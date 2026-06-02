<?php 
$meta['page_title'] = $this->lang->line("change_password_heading");
$this->load->view('templates/head', $meta);
$this->load->view('templates/header');
?>

<div class="span6">
      <div class="grid simple">
            <div class="grid-title no-border">
                  <h4><?php echo lang('change_password_heading');?></h4>
            </div>
            <div class="grid-body no-border">
                  <?php if (isset($message)): ?>
                  <div class="alert alert-error"><?php echo $message;?></div>
                  <?php endif ?>
                  <?php echo form_open("auth/change_password", array('class' => 'form-login'));?>
                        <div class="row-fluid">
                          <div class="row-fluid">
                            <label for="old_password"><?php echo lang('change_password_old_password_label');?></label>
                            <div class="input-append primary">
                              <?php echo form_input($old_password);?>
                              <span class="add-on"><span class="arrow"></span><i class="icon-lock"></i> </span>
                            </div>
                          </div>
                          <div class="row-fluid">
                            <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label>
                            <div class="input-append primary">
                              <?php echo form_input($new_password);?>
                              <span class="add-on"><span class="arrow"></span><i class="icon-lock"></i> </span>
                            </div>
                          </div>
                          <div class="row-fluid">
                            <label for="new_password_confirm"><?php echo lang('change_password_new_password_confirm_label');?></label>
                            <div class="input-append primary">
                              <?php echo form_input($new_password_confirm);?>
                              <span class="add-on"><span class="arrow"></span><i class="icon-lock"></i> </span>
                            </div>
                          </div>
                        </div>
                        <?php echo form_input($user_id);?>
                        <div class="form-actions">
                          <div class="pull-right">
                            <?php echo form_submit('submit', lang('change_password_submit_btn'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
                          </div>
                        </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>
<?php $this->load->view('templates/footer', $meta);?>