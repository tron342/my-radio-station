<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>
<?php
	echo lang("site_title_head");
	echo isset($page_title)?" - ".$page_title:"";
?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="Radio station" name="description" />
<meta content="bessemzitouni" name="author" />
<link rel="SHORTCUT ICON" href="<?=base_url(); ?>assets/img/favicon.png"/>


<link href="<?=base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url(); ?>assets/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen" />
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?=base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css" />
<!-- END CORE CSS FRAMEWORK -->

<!-- BEGIN CSS TEMPLATE -->
<link href="<?=base_url(); ?>assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css" />
<!-- END CSS TEMPLATE -->

<?php if (isset($datatables) && $datatables): ?>
<link href="<?=base_url(); ?>assets/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/plugins/jquery-datatable/css/dataTables.colVis.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/plugins/boostrap-checkbox/css/bootstrap-checkbox.css" rel="stylesheet" type="text/css" media="screen" />
<!-- <link href="<?=base_url(); ?>assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" /> -->
<?php endif ?>

<?php if (isset($calendar_plugin) && $calendar_plugin): ?>
<link href="<?=base_url(); ?>assets/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" media="screen" />
<?php endif ?>


<?php if (isset($datepicker) && $datepicker): ?>
<link href="<?=base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url(); ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
<?php endif ?>

<?php if (isset($code_copy) && $code_copy): ?>
<link rel="stylesheet" href="<?=base_url(); ?>assets/plugins/highlight.js/styles/github.css">
<?php endif ?>

<?php if (lang("is_rtl")): ?>
	<link href="<?=base_url(); ?>assets/css/rtl.css" rel="stylesheet" type="text/css" />
<?php endif ?>
<script src="<?=base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var globalLang = <?php echo json_encode($this->lang->language); ?>;
	var SITE_URL = "<?php echo site_url('')?>";
    var BASE_URL = "<?php echo base_url('')?>";
    var CSRF_HASH = "<?php echo $this->security->get_csrf_hash(); ?>";
</script>
</head>
<!-- END HEAD -->
