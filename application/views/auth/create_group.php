<?php 
$meta['page_title'] = $this->lang->line("create_group_heading");
$this->load->view('templates/head', $meta);
$this->load->view('templates/header');
?>
<div class="span6">
      <div class="grid simple">
            <div class="grid-title no-border">
                  <h4><?php echo lang('create_group_heading');?></h4>
            </div>
            <div class="grid-body no-border">
                  <?php if (isset($message)): ?>
                  <div class="alert alert-error"><?php echo $message;?></div>
                  <?php endif ?>
<p><?php echo lang('create_group_subheading');?></p>

<?php echo form_open("auth/create_group", array('class' => 'form-login form-horizontal'));?>
  <div class="control-group">
    <label class="control-label required" for="group_name"><?php echo lang('create_group_name_label');?></label>
    <div class="controls">
      <?php echo form_input($group_name);?>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label required" for="description"><?php echo lang('create_group_desc_label');?></label>
    <div class="controls">
      <?php echo form_input($description);?>
    </div>
  </div>

  <div class="form-actions">
    <div class="pull-right flip">
      <a href="<?php echo site_url("/auth") ?>" class="btn btn-white"><?php echo lang("cancel") ?></a>
      <?php echo form_submit('submit', lang('create_group_submit_btn'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
    </div>
  </div>
<?php echo form_close();?>
            </div>
      </div>
</div>
<?php $this->load->view('templates/footer', $meta);?>