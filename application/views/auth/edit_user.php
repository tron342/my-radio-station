<?php 
$meta['page_title'] = $this->lang->line("edit_user_heading");
$this->load->view('templates/head', $meta);
$this->load->view('templates/header');
?>
<div class="span6">
      <div class="grid simple">
            <div class="grid-title no-border">
                  <h4><?php echo lang('edit_user_heading');?></h4>
            </div>
            <div class="grid-body no-border">
                  <?php if (isset($message)): ?>
                  <div class="alert alert-error"><?php echo $message;?></div>
                  <?php endif ?>
<p><?php echo lang('edit_user_subheading');?></p>

<?php echo form_open(uri_string(), array('class' => 'form-login form-horizontal'));?>

  <div class="control-group">
    <label class="control-label required" for="first_name"><?php echo lang('edit_user_fname_label');?></label>
    <div class="controls">
      <?php echo form_input($first_name);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label required" for="last_name"><?php echo lang('edit_user_lname_label');?></label>
    <div class="controls">
      <?php echo form_input($last_name);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="company"><?php echo lang('edit_user_company_label');?></label>
    <div class="controls">
      <?php echo form_input($company);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="phone"><?php echo lang('edit_user_phone_label');?></label>
    <div class="controls">
      <?php echo form_input($phone);?>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label required" for="password"><?php echo lang('edit_user_password_label');?></label>
    <div class="controls">
      <?php echo form_input($password);?>
    </div>
    <span class="help pull-right flip"><?php echo lang('edit_user_password_help');?></span>
  </div>

  <div class="control-group">
    <label class="control-label required" for="password_confirm"><?php echo lang('edit_user_password_confirm_label');?></label>
    <div class="controls">
      <?php echo form_input($password_confirm);?>
    </div>
    <span class="help pull-right flip"><?php echo lang('edit_user_password_help');?></span>
  </div>

  <?php if ($this->ion_auth->is_admin()): ?>
    <div class="control-group">
      <label class="control-label"><?php echo lang('edit_user_groups_heading');?></label>
      <div class="controls">
        <?php foreach ($groups as $group):?>
          <?php
              $gID=$group['id'];
              $checked = null;
              $item = null;
              foreach($currentGroups as $grp) {
                  if ($gID == $grp->id) {
                      $checked= ' checked="checked"';
                  break;
                  }
              }
          ?>
          <div class="checkbox check-default">
            <input id="<?php echo $group['name'] ?>" type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
            <label for="<?php echo $group['name'] ?>"><?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?></label>
          </div>
        <?php endforeach?>
      </div>
    </div>
  <?php endif ?>

      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>

  <div class="form-actions">
    <div class="pull-right flip">
      <a href="<?php echo site_url("/auth") ?>" class="btn btn-white"><?php echo lang("cancel") ?></a>
      <?php echo form_submit('submit', lang('edit_user_submit_btn'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
    </div>
  </div>
<?php echo form_close();?>
            </div>
      </div>
</div>
<?php $this->load->view('templates/footer', $meta);?>
