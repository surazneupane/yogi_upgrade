<?php

helper('form');

?>
<script>
	function responsive_filemanager_callback(field_id) {
		console.log(field_id);
		var url = jQuery('#' + field_id).val();
		/* ADD PANCHSOFT  [START] */
		if (url == '') {
			jQuery('.' + field_id).hide();
		} else {
			jQuery('.' + field_id).show();
		}
		jQuery('.' + field_id).attr('src', '<?php echo site_url('assets/media/'); ?>' + url);
		/* ADD PANCHSOFT  [END] */
	}
	jQuery(document).ready(function() {

		jQuery('#mediaModal').on('show.bs.modal', function(event) {
			var button = jQuery(event.relatedTarget) // Button that triggered the modal
			var srcUrl = button.data('src') // Extract info from data-* attributes
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = jQuery(this)
			modal.find('.modal-body iframe').attr('src', srcUrl);

		});
	});
</script>


<section class="content-header">
	<h1>
		Create User
		<!--<small>Create user</small>-->
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/users'); ?>">Users</a></li>
		<li class="active">Create User</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<?php echo form_open('', array('class' => '')); ?>
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php
								echo form_label('First name', 'first_name');
								echo '<span class="text-danger">' . session('errors.first_name') . '</span>';
								// echo form_error('first_name');
								echo form_input('first_name', set_value('first_name'), 'class="form-control"');
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label('Last name', 'last_name');
								echo '<span class="text-danger">' . session('errors.last_name') . '</span>';
								echo form_input('last_name', set_value('last_name'), 'class="form-control"');
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label('Company', 'company');
								echo '<span class="text-danger">' . session('errors.company') . '</span>';
								echo form_input('company', set_value('company'), 'class="form-control"');
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label('Phone', 'phone');
								echo '<span class="text-danger">' . session('errors.phone') . '</span>';
								echo form_input('phone', set_value('phone'), 'class="form-control"');
								?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php
								echo form_label('Username', 'username');
								echo '<span class="text-danger">' . session('errors.username') . '</span>';
								echo form_input('username', set_value('username'), 'class="form-control"');
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label('Email', 'email');
								echo '<span class="text-danger">' . session('errors.email') . '</span>';
								echo form_input('email', '', 'class="form-control"');
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label('Password', 'password');
								echo '<span class="text-danger">' . session('errors.password') . '</span>';
								echo form_password('password', '', 'class="form-control"');
								?>
							</div>
							<div class="form-group">
								<?php
								echo form_label('Confirm password', 'password_confirm');
								echo '<span class="text-danger">' . session('errors.password_confirm') . '</span>';
								echo form_password('password_confirm', '', 'class="form-control"');
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="form-group">
									<?php echo form_label('Description', 'description'); ?>
									<?php echo '<span class="text-danger">' . session('errors.description') . '</span>'; ?>
									<?php echo form_textarea('description', '', 'class="textarea"'); ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Upload Photo'); ?>
								<div class="row">
									<div class="col-lg-7">
										<div class="input-group">
											<input id="fieldImgID" class="form-control" value="" name="photo" type="text">
											<span class="input-group-btn">
												<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImgID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
											</span>
										</div><!-- /input-group -->
									</div>
									<div class="col-lg-5">
										<img src='' class='icon_div fieldImgID' width="50" />
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
								<?php echo   '<span class="text-danger">' . session('errors.website_link') . '</span>'; ?>
								<?php echo form_input('website_link', '', 'class="form-control"'); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<?php
						if (isset($groups)) {
							echo form_label('Groups', 'groups[]');
							foreach ($groups as $group) {
								echo '<div class="checkbox">';
								echo '<label>';
								echo form_checkbox('groups[]', $group->id, set_checkbox('groups[]', $group->id));
								echo ' ' . $group->name;
								echo '</label>';
								echo '</div>';
							}
						}
						?>
						<?php echo   '<span class="text-danger">' . session('errors.groups') . '</span>'; ?>
					</div>
				</div>

				<div class="box-footer">
					<?php echo form_submit('submit', 'Save', 'class="btn btn-success"'); ?>
					<a href="<?php echo site_url('administrator/users'); ?>" class="btn btn-danger">Cancel</a>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</section>