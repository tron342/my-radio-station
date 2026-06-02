<?php 
$meta['page_title'] = $this->lang->line("deactivate_heading");
$this->load->view('templates/head', $meta);
$this->load->view('templates/header');
?>
<div class="span2"></div>
<div class="span8">
      <div class="grid simple">
            <div class="grid-title no-border">
                  <h4><?php echo lang('deactivate_heading');?></h4>
            </div>
            <div class="grid-body no-border">

<p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>

<?php echo form_open("auth/deactivate/".$user->id);?>

  <?php echo form_hidden($csrf); ?>
  <?php echo form_hidden(array('id'=>$user->id)); ?>
  <?php echo form_hidden(array('confirm'=>"yes")); ?>

  <div class="form-actions">
    <div class="text-center">
      <a href="<?php echo site_url("/auth") ?>" class="btn btn-white btn-cons no-margin"
             ><?php echo lang("deactivate_confirm_n_label") ?></a>
      <?php echo form_submit('submit', lang('deactivate_confirm_y_label'), array('class' => 'btn btn-primary btn-cons no-margin'));?>
    </div>
  </div>
<?php echo form_close();?>
            </div>
      </div>
</div>
<?php $this->load->view('templates/footer', $meta);?>