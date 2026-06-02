$(document).ready(function() {
    preloadEventFeed();
    setAddShowEvents($("#add-show-form"));
    $('#select-playlist').click(function(){
        playlist_select(append_playlist);
    });

    $('#calendar_playlists').on('click', '.delete-calpl', function(e){
        $(this).closest('tr').remove();
        $('#calendar_playlists').trigger("calculate_times");
        e.preventDefault();
        return false;
    });

    $('#calendar_playlists').on('click', '.delete-all-calpl', function(e){
        $('#calendar_playlists tbody tr').remove();
        $('#calendar_playlists').trigger("calculate_times");
        e.preventDefault();
        return false;
    });

    $('#calendar_playlists tbody').sortable({
        opacity: 0.6,
        placeholder: 'ui-placeholder',
        tolerance: 'pointer',
        scroll: true,
        //handle: 'i.icon-move',
        update: function(event, ui) {
            $('#calendar_playlists').trigger("calculate_times");
        },
        'start': function (event, ui) {
            ui.placeholder.html("<td colspan='6'></td>")
        },
        helper: function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        }
    }).disableSelection();

    $('#calendar_playlists').on("calculate_times", function(){
        var total_time = 0;
        var calendar_duration = parseInt( $('#add_show_duration').val() );
        $.each($("#calendar_playlists tbody tr"), function(i, row){
            var durration = parseFloat($(row).data('durration'));
            $(row).find('input[name="time_start[]"]').val(total_time);
            $(row).find('input[name="time_end[]"]').val(durration+total_time);
            $(row).find('td:eq(2)').text(timeFormat(total_time));
            $(row).find('td:eq(3)').text(timeFormat(durration+total_time));
            $(row).removeClass("table-danger table-warning");
            if( calendar_duration < total_time ){
                $(row).addClass("table-danger");
            }
            if( calendar_duration >= total_time && calendar_duration < (total_time+durration) ){
                $(row).addClass("table-warning");
            }
            total_time += durration;
        });
        var diff = total_time-calendar_duration;
        diff = timeFormat(Math.abs(diff));
        if( calendar_duration > total_time ){
            $('#calendar_playlists tfoot tr i').removeClass('icon-ok').addClass('icon-info');
            $('#calendar_playlists tfoot td').removeClass('text-success').addClass('text-error');
            $('#calendar_playlists tfoot tr td:eq(1)').text("-"+diff);
        }else{
            $('#calendar_playlists tfoot tr i').removeClass('icon-info').addClass('icon-ok');
            $('#calendar_playlists tfoot td').removeClass('text-error').addClass('text-success');
            $('#calendar_playlists tfoot tr td:eq(1)').text("+"+diff);
        }
    });

    $(".tab-content").ajaxStart(function() {
        blockUI($(this));
    })
    .ajaxStop(function() {
        unblockUI($(this));
    });
    // TAB ACTIONS

    $('#tab-calendar a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});


/**
*
*   Full Calendar callback methods.
*
*/
function createFullCalendar(){
	var mainHeight = $(window).height() - 200 - 35;
    mainHeight = mainHeight<600?600:mainHeight;
	$('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
			right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month',
        slotMinutes: 30, // minute
        firstDay: calendarPref.weekStart, // sunday
        editable: true,
        allDaySlot: false,
        isRtl: globalLang['is_rtl'],
        axisFormat: 'H:mm',
        timeFormat: {
            agenda: 'H:mm{ - H:mm}',
            month: 'H:mm{ - H:mm}'
        },
        monthNames: i18n_months,
        monthNamesShort: i18n_months_short,
        buttonText: {
            today: globalLang['today'],
            month: globalLang['month'],
            week: globalLang['week'],
            day: globalLang['day']
        },
        dayNames: i18n_days,
        dayNamesShort: i18n_days_short,
        lazyFetching: true,
        contentHeight: mainHeight,
        timezone : currentTimezone,
        serverTimestamp: calendarPref.timestamp,
        serverTimezoneOffset: calendarPref.timezoneOffset,
        events: getFullCalendarEvents,
        viewDisplay: viewDisplay,
        dayClick: dayClick,
        eventRender: eventRender,
        eventDrop: eventDrop,
        eventResize: eventResize,
        loading: function(isLoading, view) {
            if (isLoading) {
                blockUI($(".fc-content"));
            } else {
                unblockUI($(".fc-content"));
            }
        }
    });
    if( globalLang['is_rtl'] ){
        $('#calendar').removeClass('fc-ltr').addClass("fc-rtl");
    }

    $.contextMenu({
        selector: 'div.fc-event.editable',
        trigger: "left",
        ignoreRightClick: true,
        className: 'calendar-context-menu',
        build: function($el, e) {
            var data, items, callback;
            data = $el.data("event");
            function processMenuItems(oItems) {
                //define a schedule callback.
                if (oItems.schedule !== undefined) {
                    callback = function() {
                        $.post(oItems.schedule.url, {id: data.id, "csrf_token_rs": CSRF_HASH}, function(json){
                            buildScheduleDialog(json, data.id);
                        });
                    };
                    oItems.schedule.callback = callback;
                }
                //define an edit callback.
                if (oItems.edit !== undefined) {
                    callback = function() {
                        $.post(oItems.edit.url, {id: data.id, "csrf_token_rs": CSRF_HASH}, function(json){
                            beginEditShow(json);
                        });
                    };
                    oItems.edit.callback = callback;
                }
                //define a delete callback.
                if (oItems.del !== undefined) {
                    callback = function() {
                        $.post(oItems.del.url, {id: data.id, "csrf_token_rs": CSRF_HASH}, function(json){
                            scheduleRefetchEvents(json);
                        });
                    };
                    oItems.del.callback = callback;
                } else if (oItems.del_edited_instance !== undefined) {
                    //delete a single instance that has been edited (i.e. change the time)
                    callback = function () {
                        console.log(data.id);
                        $.post(oItems.del_edited_instance.url, {format: "json", id: data.id, "csrf_token_rs": CSRF_HASH}, function (json) {
                            scheduleRefetchEvents(json);
                        });
                    };
                    oItems.del_edited_instance.callback = callback;
                }
                items = oItems;
            }
            var items = {
                "edit":{
                    "name":globalLang["edit"],"icon":"icon-pencil","url":SITE_URL+"/calendar/ajaxElement"
                },
                "del":{
                    "name":globalLang["delete"],"icon":"icon-trash","url":SITE_URL+"/calendar/delete"
                }
            }
            processMenuItems(items);
            return {
                className: 'dropdown-menu',
                items: items,
                determinePosition : function($menu, x, y) {
                    $menu.css('display', 'block')
                        .position({ my: "left top", at: "right top", of: this, offset: "-20 10", collision: "fit"});
                }
            };
        }
    });
}

function append_playlist(playlists){
    for (var i = 0; i < playlists.length; i++) {
        var row_data = playlists[i];
        var durration = parseFloat(row_data.durration);
        var html = '<tr data-durration="'+durration+'">'+
        '<input type="hidden" name="playlist_id[]" value="'+row_data.playlist_id+'">'+
        '<input type="hidden" name="time_start[]" value="">'+
        '<input type="hidden" name="time_end[]" value="">'+
        '<td><i class="icon-move"></i></td>'+
        '<td>'+row_data.name+'</td>'+
        '<td></td>'+
        '<td></td>'+
        '<td>'+timeFormat(durration)+'</td>'+
        '<td><button type="button" class="btn btn-mini btn-danger delete-calpl"><i class="icon-trash"></i></button></td>'+
        "</tr>";
        $('#calendar_playlists tbody').append(html);
    }
    $('#calendar_playlists').trigger("calculate_times");
}

function beginEditShow(data){
    if (data.status == "error"){
        alert(data.message);
        return false;
    }
    redrawAddShowForm($("#add-show-form"), data);
    openAddShowForm(true);
    toggleAddShowButton(false);
}

function makeAddShowButton() {
    if($('.add-button').length === 0) {
        $('.fc-header-left')
            .prepend('<span class="fc-header-space"></span>')
            .prepend('<button onclick="showForm()" class="btn btn-small btn-primary add-button" title="'+globalLang['new_show']+'"><i class="icon-plus"></i></button>');
    }
}

function scheduleRefetchEvents() {
    $("#calendar").fullCalendar('refetchEvents');
}

function makeTimeStamp(date){
    var sy, sm, sd, h, m, s, timestamp;
    sy = date.getFullYear();
    sm = date.getMonth() + 1;
    sd = date.getDate();
    h = date.getHours();
    m = date.getMinutes();
    s = date.getSeconds();
    timestamp = sy+"-"+ pad(sm, 2) +"-"+ pad(sd, 2) +" "+ pad(h, 2) +":"+ pad(m, 2) +":"+ pad(s, 2);
    return timestamp;
}

function pad(number, length)
{
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}

serverTimezoneOffset = timezoneOffset;
function adjustDateToServerDate(date, serverTimezoneOffset){
    //date object stores time in the browser's localtime. We need to artificially shift
    //it to
    var timezoneOffset = date.getTimezoneOffset()*60*1000;

    date.setTime(date.getTime() + timezoneOffset + serverTimezoneOffset*1000);

    /* date object has been shifted to artificial UTC time. Now let's
     * shift it to the server's timezone */
    return date;
}


function dayClick(date, allDay, jsEvent, view){
    var now, today, selected, chosenDate, chosenTime;
    now = adjustDateToServerDate(new Date(), serverTimezoneOffset);
    if(view.name === "month") {
        today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        selected = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    }
    else {
        today = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes());
        selected = new Date(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes());
    }

    if(selected >= today) {
        // get current duration value on the form
        var duration = parseInt($("#add_show_duration").val());
        // duration in milisec
        var duration = duration * 1000;

        var startTime_string;
        var startTime = 0;
        // get start time value on the form
        if(view.name === "month") {
            startTime_string = $("#add_show_start_time").val();
            var startTime_info = startTime_string.split(':');
            if (startTime_info.length == 2) {
                var start_time_temp = (parseInt(startTime_info[0],10) * 60 * 60 * 1000)
                    + (parseInt(startTime_info[1], 10) * 60 * 1000);
                if (!isNaN(start_time_temp)) {
                    startTime = start_time_temp;
                }
            }
        }else{
            // if in day or week view, selected has all the time info as well
            // so we don't ahve to calculate it explicitly
            startTime_string = pad(selected.getHours(),2)+":"+pad(selected.getMinutes(),2)
            startTime = 0
        }

        // calculate endDateTime
        var endDateTime = new Date(selected.getTime() + startTime + duration);

        chosenDate = selected.getFullYear() + '-' + pad(selected.getMonth()+1,2) + '-' + pad(selected.getDate(),2);
        var endDateFormat = endDateTime.getFullYear() + '-' + pad(endDateTime.getMonth()+1,2) + '-' + pad(endDateTime.getDate(),2);


        //TODO: This should all be refactored into a proper initialize() function for the show form.
        setupStartTimeWidgets(); //add-show.js
        $("#add_show_start_date").val(chosenDate);
        $("#add_show_end_date_no_repeat").val(endDateFormat);
        //DO NOT SET THIS - This is the "end date" for repeating shows!
        //$("#add_show_end_date").val(endDateFormat);
        if(view.name !== "month") {
            var endTimeString = pad(endDateTime.getHours(),2)+":"+pad(endDateTime.getMinutes(),2);
            $("#add_show_start_time").val(startTime_string)
            $("#add_show_end_time").val(endTimeString)
        }
        openAddShowForm(false);
        makeAddShowButton();
        toggleAddShowButton(false);
    }
}

