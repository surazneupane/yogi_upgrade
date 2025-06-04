<?php

helper('form');

?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#presenter-create-form').submit();

		});

	});
</script>


<section class="content-header">
	<h1>
		Create Presenter
		<small>Add video presenter</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/presenters'); ?>">Presenters</a></li>
		<li class="active">Create presenter</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open('', array('id' => 'presenter-create-form', 'class' => 'presenter-create-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"'); ?>
						<a href="<?php echo site_url('administrator/presenters'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<div class="box-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Name', 'name'); ?>
								<?php echo '<span class="text-danger">' . session('errors.name') . '</span>'; ?>
								<?php echo form_input('name', '', 'class="form-control"'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Alias', 'alias'); ?>
								<?php echo '<span class="text-danger">' . session('errors.alias') . '</span>'; ?>
								<?php echo form_input('alias', '', 'class="form-control"'); ?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Description', 'description'); ?>
								<?php echo '<span class="text-danger">' . session('errors.description') . '</span>'; ?>
								<?php echo form_textarea('description', '', 'class="textarea"'); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Image', 'image'); ?>
								<?php echo '<span class="text-danger">' . session('errors.image') . '</span>'; ?>
								<div class="row">
									<div class="col-lg-12">
										<div class="input-group">
											<?php echo form_input('image', 'avatars/avatar1.png', 'id="fieldImgID" class="form-control"'); ?>
											<span class="input-group-btn">
												<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImgID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
											</span>
										</div><!-- /input-group -->
									</div>
								</div>
							</div>
							<div class="form-group">
								<?php echo form_label('Facebook URL', 'facebook_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.facebook_link') . '</span>'; ?>
								<?php echo form_input('facebook_link', '', 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Twitter URL', 'twitter_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.twitter_link') . '</span>'; ?>
								<?php echo form_input('twitter_link', '', 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Instagram URL', 'instagram_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.instagram_link') . '</span>'; ?>
								<?php echo form_input('instagram_link', '', 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Website URL', 'website_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.website_link') . '</span>'; ?>
								<?php echo form_input('website_link', '', 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Status', 'status'); ?>
								<?php echo '<span class="text-danger">' . session('errors.status') . '</span>'; ?>
								<?php
								$options = array();
								$options[1] = 'Publish';
								$options[0] = 'Unpublish';
								echo form_dropdown('status', $options, '', 'class="form-control"');
								?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">

								<?php echo form_label('Created Date', 'created_date'); ?>
								<div class="input-group date datetimepicker-control">
									<?php echo form_input('created_date', date('Y-m-d H:i:s'), 'class="form-control pull-right"'); ?>
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
								<?php echo '<span class="text-danger">' . session('errors.created_date') . '</span>'; ?>

							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Created By', 'created_by'); ?>
								<?php echo '<span class="text-danger">' . session('errors.created_by') . '</span>'; ?>
								<?php
								$options = array();
								foreach ($userlist as $user) {
									$options[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
								}

								echo form_dropdown('created_by', $options, $current_user->id, 'class="form-control"');
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->

				<?php echo form_hidden('task', ''); ?>

				<div class="box-footer">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"'); ?>
						<a href="<?php echo site_url('administrator/presenters'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>
</section>