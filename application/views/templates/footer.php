    </div>
  </div>
 </div>
<!-- END CONTAINER -->

<div class="modal fade" id="player_modal" style="width: 420px;margin-left:-210px;"></div>
<!-- BEGIN CORE JS FRAMEWORK--> 
<script src="<?=base_url(); ?>assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script> 
<script src="<?=base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<script src="<?=base_url(); ?>assets/plugins/breakpoints.js" type="text/javascript"></script> 
<script src="<?=base_url(); ?>assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script> 
<!-- END CORE JS FRAMEWORK --> 
<!-- BEGIN PAGE LEVEL JS --> 	
<script src="<?=base_url(); ?>assets/plugins/jquery-slider/jquery.sidr.min.js" type="text/javascript"></script> 	
<script src="<?=base_url(); ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<script src="<?=base_url(); ?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>  
<script src="<?=base_url(); ?>assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<script src="<?=base_url(); ?>assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<script src="<?=base_url(); ?>assets/js/tabs_accordian.js" type="text/javascript"></script>
<script src="<?=base_url(); ?>assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script>

<script src="<?=base_url(); ?>assets/plugins/jquery-contextmenu/jquery.contextMenu.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

<?php if (isset($datatables) && $datatables): ?>
<script src="<?=base_url(); ?>assets/plugins/jquery-datatable/js/jquery.dataTables1.min.js" type="text/javascript"></script>
<script src="<?=base_url(); ?>assets/plugins/jquery-datatable/js/dataTables.colVis.js" type="text/javascript"></script>
<script src="<?=base_url(); ?>assets/plugins/jquery-datatable/extra/js/TableTools.min.js" type="text/javascript"></script>
<!-- <script type="text/javascript" src="<?=base_url(); ?>assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>assets/plugins/datatables-responsive/js/lodash.min.js"></script> -->
<script src="<?=base_url(); ?>assets/js/datatables.js" type="text/javascript"></script>
<?php endif ?>

<?php if (isset($calendar_plugin) && $calendar_plugin): ?>
<script src="<?=base_url(); ?>assets/plugins/fullcalendar/fullcalendar.min.js"></script>
<?php endif ?>

<?php if (isset($code_copy) && $code_copy): ?>
<script src="<?=base_url(); ?>assets/plugins/ZeroClipboard/ZeroClipboard.js"></script>
<script src="<?=base_url(); ?>assets/plugins/highlight.js/highlight.pack.js"></script>
<script>
hljs.initHighlightingOnLoad();
var zeroClipboard_btn = new ZeroClipboard( document.getElementById("copy-button") );
zeroClipboard_btn.on( "ready", function( readyEvent ) {
  zeroClipboard_btn.setText($('code.html').text());
  zeroClipboard_btn.on( "aftercopy", function( event ) {
    alert("<?php echo lang("copied") ?>" );
  } );
} );
</script>
<?php endif ?>

<?php if (isset($datepicker) && $datepicker): ?>
<script src="<?=base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?=base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
 <script src="<?=base_url(); ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<script src="<?=base_url(); ?>assets/plugins/moments/moment.min.js"></script>
<script src="<?=base_url(); ?>assets/plugins/moments/moment-timezone-with-data-2010-2020.min.js"></script>
<script type="text/javascript">
$.fn.datepicker.defaults.language = 'script';
$.fn.datepicker.dates['script'] = {
    days: globalLang['days_array'],
    daysShort: globalLang['days_short_array'],
    daysMin: globalLang['days_min_array'],
    months: globalLang['months_array'],
    monthsShort: globalLang['months_short_array'],
    today: globalLang['today'],
    clear: globalLang['clear'],
    rtl: globalLang['is_rtl']
};
</script>
<?php endif ?>

<!-- BEGIN CORE TEMPLATE JS --> 
<script src="<?=base_url(); ?>assets/js/core.js" type="text/javascript"></script> 
<script src="<?=base_url(); ?>assets/js/demo.js" type="text/javascript"></script> 
<!-- END CORE TEMPLATE JS --> 
</body>