function openAddShowForm(isEdit) {
    if( isEdit == undefined || !isEdit ){
        $("#add-show-form").attr("action", SITE_URL+"/calendar/add");
        $('#add_show_name').val("Untitled event");
        $('select[name="repeat_type"] option').removeAttr("selected");
        $('select[name="repeat_type"]').trigger("change");
        $('#add_show_duration').val("0");
        $('#add_show_start_date').trigger("change");
        $('#add_show_background_color').parent().colorpicker('setValue', "#ffcc00");
        $('#add_show_color').parent().colorpicker('setValue', "#000000");
        $('#calendar_playlists tbody tr').remove();
        append_playlist([]);
    }
    $('#tab-calendar a[href="#tabeditingshow"]').tab('show');
    var editor = $("#calendar-editor");
    if(editor.length == 1) {
        if( (editor.css('display')=='none')) {
            editor.show();
			$('#calendar-tile').removeClass('span12').addClass('span8');
        }
        $add_show_name = $("#add_show_name");
        $add_show_name.focus();
        $add_show_name.select();
    }
    setTimeout(function() {
        var formHeight = $('#calendar-tile').height();
        $('#add-show-form .vertical-scroll').css('height', formHeight);
        $('#add-show-form .vertical-scroll').css('maxHeight', formHeight -56 -48);
    }, 100);
}

