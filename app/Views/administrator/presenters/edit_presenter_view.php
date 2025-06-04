<?php

helper('form')

?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#presenter-edit-form').submit();

		});

	});
</script>

<section class="content-header">
	<h1>
		Edit Presenter
		<small>Edit video presenter</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/presenters'); ?>">Presenters</a></li>
		<li class="active">Edit Presenter</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open('', array('id' => 'presenter-edit-form', 'class' => 'presenter-edit-form')); ?>

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
								<?php echo form_input('name', $presenter['name'], 'class="form-control"'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Alias', 'alias'); ?>
								<?php echo '<span class="text-danger">' . session('errors.alias') . '</span>'; ?>

								<?php echo form_input('alias', $presenter['alias'], 'class="form-control"'); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Description', 'description'); ?>
								<?php echo '<span class="text-danger">' . session('errors.description') . '</span>'; ?>

								<?php echo form_textarea('description', $presenter['description'], 'class="textarea"'); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Image', 'image'); ?>
								<?php echo '<span class="text-danger">' . session('errors.image') . '</span>'; ?>

								<div class="row">
									<div class="col-lg-9">
										<div class="input-group">

											<?php
											if ($presenter['image'] != '') {
												$presenter_image = "<img src='" . site_url('assets/media/') . $presenter['image'] . "' class='img-responsive' />";
											} else {
												$presenter_image = 'No image selected.';
											}
											?>

											<span class="input-group-btn">
												<a href="javascript:void(0)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" title="Selected Image" data-content="<?php echo $presenter_image; ?>" data-content="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'>Selected Image</h3><div class='popover-content'><?php echo $presenter_image; ?></div></div>" class="btn btn-default"><i class="fa fa-eye"></i></a>
											</span>

											<?php echo form_input('image', $presenter['image'], 'id="fieldImgID" class="form-control"'); ?>
											<span class="input-group-btn">
												<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImgID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
											</span>
										</div><!-- /input-group -->
									</div>
									<?php if ($presenter['image'] != '') { ?>
										<div class="col-lg-3">
											<img src='<?php echo site_url('assets/media/') . $presenter['image']; ?>' class='icon_div fieldImgID' width="50" />
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<?php echo form_label('Facebook URL', 'facebook_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.facebook_link') . '</span>'; ?>

								<?php echo form_input('facebook_link', $presenter['facebook_link'], 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Twitter URL', 'twitter_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.twitter_link') . '</span>'; ?>

								<?php echo form_input('twitter_link', $presenter['twitter_link'], 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Instagram URL', 'instagram_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.instagram_link') . '</span>'; ?>

								<?php echo form_input('instagram_link', $presenter['instagram_link'], 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Website URL', 'website_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.website_link') . '</span>'; ?>

								<?php echo form_input('website_link', $presenter['website_link'], 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Status', 'status'); ?>
								<?php echo '<span class="text-danger">' . session('errors.status') . '</span>'; ?>

								<?php
								$options = array();
								$options[1] = 'Publish';
								$options[0] = 'Unpublish';
								echo form_dropdown('status', $options, $presenter['status'], 'class="form-control"');
								?>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Created Date', 'created_date'); ?>
								<?php echo '<span class="text-danger">' . session('errors.created_date') . '</span>'; ?>

								<?php echo form_input('created_date', $presenter['created_date'], 'class="form-control readonly" readonly="readonly"'); ?>
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
								echo form_dropdown('created_by', $options, $presenter['created_by'], 'class="form-control"');
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->

				<?php echo form_hidden('presenter_id', set_value('presenter_id', $presenter['id'])); ?>
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