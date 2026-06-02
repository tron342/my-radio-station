/* Set the defaults for DataTables initialisation */
$.extend( true, $.fn.dataTable.defaults, {
	"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'p i>>",
	"sPaginationType": "bootstrap",
	"oLanguage": {
	    "sEmptyTable":     globalLang['sEmptyTable'],
	    "sInfo":           globalLang['sInfo'],
	    "sInfoEmpty":      globalLang['sInfoEmpty'],
	    "sInfoFiltered":   globalLang['sInfoFiltered'],
	    "sLengthMenu":     globalLang['sLengthMenu'],
	    "sLoadingRecords": globalLang['sLoadingRecords'],
	    "sProcessing":     globalLang['sProcessing'],
	    "sSearch":         globalLang['sSearch'],
	    "sZeroRecords":    globalLang['sZeroRecords'],
	},
	"stateSave": true,
	"sDom": "<'row-fluid'<'span12'f>><'toolbar'lC>t<'row-fluid'<'span12'p i>>",
	"oTableTools": {
		"aButtons": [
			{
				"sExtends":    "collection",
				"sButtonText": "<i class='icon-cloud-download'></i>",
				"aButtons":    [ "csv", "xls", "pdf", "copy"]
			}
		]
	},
	"sPaginationType": "bootstrap",
} );

$.extend( true, $.fn.dataTable.ColVis.defaults, {
	active: 'click',
	buttonText: globalLang['columns'],
	buttonTitle: globalLang['show_hide_cols']
});



/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper form-inline"
} );


/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
{
	return {
		"iStart":         oSettings._iDisplayStart,
		"iEnd":           oSettings.fnDisplayEnd(),
		"iLength":        oSettings._iDisplayLength,
		"iTotal":         oSettings.fnRecordsTotal(),
		"iFilteredTotal": oSettings.fnRecordsDisplay(),
		"iPage":          oSettings._iDisplayLength === -1 ?
			0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
		"iTotalPages":    oSettings._iDisplayLength === -1 ?
			0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	};
};


/* Bootstrap style pagination control */
$.extend( $.fn.dataTableExt.oPagination, {
	"bootstrap": {
		"fnInit": function( oSettings, nPaging, fnDraw ) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function ( e ) {
				e.preventDefault();
				if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
					fnDraw( oSettings );
				}
			};

			$(nPaging).addClass('pagination').append(
				'<ul>'+
					'<li class="prev disabled"><a href="#"><i class="icon-chevron-left"></i></a></li>'+
					'<li class="next disabled"><a href="#"><i class="icon-chevron-right"></i></a></li>'+
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
			$(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
		},

		"fnUpdate": function ( oSettings, fnDraw ) {
			var iListLength = 5;
			var oPaging = oSettings.oInstance.fnPagingInfo();
			var an = oSettings.aanFeatures.p;
			var i, ien, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

			if ( oPaging.iTotalPages < iListLength) {
				iStart = 1;
				iEnd = oPaging.iTotalPages;
			}
			else if ( oPaging.iPage <= iHalf ) {
				iStart = 1;
				iEnd = iListLength;
			} else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
				iStart = oPaging.iTotalPages - iListLength + 1;
				iEnd = oPaging.iTotalPages;
			} else {
				iStart = oPaging.iPage - iHalf + 1;
				iEnd = iStart + iListLength - 1;
			}

			for ( i=0, ien=an.length ; i<ien ; i++ ) {
				// Remove the middle elements
				$('li:gt(0)', an[i]).filter(':not(:last)').remove();

				// Add the new list items and their event handlers
				for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
					$('<li '+sClass+'><a href="#">'+j+'</a></li>')
						.insertBefore( $('li:last', an[i])[0] )
						.bind('click', function (e) {
							e.preventDefault();
							oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
							fnDraw( oSettings );
						} );
				}

				// Add / remove disabled classes from the static elements
				if ( oPaging.iPage === 0 ) {
					$('li:first', an[i]).addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
				}

				if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
					$('li:last', an[i]).addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
				}
			}
		}
	}
} );