function showForm() {
    var now = moment(new Date()).tz(currentTimezone);
    $('.fc-content table td[data-date="'+now.format('YYYY-MM-DD')+'"]').click();
}

function redrawAddShowForm($el, data) {
    data = JSON.parse(data);
    $('#add_show_name').val(data.name);

    $('#add_show_duration').val(data.duration);
    var starts = moment(new Date(data.starts));
    $('#add_show_start_date').val(starts.format('YYYY-MM-DD'));
    $('#add_show_start_time').val(starts.format('HH:mm'));

    var ends = moment(new Date(data.ends));
    $('#add_show_end_date_no_repeat').val(ends.format('YYYY-MM-DD'));
    $('#add_show_end_time').val(ends.format('HH:mm'));

    $('select[name="repeat_type"] option').removeAttr("selected");
    $('select[name="repeat_type"] option[value="'+data.repeat_type+'"]').attr("selected","selected");

    if( data.repeat_days != null && data.repeat_days.length > 0 ){
        var repeat_days = data.repeat_days.split(",");
        $('#add_show_day_check input').removeAttr("checked");
        for (var i = 0; i < repeat_days.length; i++) {
            $('#add_show_day_check input[value="'+repeat_days[i]+'"]').attr("checked","checked");
        }
    }

    if( data.end_date != null ){
        $('#add_show_end_date').val(data.end_date);
    }else{
        $('#add_show_end_date').val("");
    }
    if( data.no_end != null ){
        $('#chbx_d_no_end').prop("checked", data.no_end==1);
    }else{
        $('#chbx_d_no_end').attr("checked", "checked");
    }

    $('#add_show_background_color').parent().colorpicker('setValue', data.background_color);
    $('#add_show_color').parent().colorpicker('setValue', data.color);
    $('#calendar_playlists tbody tr').remove();
    append_playlist(data.playlists);


    $el.attr("action", SITE_URL+"/calendar/edit?id="+data.id);
    $('#add_show_start_date').trigger("change");
    setAddShowEvents($el);
}

