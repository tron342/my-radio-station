<?php 
$meta['page_title'] = $this->lang->line("reset_password_heading");
$this->load->view('templates/head', $meta);
?>
<!-- BEGIN BODY -->
<body class="">
<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content" style="margin: 0;"> 
    <div class="content" style="margin-top: 80px;">
      <div class="span4"></div>
      <div class="span4">
        <div class="grid simple">
          <div class="grid-title no-border text-center">
            <h3><?php echo lang('reset_password_heading');?></h3>
          </div>
          <div class="grid-body no-border">
            <?php if (isset($message)): ?>
              <div class="alert alert-error"><?php echo $message;?></div>
            <?php endif ?>
            <?php echo form_open('auth/reset_password/' . $code, array('class' => 'form-login'));?>

              <div class="row-fluid">

                <div class="row-fluid">
                  <label for="new_password">
                	<?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?>
                  </label>
                  <div class="input-append primary">
                    <?php echo form_input($new_password);?>
                    <span class="add-on"><span class="arrow"></span><i class="icon-lock"></i> </span> 
                  </div>
                </div>

                <div class="row-fluid">
                  <label for="new_password_confirm">
                	<?php echo lang('reset_password_new_password_confirm_label');?>
                  </label>
                  <div class="input-append primary">
                    <?php echo form_input($new_password_confirm);?>
                    <span class="add-on"><span class="arrow"></span><i class="icon-lock"></i> </span> 
                  </div>
                </div>

              </div>

              <div class="form-actions">
                <div class="pull-right flip">
					<?php echo form_input($user_id);?>
					<?php echo form_hidden($csrf); ?>
                	<?php echo form_submit('submit', lang('reset_password_submit_btn'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
                </div>
              </div>
            <?php echo form_close();?>            
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
<?php $this->load->view('templates/footer'); ?>