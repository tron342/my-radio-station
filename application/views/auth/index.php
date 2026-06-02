<?php 
$meta['page_title'] = $this->lang->line("index_heading");
$this->load->view('templates/head', $meta);
$this->load->view('templates/header');
?>
<div class="span12">
    <div class="grid simple">
        <div class="grid-title no-border">
              <h4><?php echo lang('index_heading');?></h4>
        </div>
        <div class="grid-body no-border">
              <?php if (isset($message)): ?>
              <div class="alert alert-success"><button class="close" data-dismiss="alert"></button> <?php echo $message;?></div>
              <?php endif ?>

			<p class="pull-right flip">
				<a href="<?php echo site_url("auth/create_user") ?>" class="btn btn-primary btn-small"><i class="icon-user"></i> <?php echo lang('index_create_user_link') ?></a>
				<a href="<?php echo site_url("auth/create_group") ?>" class="btn btn-primary btn-small"><i class="icon-group"></i> <?php echo lang('index_create_group_link') ?></a>
			</p>
			<p><?php echo lang('index_subheading');?></p>

			<table class="table table-hover table-condensed minheight300" width="100%">
				<thead>
				<tr>
					<th style="min-width: 160px;"><?php echo lang('index_username_th');?></th>
					<th style="min-width: 160px;"><?php echo lang('index_name_th');?></th>
					<th style="min-width: 170px; width: 100%;"><?php echo lang('index_email_th');?></th>
					<th style="min-width: 180px;"><?php echo lang('index_groups_th');?></th>
					<th style="min-width: 100px;"><?php echo lang('index_status_th');?></th>
					<th style="min-width: 100px;"><?php echo lang('index_action_th');?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($users as $user):?>
					<tr>
			            <td><?php echo htmlspecialchars($user->username,ENT_QUOTES,'UTF-8');?></td>
			            <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8')." ".htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
			            <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
						<td>
							<?php foreach ($user->groups as $group):?>
								<span class="label"><?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?></span>
			                <?php endforeach?>
						</td>
						<td>
							<?php if ($user->active): ?>
								<?php echo lang('index_active_status') ?>
							<?php else: ?>
								<?php echo lang('index_inactive_status') ?>
							<?php endif ?>
						</td>
						<td>
							<?php if ($user->active): ?>
								<a href="<?php echo site_url("/auth/deactivate/".$user->id) ?>" class="btn btn-danger btn-small tip" title="<?php echo lang('index_inactive_link') ?>"><i class="icon-ban-circle"></i></a>
							<?php else: ?>
								<a href="<?php echo site_url("/auth/activate/".$user->id) ?>" class="btn btn-primary btn-small tip" title="<?php echo lang('index_active_link') ?>"><i class="icon-ok"></i></a>
							<?php endif ?>
							<a href="<?php echo site_url("/auth/edit_user/".$user->id) ?>" class="btn btn-white btn-small tip" title="<?php echo lang('edit') ?>"><i class="icon-edit"></i></a>
						</td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
        </div>
	</div>
</div>
<?php $this->load->view('templates/footer', $meta);?>