function closeAddShowForm(event) {
    event.stopPropagation();
    event.preventDefault();
    var $el = $("#calendar-editor");
    $el.hide();
	$('#calendar-tile').removeClass('span8').addClass('span12');
    toggleAddShowButton(true);
}

function toggleAddShowButton(enable){
    var aTag = $('.add-button');
    aTag.prop('disabled', !enable);
    $("#calendar").fullCalendar('render');
    //$("#calendar").fullCalendar('refetchEvents');
}

function setupStartTimeWidgets() {
    //Set the show start time to now (in the show timezone)
    var now = moment(new Date()).tz(currentTimezone);
    $('#add_show_start_date').val(now.format('YYYY-MM-DD'));
    $('#add_show_start_time').val(now.format('HH:mm'));
    //Set the show end time to be now + 1 hour.
    var nowShowEnd = now.add(1, 'h');
    $('#add_show_end_date_no_repeat').val(nowShowEnd.format('YYYY-MM-DD'));
    $('#add_show_end_time').val(nowShowEnd.format('HH:mm'));
}

function padZeroes(number, length)
{
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}


function viewDisplay( view ) {
    view_name = view.name;
    if(view.name === 'agendaDay' || view.name === 'agendaWeek') {
        var calendarEl = this;
        var select = $('<select class="schedule_change_slots input_select no-margin span12"/>')
            .append('<option value="1">1m</option>')
            .append('<option value="5">5m</option>')
            .append('<option value="10">10m</option>')
            .append('<option value="15">15m</option>')
            .append('<option value="30">30m</option>')
            .append('<option value="60">60m</option>')
            .change(function(){
                var slotMin = $(this).val();
                var opt = view.calendar.options;
                var date = $(calendarEl).fullCalendar('getDate');
                opt.slotMinutes = parseInt(slotMin);
                opt.events = getFullCalendarEvents;
                opt.defaultView = view.name;
                $(calendarEl)
                    .fullCalendar('destroy')
                    .fullCalendar(opt)
                    .fullCalendar( 'gotoDate', date );
                if( globalLang['is_rtl'] ){
                    $(calendarEl).removeClass('fc-ltr').addClass("fc-rtl");
                }
            });
        var topLeft = $(view.element).find("table.fc-agenda-days > thead th:first");
        $('table.fc-agenda-days').next('div').css('top','66px');
        $('table.fc-agenda-days > thead th').css('line-height','44px');
        topLeft.empty().append(select);
        var slotMin = view.calendar.options.slotMinutes;
        $('.schedule_change_slots option[value="'+slotMin+'"]').attr('selected', 'selected');
    }

    if(($("#calendar-editor").length == 1) && ($("#calendar-editor").css('display')=='none') && ($('.fc-header-left > span').length == 4)) {
        makeAddShowButton();
    }
}

