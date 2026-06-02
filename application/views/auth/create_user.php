<?php 
$meta['page_title'] = $this->lang->line("create_user_heading");
$this->load->view('templates/head', $meta);
$this->load->view('templates/header');
?>
<div class="span6">
      <div class="grid simple">
            <div class="grid-title no-border">
                  <h4><?php echo lang('create_user_heading');?></h4>
            </div>
            <div class="grid-body no-border">
                  <?php if (isset($message)): ?>
                  <div class="alert alert-error"><?php echo $message;?></div>
                  <?php endif ?>
<p><?php echo lang('create_user_subheading');?></p>

<?php echo form_open("auth/create_user", array('class' => 'form-login form-horizontal'));?>

  <div class="control-group">
    <label class="control-label required" for="first_name"><?php echo lang('create_user_fname_label');?></label>
    <div class="controls">
      <?php echo form_input($first_name);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label required" for="last_name"><?php echo lang('create_user_lname_label');?></label>
    <div class="controls">
      <?php echo form_input($last_name);?>
    </div>
  </div>

  <?php if ($identity_column!=='email'): ?>
    <div class="control-group">
      <label class="control-label required" for="identity"><?php echo lang('create_user_identity_label');?></label>
      <div class="controls">
        <?php echo form_input($identity);?>
      </div>
    </div>
  <?php endif ?>

  <div class="control-group">
    <label class="control-label" for="company"><?php echo lang('create_user_company_label');?></label>
    <div class="controls">
      <?php echo form_input($company);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label required" for="email"><?php echo lang('create_user_email_label');?></label>
    <div class="controls">
      <?php echo form_input($email);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="phone"><?php echo lang('create_user_phone_label');?></label>
    <div class="controls">
      <?php echo form_input($phone);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label required" for="password"><?php echo lang('create_user_password_label');?></label>
    <div class="controls">
      <?php echo form_input($password);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label required" for="password_confirm"><?php echo lang('create_user_password_confirm_label');?></label>
    <div class="controls">
      <?php echo form_input($password_confirm);?>
    </div>
  </div>

  <div class="form-actions">
    <div class="pull-right flip">
      <a href="<?php echo site_url("/auth") ?>" class="btn btn-white"><?php echo lang("cancel") ?></a>
      <?php echo form_submit('submit', lang('create_user_submit_btn'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
    </div>
  </div>
<?php echo form_close();?>
            </div>
      </div>
</div>
<?php $this->load->view('templates/footer', $meta);?>
