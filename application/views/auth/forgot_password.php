<?php 
$meta['page_title'] = $this->lang->line("forgot_password_heading");
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
            <h3><?php echo lang('forgot_password_heading');?></h3>
          </div>
          <div class="grid-body no-border">
            <p><?php echo lang('forgot_password_subheading');?></p>
            <?php if (isset($message)): ?>
              <div class="alert alert-error"><?php echo $message;?></div>
            <?php endif ?>
            <?php echo form_open("auth/forgot_password", array('class' => 'form-login'));?>
              <div class="row-fluid">
                <div class="row-fluid">
                  <label for="identity">
                  	<?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?>
                  </label>
                  <div class="input-append primary">
                    <?php echo form_input($identity);?>
                    <span class="add-on"><span class="arrow"></span><i class="icon-align-justify"></i> </span> 
                  </div>
                </div>
              </div>
              <div class="form-actions">
                <div class="pull-right flip">
                  <?php echo form_submit('submit', lang('forgot_password_submit_btn'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
                </div>
              </div>
            <?php echo form_close();?>            
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
<?php $this->load->view('templates/footer'); ?>