function eventRender(event, element, view) {
    $(element).addClass("fc-show-instance-"+event.id);
    $(element).attr("data-show-id", event.showId);
    $(element).attr("data-show-linked", event.linked);
    $(element).data("event", event);

    if( event.editable ){
        $(element).addClass("editable");
    }

    //only put progress bar on shows that aren't being recorded.
    if((view.name === 'agendaDay' || view.name === 'agendaWeek') && event.record === 0) {
        var div = $('<div/>');
        div
            .height('5px')
            .width('95%')
            .css('margin-top', '1px')
            .css('margin-left', 'auto')
            .css('margin-right', 'auto')
            .progressbar({
                value: event.percent
            });

        $(element).find(".fc-event-content").append(div);
    }

    if (event.record === 0 && event.rebroadcast === 0) {
        var el = (view.name === 'month') ? $(element).find(".fc-event-title") : $(element).find(".fc-event-time");

        var rotationFlag = (event.instance_rotation > 0) || (event.instance_rotation !== -1 && event.rotation > 0) || event.rotation_scheduled;

        if (view.name === 'agendaDay' || view.name === 'agendaWeek') {

            if (event.linked) {
                el.before('<span title="'+("Linked show")+'" class="small-icon calendar-icon-grid-link"><i class="icon-plus-sign icon-white" /></span>');
            }

            if (event.auto_dj) {
                el.before('<span title="'+("Created by Auto DJ")+'" class="small-icon calendar-icon-grid-autodj"><i class="icon-user icon-white" /></span>');
            }

            if (rotationFlag) {
                el.before('<span title="'+("Rotation scheduled")+'" class="small-icon calendar-icon-grid-rotation"><i class="icon-repeat icon-white" /></span>');
            } else {
                if (event.show_empty === 1) {
                    el.before('<span title="'+("Show is empty")+'" class="small-icon calendar-icon-grid-warning"><i class="icon-exclamation-sign icon-orange" /></span>');
                } else if (event.show_partial_filled === true) {
                    el.before('<span title="'+("Show is partially filled")+'" class="small-icon calendar-icon-grid-warning"><i class="icon-exclamation-sign icon-white" /></span>');
                }
            }

        } else if (view.name === 'month') {
            if (event.linked) {
                el.after('<span title="'+("Linked show")+'" class="small-icon calendar-icon-grid-link"><i class="icon-plus-sign icon-white" /></span>');
            }

            if (event.auto_dj) {
                el.after('<span title="'+("Created by Auto DJ")+'" class="small-icon calendar-icon-grid-autodj"><i class="icon-user icon-white" /></span>');
            }

            if (rotationFlag) {
                el.after('<span title="'+("Rotation scheduled")+'" class="small-icon calendar-icon-grid-rotation"><i class="icon-repeat icon-white" /></span>');
            }

            if (event.show_empty === 1) {
                el.after('<span title="'+("Show is empty")+'" class="small-icon calendar-icon-grid-warning"><i class="icon-exclamation-sign icon-orange" /></span>');
            } else if (event.show_partial_filled === true) {
                el.after('<span title="'+("Show is partially filled")+'" class="small-icon calendar-icon-grid-warning"><i class="icon-exclamation-sign icon-white" /></span>');
            }
        }
    }

    //rebroadcast icon
    if (event.rebroadcast === 1) {
        if (view.name === 'agendaDay' || view.name === 'agendaWeek') {
            $(element).find(".fc-event-time").before('<span class="small-icon rebroadcast"></span>');
        } else if (view.name === 'month') {
            $(element).find(".fc-event-title").after('<span class="small-icon rebroadcast"></span>');
        }
    }

    if (event.nowPlaying === true) {
        $(element).addClass("calendar-now-playing");
    }
}

