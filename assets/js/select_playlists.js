$(document).ready(function() {
	var tableElement = $('#playlist_select');

    var table = tableElement.dataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": SITE_URL+"/playlist/ajaxList",
            "type": "POST",
            "data": {"csrf_token_rs": CSRF_HASH}
        },
		"aoColumns": [
			{ "mRender":checkboxFormat},
			{ "mRender":playSoundFormat},
			null, null,
			{ "mRender":timeFormat}
		],
		"createdRow": function ( row, data, index ) {
            $(row).attr('id-playlist', data[0]);
            var data_row = {playlist_id:data[0], durration:data[4], name: data[2]};
            $(row).attr('data-playlist', JSON.stringify(data_row));
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
	$('.tip').tooltip();
	$('#playlist_select_wrapper .dataTables_filter input').addClass("span12 ").attr("placeholder",$('#playlist_select_wrapper .dataTables_filter label').text()); // modify table search input
    $('#playlist_select_wrapper .dataTables_length select').addClass("select2-wrapper span12"); // modify table per page dropdown

	// LIST ACTIONS
	$('#playlist_select_modal #select').click(function(e){
		var list = [];
		var selected_rows = $("#playlist_select tr.row_selected");
		for (var i = 0; i < selected_rows.length; i++) {
			var data = $(selected_rows[i]).data("playlist");
			list.push(data);
		}
		select_callback(list);
		$('#playlist_select_modal').modal("hide");
		e.preventDefault();
	});

	tableElement.on("select-count", function(){
		var select_count = tableElement.data('select-count');
		$('#playlist_select_modal #select').attr("disabled", "disabled");
		if( select_count > 0 ){
			$('#playlist_select_modal #select').removeAttr("disabled");
		}
	});

	$('#playlist_select_modal').on("show.bs.modal", function(){
		tableElement.trigger("unselect_all");
		table._fnReDraw();
	});
});

var select_callback = false;
function playlist_select(callback){
	select_callback = callback;
    $('#playlist_select_modal').modal("show");
}
