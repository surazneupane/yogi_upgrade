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
		jQuery('.' + field_id).attr('src', '<?php echo site_url('assets/administrator/'); ?>source/' + url);
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
		Profile
		<!--<small>Edit user</small>-->
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/users'); ?>">Users</a></li>
		<li class="active">Edit User</li>
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
								<?php echo form_label('First name', 'first_name'); ?>
								<span class="text-danger"><?= session('errors.first_name') ?? '' ?></span>
								<?php echo form_input('first_name', set_value('first_name', $user->first_name), 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Last name', 'last_name'); ?>
								<span class="text-danger"><?= session('errors.last_name') ?? '' ?></span>
								<?php echo form_input('last_name', set_value('last_name', $user->last_name), 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Company', 'company'); ?>
								<span class="text-danger"><?= session('errors.company') ?? '' ?></span>
								<?php echo form_input('company', set_value('company', $user->company), 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Phone', 'phone'); ?>
								<span class="text-danger"><?= session('errors.phone') ?? '' ?></span>
								<?php echo form_input('phone', set_value('phone', $user->phone), 'class="form-control"'); ?>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Username', 'username'); ?>
								<span class="text-danger"><?= session('errors.username') ?? '' ?></span>
								<?php echo form_input('username', set_value('username', $user->username), 'class="form-control" readonly'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Email', 'email'); ?>
								<span class="text-danger"><?= session('errors.email') ?? '' ?></span>
								<?php echo form_input('email', set_value('email', $user->email), 'class="form-control" readonly'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Change password', 'password'); ?>
								<span class="text-danger"><?= session('errors.password') ?? '' ?></span>
								<?php echo form_password('password', '', 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Confirm changed password', 'password_confirm'); ?>
								<span class="text-danger"><?= session('errors.password_confirm') ?? '' ?></span>
								<?php echo form_password('password_confirm', '', 'class="form-control"'); ?>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Description', 'description'); ?>
								<span class="text-danger"><?= session('errors.description') ?? '' ?></span>
								<?php echo form_textarea('description', $user->description, 'class="textarea"'); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Upload Photo'); ?>
								<div class="row">
									<div class="col-lg-7">
										<div class="input-group">
											<?php $photo = isset($user->photo) && !empty($user->photo) ? $user->photo : ''; ?>
											<?php
											$upload_photo =  site_url('assets/administrator/') . 'source/' . $photo;
											?>
											<input id="fieldImgID" value="<?php echo $photo; ?>" name="photo" type="text" class="form-control">
											<span class="input-group-btn">
												<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImgID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
											</span>
										</div><!-- /input-group -->
									</div>
									<div class="col-lg-5">
										<?php if ($photo != '') { ?>
											<img src="<?php echo $upload_photo; ?>" class="icon_div fieldImgID" width="50" />
										<?php } else { ?>
											<img src="" class="icon_div fieldImgID" width="50" />
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<?php echo form_label('Facebook URL', 'facebook_link'); ?>
								<span class="text-danger"><?= session('errors.facebook_link') ?? '' ?></span>
								<?php echo form_input('facebook_link', set_value('facebook_link', $user->facebook_link), 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Twitter URL', 'twitter_link'); ?>
								<span class="text-danger"><?= session('errors.twitter_link') ?? '' ?></span>
								<?php echo form_input('twitter_link', set_value('twitter_link', $user->twitter_link), 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Instagram URL', 'instagram_link'); ?>
								<span class="text-danger"><?= session('errors.instagram_link') ?? '' ?></span>
								<?php echo form_input('instagram_link', set_value('instagram_link', $user->instagram_link), 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('Website URL', 'website_link'); ?>
								<span class="text-danger"><?= session('errors.website_link') ?? '' ?></span>
								<?php echo form_input('website_link', set_value('website_link', $user->website_link), 'class="form-control"'); ?>
							</div>

						</div>
					</div>
				</div>
				<div class="box-footer">
					<?php echo form_submit('submit', 'Save', 'class="btn btn-success"'); ?>
					<a href="<?php echo site_url('administrator'); ?>" class="btn btn-danger">Cancel</a>
				</div>
				<?php echo form_close(); ?>

			</div>
		</div>
	</div>


</section>