function eventDrop(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
	$.ajax({
		url: SITE_URL+'/calendar/moveShow',
		data: {
            day: dayDelta,
            min: minuteDelta,
            id: event.id,
            "csrf_token_rs": CSRF_HASH
        },
		dataType: 'JSON',
		type: 'POST',
		success: function(data){
			if( data.status == "success" ){
				scheduleRefetchEvents();
			}else{
				console.error(data.message);
			}
		}
	});
}

function eventResize( event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view ) {
	$.ajax({
		url: SITE_URL+'/calendar/resizeShow',
		data: {
            day: dayDelta,
            min: minuteDelta,
            id: event.id,
            showId: event.showId,
            "csrf_token_rs": CSRF_HASH
        },
		dataType: 'JSON',
		type: 'POST',
		success: function(data){
			if( data.status == "success" ){
				scheduleRefetchEvents();
			}else{
				console.error(data.message);
			}
		}
	});
}

function preloadEventFeed () {
    createFullCalendar();
}

function getFullCalendarEvents(start, end, callback) {
	$.ajax({
		url: SITE_URL+'/calendar/eventsFeed',
		data: {start: makeTimeStamp(start), end: makeTimeStamp(end), "csrf_token_rs": CSRF_HASH},
		dataType: 'JSON',
		type: 'POST',
		success: function(json){
	        callback(json);
	        //getUsabilityHint();
		}
	});
    $(".fc-button").addClass("btn btn-small btn-success").removeClass('fc-button fc-state-default fc-corner-right');
    $(".fc-button-prev, .fc-button-next").addClass("btn-sm-padding btn-primary");
}
var view_name;

function onStartTimeSelect(){
    $("#add_show_start_time").trigger('input');
}

function onEndTimeSelect(){
    $("#add_show_end_time").trigger('input');
}

//dateText mm-dd-yy
function startDpSelect(dateText, inst) {
    var time, date;
    time = dateText.split("-");
    date = new Date(time[0], time[1] - 1, time[2]);
    if (inst.input)
        inst.input.trigger('input');
}

