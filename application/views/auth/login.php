<?php 
$meta['page_title'] = $this->lang->line("login_heading");
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
            <h3><?php echo lang('login_heading');?> <span class="semi-bold"><?php echo lang('here');?></span></h3>
          </div>
          <div class="grid-body no-border">
            <p><?php echo lang('login_subheading');?></p>
            <?php if (isset($message)): ?>
            <div class="alert alert-error"><?php echo $message;?></div>
            <?php endif ?>
            <?php echo form_open("auth/login", array('class' => 'form-login'));?>
            <div class="row-fluid">
              <div class="row-fluid">
                <label for="identity"><?php echo lang('login_identity_label');?></label>
                <div class="input-append primary">
                  <?php echo form_input($identity);?>
                  <span class="add-on"><span class="arrow"></span><i class="icon-align-justify"></i> </span>
                </div>
              </div>
              <div class="row-fluid">
                <label for="password"><?php echo lang('login_password_label');?></label>
                <div class="input-append primary">
                  <?php echo form_input($password);?>
                  <span class="add-on"><span class="arrow"></span><i class="icon-lock"></i> </span>
                </div>
              </div>
            </div>
            <div class="form-actions">
              <div class="pull-left flip">
                <label for="remember">
                  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                  <?php echo lang('login_remember_label');?>
                </label>
              </div>
              <div class="pull-right flip">
                <?php echo form_submit('submit', lang('login_submit_btn'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
              </div>
              <a href="forgot_password" class="btn btn-block">
                <?php echo lang('login_forgot_password');?>
              </a>
            </div>
            <?php echo form_close();?>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
<?php $this->load->view('templates/footer'); ?>