/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */

	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, $.fn.DataTable.TableTools.classes, {
		"container": "DTTT ",
		"buttons": {
			"normal": "btn btn-white tip",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info "
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible dropdown
	$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );


function updateDataTableSelectAllCtrl(table){
   var $chkbox_all        = $(table).find('tbody input[type="checkbox"]');
   var $chkbox_checked    = $(table).find('tbody input[type="checkbox"]:checked');
   var chkbox_select_all  = $(table).find('thead input[name="select_all"]').get(0);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
   $(table).data("select-count", $(table).find('tbody input[type="checkbox"]:checked').length);
   $(table).trigger("select-count");
}

/* Table initialisation */
$(document).ready(function() {
    var breakpointDefinition = {
        tablet: 1024,
        phone : 480
    };    
	var rows_selected = [];
    var from_select_all = false;
    $('.checkable_datatable tbody input[type="checkbox"]').live("click", function(e){
		var $row = $(this).closest('tr');
		if( (this.checked && !from_select_all) || (!this.checked && from_select_all)){
			$row.addClass('row_selected');
		} else {
			$row.removeClass('row_selected');
		}
		var table = $(this).closest('table');

    	if( !from_select_all )
			updateDataTableSelectAllCtrl(table);
		e.stopPropagation();
	});

	$('.checkable_datatable').on('click', 'tbody td, thead th:first-child', function(e){
		if( !$(e.target.parentElement).is("a.playsound") ){
			if( !$(e.target).is('input[type="checkbox"]') ){
				$(this).parent().find('input[type="checkbox"]')[0].click();//.trigger('click');
			}
		}
	});

	$('.checkable_datatable thead input[name="select_all"]').on('click', function(e){
		from_select_all = true;
		var table = $(this).closest('table');
		if(this.checked){
			$(table).find('input[type="checkbox"]:not(:checked)').click();//.trigger('click');
		} else {
			$(table).find('input[type="checkbox"]:checked').click();//.trigger('click');
		}
		from_select_all = false;
		$(table).data("select-count", $(table).find('tbody input[type="checkbox"]:checked').length);
		$(table).trigger("select-count");
		e.stopPropagation();
	});


	$('.checkable_datatable').on('unselect_all', function(){
		var table = $(this);
		var chkbox_checked     = $(table).find('tbody input[type="checkbox"]:checked');
		var chkbox_select_all  = $(table).find('thead input[name="select_all"]').get(0);

		chkbox_checked.click();
		chkbox_select_all.checked = false;
        chkbox_select_all.indeterminate = false;
		$(table).find('.row_selected').removeClass('row_selected');

		$(table).data("select-count", $(table).find('tbody input[type="checkbox"]:checked').length);
		$(table).trigger("select-count");
	});

	$('.playsound').live("click", function(e){
		var type = $(this).data('type');
		var id   = $(this).data('sound-id');
		if( type == "track" ){
			playTrack(id);
		}
		else if( type == "playlist" ){
			playPlayList(id);
		}
		e.preventDefault();
		return false;
	})

});


/* Formating function for row details */
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="inner-table">';
    sOut += '<tr><td>Rendering engine:</td><td>'+aData[1]+' '+aData[4]+'</td></tr>';
    sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
    sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
    sOut += '</table>';
     
    return sOut;
}

function checkboxFormat(x) {
	return '<input type="checkbox" id="'+x+'" />';
}

function playSoundFormat(x, y, z) {
	return '<a class="playsound" href="#" data-type="'+x+'" data-sound-id="'+z[0]+'" ><i class=" icon-volume-up"></i></a>'
}

function fileSizeFormat(bytes) {
	var sizes = ['KB', 'MB', 'GB', 'TB'];
	if (bytes == 0) 
		return '0 ' + sizes[0];
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    if (i == 0) 
    	return bytes + ' ' + sizes[i];
    return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
}

function timeFormat(x) {
    var sec_num = parseInt(x, 10);
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes+':'+seconds;
}

function dateFormat(x) {
	return x;
}