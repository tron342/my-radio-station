$(document).ready(function() {
	var tableElement = $('#playlist_datatable');

    var table = tableElement.dataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": SITE_URL+"/playlist/ajaxList",
            "type": "POST",
            "data": {"csrf_token_rs":CSRF_HASH}
        },
		"aoColumns": [
			{ "mRender":checkboxFormat},
			{ "mRender":playSoundFormat},
			null, null,
			{ "mRender":timeFormat}
		],
		"createdRow": function ( row, data, index ) {
            $(row).attr('id-playlist', data[0]);
        },
		 "aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 0, 1 ] },
          { "bVisible": false, "aTargets": [3] }
		],
		"oColVis": {
            "aiExclude": [ 0, 1 ]
        },
		"aaSorting": [[ 2, "asc" ]],
		 bAutoWidth     : false
	});
	$("#playlist_datatable_wrapper div.toolbar").append('<button class="btn tip btn-white " id="add-playlist" style="margin:0 5px;" title="'+globalLang['add']+'"><i class="icon-plus"></i></button>');
	$("#playlist_datatable_wrapper div.toolbar").append('<button class="btn tip btn-white " disabled id="edit-playlist" style="margin:0 5px;" title="'+globalLang['edit']+'"><i class="icon-pencil"></i></button>');
	$("#playlist_datatable_wrapper div.toolbar").append('<button class="btn tip btn-danger" disabled id="delete-playlist" style="margin:0 5px;" title="'+globalLang['delete']+'"><i class="icon-trash"></i></button>');
	$('.tip').tooltip();
	$('#playlist_datatable_wrapper .dataTables_filter input').addClass("span12 ").attr("placeholder",$('#playlist_datatable_wrapper .dataTables_filter label').text()); // modify table search input
    $('#playlist_datatable_wrapper .dataTables_length select').addClass("select2-wrapper span12"); // modify table per page dropdown

	// TAB ACTIONS

    $('#tab-playlist a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	// LIST ACTIONS

	$('#add-playlist').on('click', function(e){
		e.preventDefault();
		$('#tab-playlist a[href="#tabediting"]').parent('li').show();
		$('#tab-playlist a[href="#tabediting"]').tab('show');
		var tab_content = $('#tab-playlist').next('.tab-content');
		var tabediting = $(tab_content).find('#tabediting');
		var form = $(tabediting).find('form');
		$(form).attr('action', SITE_URL+'/playlist/add');
		$(tabediting).find('.tab-title h4').html(globalLang['create']+" <span class=\"semi-bold\">"+globalLang['playlist']+"</span>");
		$(form).find('[name="name"]').val("");
		$(form).find('[name="description"]').val("");
		$(form).find('[name="name"]').focus();
		$('#tracks_datatable').trigger("tabediting_change");
		$("#playlist_datatable").trigger("unselect_all");
		$('#playlist_tracks tbody tr').remove();
	});

	$('#edit-playlist').click(function(e){
		var id_playlist = $("#playlist_datatable tr.row_selected").attr("id-playlist");
		$("#playlist_datatable").trigger("unselect_all");
		e.preventDefault();
		edit_playlist(id_playlist);
	});

	function edit_playlist(id_playlist){
		$.ajax({
			url: SITE_URL+'/playlist/ajaxElement',
			data: {id:id_playlist, "csrf_token_rs": CSRF_HASH},
			dataType: 'JSON',
			type: 'POST',
			success: function(data){
				$('#tab-playlist a[href="#tabediting"]').parent('li').show();
				$('#tab-playlist a[href="#tabediting"]').tab('show');
				var tab_content = $('#tab-playlist').next('.tab-content');
				var tabediting = $(tab_content).find('#tabediting');
				var form = $(tabediting).find('form');
				$(form).attr('action', SITE_URL+'/playlist/edit?id='+id_playlist);
				$(tabediting).find('.tab-title h4').html(globalLang['editing']+" <span class=\"semi-bold\">\""+data.name+"\"</span>");
				$(form).find('[name="name"]').val(data.name);
				$(form).find('[name="description"]').val(data.description);
				$(form).find('[name="audiolength"]').val(data.audiolength);
				$(form).find('[name="name"]').focus();
				$('#tracks_datatable').trigger("tabediting_change");
				$('#playlist_tracks tbody tr').remove();
				$.each(data.tracks, function(i, row_data){
					var durration = parseFloat(row_data.time_end) - parseFloat(row_data.time_start);
					var html = '<tr data-durration="'+durration+'">'+
					'<input type="hidden" name="track_id[]" value="'+row_data.track_id+'">'+
					'<input type="hidden" name="time_start[]" value="">'+
					'<input type="hidden" name="time_end[]" value="">'+
					'<td><i class="icon-move"></i></td>'+
					'<td>'+row_data.title+'</td>'+
					'<td></td>'+
					'<td></td>'+
					'<td>'+timeFormat(durration)+'</td>'+
					'<td><button type="button" class="btn btn-small btn-danger delete-pltr"><i class="icon-trash"></i></button></td>'+
					"</tr>";
					$('#playlist_tracks tbody').append(html);
				});
				$('#playlist_tracks').trigger("calculate_times");
			}
		});
	}

	$('#delete-playlist').click(function(e){
		if( !confirm(globalLang['confirm_del_pl']) ){
			e.preventDefault();
			return false;
		}
		$.each($("#playlist_datatable tr.row_selected"), function(i, row){
			var id_playlist = $(row).attr("id-playlist");
			$.ajax({
				url: SITE_URL+'/playlist/delete',
				data: {id:id_playlist, "csrf_token_rs": CSRF_HASH},
				dataType: 'JSON',
				type: 'POST',
				success: function(data){
					if( data.status == "success" ){
						table._fnReDraw();
					}else{
						console.error(data.message);
					}
				}
			});
		});
		e.preventDefault();
		return false;
	});

	// EDIT PLAYLIST ACTIONS

	$('#editPlayList #cancelediting').on('click', function(){
		$('#tab-playlist a[href="#tabplaylists"]').tab('show');
		$('#tab-playlist a[href="#tabediting"]').parent('li').hide();
		$('#tracks_datatable').trigger("tabediting_change");
	});

	$('#editPlayList').on('submit', function(e){
		var form = $(this);
		$.ajax({
			url: $(form).attr('action'),
			data: $(form).serialize(),
			dataType: 'JSON',
			type: 'POST',
			success: function(data){
				if( data.status == "success" ){
					table._fnReDraw();
					edit_playlist(data.message);
				}else{
					console.error(data.message);
				}
			}
		});
		e.preventDefault();
		return false;
	});

	tableElement.on("select-count", function(){
		var select_count = tableElement.data('select-count');
		$('#edit-playlist').attr("disabled", "disabled");
		$('#delete-playlist').attr("disabled", "disabled");

		if( select_count == 1 ){
			$('#edit-playlist').removeAttr("disabled");
		}
		if( select_count > 0 ){
			$('#delete-playlist').removeAttr("disabled");
		}
	});

	$('#playlist_tracks').on("calculate_times", function(){
		var total_time = 0;
		$.each($("#playlist_tracks tbody tr"), function(i, row){
			var durration = parseFloat($(row).data('durration'));
			$(row).find('input[name="time_start[]"]').val(total_time);
			$(row).find('input[name="time_end[]"]').val(durration+total_time);
			$(row).find('td:eq(2)').text(timeFormat(total_time));
			$(row).find('td:eq(3)').text(timeFormat(durration+total_time));
			total_time += durration;
		});
		$('#playlist_duration span').text(timeFormat(total_time));
		$("#editPlayList").find('[name="audiolength"]').val(total_time);
	});

	$('#playlist_tracks').on('click', '.delete-pltr', function(e){
		$(this).closest('tr').remove();
		$('#playlist_tracks').trigger("calculate_times");
		e.preventDefault();
		return false;
	});

	$('#playlist_tracks').on('click', '.delete-all-pltr', function(e){
		$('#playlist_tracks tbody tr').remove();
		$('#playlist_tracks').trigger("calculate_times");
		e.preventDefault();
		return false;
	});

	$('#playlist_tracks tbody').sortable({
	    opacity: 0.6,
	    placeholder: 'ui-placeholder',
	    tolerance: 'pointer',
	    scroll: true,
	    //handle: 'i.icon-move',
        update: function(event, ui) {
			$('#playlist_tracks').trigger("calculate_times");
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

    $(".tab-content").ajaxStart(function() {
        blockUI($(this));
    })
    .ajaxStop(function() {
        unblockUI($(this));
    });
});