function endDpSelect(dateText, inst) {
    var time, date;
    time = dateText.split("-");
    date = new Date(time[0], time[1] - 1, time[2]);
    if (inst.input)
        inst.input.trigger('input');
}

function createDateInput(el, onSelect) {
    el.datepicker({
        minDate: adjustDateToServerDate(new Date(), timezoneOffset),
        onSelect: onSelect,
        format: 'yyyy-mm-dd',
        monthNames: i18n_months,
        dayNamesMin: i18n_days_short,
        closeText: 'Close',
        //showButtonPanel: true,
        weekStart: calendarPref.weekStart,
        language: 'script',
    });
}

/*
 * SET ADD SHOW EVENTS (setAddShowEvents)
 */
function setAddShowEvents(form) {
    //var form = $("#add-show-form");
    $('#cancel-add-show').click(closeAddShowForm);

	function repeating_change_type(){
    	selected = $('select[name="repeat_type"]').val();
    	if( selected == "-1" ){ // no repeat
    		$('#add_show_day_check').hide();
    		$('#add_show_end').hide();
    	}else if( selected == "2" ){ // monthly
    		$('#add_show_day_check').hide();
    		$('#add_show_end').show();
    	}else{
    		$('#add_show_day_check').show();
    		$('#add_show_end').show();
    	}
    }
    repeating_change_type();
    $('select[name="repeat_type"]').change(repeating_change_type);

    function endDateVisibility(){
    	if( form.find("#chbx_d_no_end").is(':checked') ){
			$('input[name="add_show_end_date"]').attr("disabled", "disabled");
    	}else{
			$('input[name="add_show_end_date"]').removeAttr("disabled");
    	}
    }
    endDateVisibility();
    form.find("#chbx_d_no_end").click(endDateVisibility);

    createDateInput(form.find("#add_show_start_date"), startDpSelect);
    createDateInput(form.find("#add_show_end_date_no_repeat"), endDpSelect);
    createDateInput(form.find("#add_show_end_date"), endDpSelect);

    $("#add_show_start_time").timepicker({
        defaultTime: '00:00',
        onSelect: onStartTimeSelect,
        minuteStep: 1,
        showSeconds: false,
        showMeridian: false
    });
    $("#add_show_end_time").timepicker({
        onSelect: onEndTimeSelect,
        minuteStep: 1,
        showSeconds: false,
        showMeridian: false
    });

    form.find(".colorchooser").colorpicker({
        onChange: function (hsb, hex, rgb, el) {
            $(el).val(hex);
        },
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    });


    form.ajaxStart(function() {
        blockUI($(this));
    })
    .ajaxStop(function() {
        unblockUI($(this));
    });

	form.unbind('submit').bind('submit', function(e){
        form.find(':submit').prop('disabled', true);
        var isADD = $(form).attr('action').indexOf('/add')!=-1;
		$.ajax({
			url: $(form).attr('action'),
			data: $(form).serialize(),
			dataType: 'JSON',
			type: 'POST',
			success: function(data){
				if( data.status == "success" ){
					//edit_playlist(data.message);
                    if( isADD ){
                        $.post(SITE_URL+"/calendar/ajaxElement", {id: data.message, "csrf_token_rs": CSRF_HASH}, function(json){
                            beginEditShow(json);
                        });
                    }else{
					   $('#cancel-add-show').click();
                    }
                    scheduleRefetchEvents();
				}else{
					console.error(data.message);
				}
			},
            complete: function(){
                form.find(':submit').prop('disabled', false);
            }
		});
		e.preventDefault();
		return false;
	});

    var regDate = new RegExp(/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/);
    var regTime = new RegExp(/^[0-2][0-9]:[0-5][0-9]$/);

    // when start date/time changes, set end date/time to start date/time+1 hr
    $('#add_show_start_date, #add_show_start_time').bind('change', function(){
        var startDateString = $('#add_show_start_date').val();
        var startTimeString = $('#add_show_start_time').val();

        if(regDate.test(startDateString) && regTime.test(startTimeString)){

            var startDate = startDateString.split('-');
            var startTime = startTimeString.split(':');
            var startDateTime = new Date(startDate[0], parseInt(startDate[1], 10)-1, startDate[2], startTime[0], startTime[1], 0, 0);

            var endDateString = $('#add_show_end_date_no_repeat').val();
            var endTimeString = $('#add_show_end_time').val()
            var endDate = endDateString.split('-');
            var endTime = endTimeString.split(':');
            var endDateTime = new Date(endDate[0], parseInt(endDate[1], 10)-1, endDate[2], endTime[0], endTime[1], 0, 0);

            if(startDateTime.getTime() >= endDateTime.getTime()){
                var duration = $('#add_show_duration').val();
                // parse duration
                var time = moment.duration(duration);
                endDateTime = new Date(startDateTime.getTime() + time);
            }

            var endDateFormat = endDateTime.getFullYear() + '-' + pad(endDateTime.getMonth()+1,2) + '-' + pad(endDateTime.getDate(),2);
            var endTimeFormat = pad(endDateTime.getHours(),2) + ':' + pad(endDateTime.getMinutes(),2);

            if(startDateTime.getTime() > endDateTime.getTime()){
                $('#add_show_end_date_no_repeat').css('background-color', '#F49C9C');
                $('#add_show_end_time').css('background-color', '#F49C9C');
            }else{
                $('#add_show_end_date_no_repeat').css('background-color', '');
                $('#add_show_end_time').css('background-color', '');
            }
            $('#add_show_end_date_no_repeat').val(endDateFormat);
            $('#add_show_end_time').val(endTimeFormat);

            // calculate duration
            var startDateTimeString = startDateString + " " + startTimeString;
            var endDateTimeString = $('#add_show_end_date_no_repeat').val() + " " + $('#add_show_end_time').val();
            var timezone = $("#add_show_timezone").val();
            calculateDuration(startDateTimeString, endDateTimeString, timezone);
        }
    });

    // when end date/time changes, check if the changed date is in past of start date/time
    $('#add_show_end_date_no_repeat, #add_show_end_time').bind('change', function(){
        var endDateString = $('#add_show_end_date_no_repeat').val();
        var endTimeString = $('#add_show_end_time').val()

        if(regDate.test(endDateString) && regTime.test(endTimeString)){
            var startDateString = $('#add_show_start_date').val();
            var startTimeString = $('#add_show_start_time').val();
            var startDate = startDateString.split('-');
            var startTime = startTimeString.split(':');
            var startDateTime = new Date(startDate[0], parseInt(startDate[1], 10)-1, startDate[2], startTime[0], startTime[1], 0, 0);

            var endDate = endDateString.split('-');
            var endTime = endTimeString.split(':');
            var endDateTime = new Date(endDate[0], parseInt(endDate[1], 10)-1, endDate[2], endTime[0], endTime[1], 0, 0);

            if(startDateTime.getTime() > endDateTime.getTime()){
                $('#add_show_end_date_no_repeat').css('background-color', '#F49C9C');
                $('#add_show_end_time').css('background-color', '#F49C9C');
            }else{
                $('#add_show_end_date_no_repeat').css('background-color', '');
                $('#add_show_end_time').css('background-color', '');
            }

            // calculate duration
            var startDateTimeString = startDateString + " " + startTimeString;
            var endDateTimeString = endDateString + " " + endTimeString;
            var timezone = $("#add_show_timezone").val();
            calculateDuration(startDateTimeString, endDateTimeString, timezone);
        }
    });

    function calculateDuration(startDateTime, endDateTime, timezone){
    	var date1 = new Date(startDateTime);
		var date2 = new Date(endDateTime);
		var diff = (date2-date1)/1000;
    	$('#add_show_duration').val(diff).prev("h5").find("span").text(timeFormat(diff));
    }

}
