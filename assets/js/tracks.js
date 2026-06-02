$(document).ready(function() {
	var tableElement = $('#tracks_datatable');

    var trackTable = tableElement.dataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": SITE_URL+"/track/ajax_list",
            "type": "POST",
            "data": {"csrf_token_rs": CSRF_HASH}
        },
		"aoColumns": [
			{ "mRender":checkboxFormat},
			{ "mRender":playSoundFormat},
			null, null, null, { "mRender":timeFormat}, null, null, null, null,
			null,
			{ "mRender":fileSizeFormat},
			{ "mRender":dateFormat}
		],
		"createdRow": function ( row, data, index ) {
            $(row).attr('id-track', data[0]);
        },
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 0, 1 ] },
		],
		"oColVis": {
            "aiExclude": [ 0, 1 ],
        },
		"aaSorting": [[ 2, "asc" ]],
		bAutoWidth : false,
	});

	$("#tracks_datatable_wrapper div.toolbar").append('<button id="move-track" disabled class="btn tip btn-white" style="margin:0 5px;" title="'+globalLang['move_to_playlist']+'"><i class="icon-arrow-right"></i></button>');
	$("#tracks_datatable_wrapper div.toolbar").append('<button id="delete-track" disabled class="btn tip btn-danger" style="margin:0 5px;" title="'+globalLang['delete']+'"><i class="icon-trash"></i></button>');
	$('.tip').tooltip();
	$('#tracks_datatable_wrapper .dataTables_filter input').addClass("span12 ").attr("placeholder",$('#tracks_datatable_wrapper .dataTables_filter label').text()); // modify table search input
    $('#tracks_datatable_wrapper .dataTables_length select').addClass("select2-wrapper span12"); // modify table per page dropdown


    $('#tab-tracks a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#move-track').on('click', function(){
		var data = trackTable.DataTable().rows().data();
		$.each($("#tracks_datatable tr.row_selected"), function(i, row){
			var index = $(row).index();
			var row_data = data[index];
			var durration = parseFloat(row_data[5]);
			var html = '<tr data-durration="'+durration+'">'+
			'<input type="hidden" name="track_id[]" value="'+row_data[0]+'">'+
			'<input type="hidden" name="time_start[]" value="">'+
			'<input type="hidden" name="time_end[]" value="">'+
			'<td><i class="icon-move"></i></td>'+
			'<td>'+row_data[2]+'</td>'+
			'<td></td>'+
			'<td></td>'+
			'<td>'+timeFormat(durration)+'</td>'+
			'<td><button type="button" class="btn btn-small btn-danger delete-pltr"><i class="icon-trash"></i></button></td>'+
			"</tr>";
			$('#playlist_tracks tbody').append(html);
		});
		$("#tracks_datatable").trigger("unselect_all");
		$('#playlist_tracks').trigger("calculate_times");
	});

	$('#delete-track').click(function(e){
		if( !confirm(globalLang['confirm_del_tr']) ){
			e.preventDefault();
			return false;
		}
		$.each($("#tracks_datatable tr.row_selected"), function(i, row){
			var id_track = $(row).attr("id-track");
			$.ajax({
				url: SITE_URL+'/track/delete',
				data: {id:id_track, "csrf_token_rs": CSRF_HASH},
				dataType: 'JSON',
				type: 'POST',
				success: function(data){
					if( data.status == "success" ){
						trackTable._fnReDraw();
					}else{
						console.error(data.message);
					}
				}
			});
		});
		e.preventDefault();
		return false;
	});

	new Dropzone("#my-awesome-dropzone", {
		paramName: "file",
		maxFilesize: 1024, // MB
        acceptedFiles: "audio/*",
		accept: function(file, done) {
			done();
		},
		success: function(file, done) {
			$('#tab-tracks a[href="#tablisttraks"]').tab('show');
			trackTable._fnReDraw();
		}
	});

	tableElement.on("select-count", function(){
		var select_count = tableElement.data('select-count');
		$('#move-track').attr("disabled", "disabled");
		$('#delete-track').attr("disabled", "disabled");
		if( select_count > 0 ){
			$('#delete-track').removeAttr("disabled");
			if( $('#tabediting').length > 0 && $('#tabediting').is(".active") ){
				$('#move-track').removeAttr("disabled");
			}
		}
	});

	tableElement.on("tabediting_change", function(){
		var select_count = tableElement.data('select-count');
		$('#move-track').attr("disabled", "disabled");
		if( select_count > 0 ){
			if( $('#tabediting').length > 0 && $('#tabediting').is(".active") ){
				$('#move-track').removeAttr("disabled");
			}
		}
	});
});
