<?php
helper('form');


?>

<script type="text/javascript">
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

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#site_configuration-edit-form').submit();

		});

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
		<i class="fa fa-gear"></i> Site Configuration
		<small>Change site configurations</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/site_configurations/edit'); ?>">Site Configuration</a></li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open_multipart('', array('id' => 'site_configuration-edit-form', 'class' => 'site_configuration-edit-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<a href="<?php echo site_url('administrator/dashboard'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<div class="box-body">

					<!-- Nav tabs -->
					<div class="data-tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#site_details" aria-controls="site_details" role="tab" data-toggle="tab">Site Details</a></li>
							<li role="presentation" class=""><a href="#site_settings" aria-controls="site_settings" role="tab" data-toggle="tab">Site Settings</a></li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">

							<!-- SITE  DETAILS	[START]		-->
							<div role="tabpanel" class="tab-pane active" id="site_details">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Site Title', 'site_title'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_title') . '</span>'; ?>

											<?php echo form_input('site_title', $site_configuration['site_title'], 'class="form-control"'); ?>
										</div>
										<div class="form-group">
											<?php echo form_label('Site Description', 'site_description'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_description') . '</span>'; ?>

											<?php echo form_textarea('site_description', $site_configuration['site_description'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Site Title FR', 'site_title_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_title_fr') . '</span>'; ?>

											<?php echo form_input('site_title_fr', $site_configuration['site_title_fr'], 'class="form-control"'); ?>
										</div>
										<div class="form-group">
											<?php echo form_label('Site Description FR', 'site_description_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_description_fr') . '</span>'; ?>

											<?php echo form_textarea('site_description_fr', $site_configuration['site_description_fr'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('From Name', 'from_name'); ?>
											<?php echo '<span class="text-danger">' . session('errors.from_name') . '</span>'; ?>

											<?php echo form_input('from_name', $site_configuration['from_name'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('From Email', 'from_email'); ?>
											<?php echo '<span class="text-danger">' . session('errors.from_email') . '</span>'; ?>

											<?php echo form_input('from_email', $site_configuration['from_email'], 'class="form-control"'); ?>
										</div>
									</div>
								</div>

							</div>
							<!-- SITE  DETAILS	[END]		-->

							<!-- SITE  DETAILS	[START]		-->
							<div role="tabpanel" class="tab-pane" id="site_settings">
								<a href="javascript:void(0)" id="clear-cache-ajax" class="btn btn-lg btn-warning clear-cache-ajax pull-right"><i class="fa fa-bolt" aria-hidden="true"></i>&nbsp; Clear Cache</a>
								<div class="row clearfix">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Site Offline', 'site_offline'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_offline') . '</span>'; ?>

											<div class="radiogroup_codeignitor">
												<div class="btn-group" data-toggle="buttons">
													<label class="btn btn-default <?php if ($site_configuration['site_offline'] == 1) { ?>btn-danger active<?php } ?>">
														<?php echo form_radio(array('name' => 'site_offline', 'value' => '1', 'checked' => ('1' == $site_configuration['site_offline']) ? TRUE : FALSE, 'id' => 'site_offline_1')); ?> <?php echo form_label('Yes', 'site_offline_1', ["class" => "radio_label"]); ?>
													</label>
													<label class="btn btn-default <?php if ($site_configuration['site_offline'] == 0) { ?>btn-success active<?php } ?>">
														<?php echo form_radio(array('name' => 'site_offline', 'value' => '0', 'checked' => ('0' == $site_configuration['site_offline']) ? TRUE : FALSE, 'id' => 'site_offline_0')); ?> <?php echo form_label('No', 'site_offline_0', ["class" => "radio_label"]); ?>
													</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<?php echo form_label('Offline Image', 'site_offlineimage'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_offlineimage') . '</span>'; ?>

											<div class="row">
												<div class="col-lg-7">
													<div class="input-group">
														<?php $site_offlineimage = isset($site_configuration['site_offlineimage']) && !empty($site_configuration['site_offlineimage']) ? $site_configuration['site_offlineimage'] : ''; ?>
														<?php $upload_site_offlineimage =  site_url('assets/media/') . $site_offlineimage; ?>
														<input id="site_offlineimageID" value="<?php echo $site_offlineimage; ?>" name="site_offlineimage" type="text" class="form-control">
														<span class="input-group-btn">
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=site_offlineimageID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
														</span>
													</div><!-- /input-group -->
												</div>
												<div class="col-lg-5">
													<?php if ($site_offlineimage != '') { ?>
														<img src="<?php echo $upload_site_offlineimage; ?>" class="icon_div site_offlineimageID" width="50" />
													<?php } else { ?>
														<img src="" class="icon_div site_offlineimageID" width="50" />
													<?php } ?>
												</div>
											</div>
										</div>
										<div class="form-group">
											<?php echo form_label('Offline Message', 'site_offlinemessage'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_offlinemessage') . '</span>'; ?>

											<?php echo form_textarea('site_offlinemessage', $site_configuration['site_offlinemessage'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">

										<div class="form-group">
											<?php echo form_label('Site Cache', 'site_caching'); ?>
											<?php echo '<span class="text-danger">' . session('errors.site_caching') . '</span>'; ?>

											<div class="radiogroup_codeignitor">
												<div class="btn-group" data-toggle="buttons">
													<label class="btn btn-warning <?php if ($site_configuration['site_caching'] == 0) { ?>active<?php } ?>">
														<?php echo form_radio(array('name' => 'site_caching', 'value' => '0', 'checked' => ('0' == $site_configuration['site_caching']) ? TRUE : FALSE, 'id' => 'site_caching_0')); ?> <?php echo form_label('Off', 'site_caching_0', ["class" => "radio_label"]); ?>
													</label>
													<label class="btn btn-warning <?php if ($site_configuration['site_caching'] == 1) { ?>active<?php } ?>">
														<?php echo form_radio(array('name' => 'site_caching', 'value' => '1', 'checked' => ('1' == $site_configuration['site_caching']) ? TRUE : FALSE, 'id' => 'site_caching_1')); ?> <?php echo form_label('On', 'site_caching_1', ["class" => "radio_label"]); ?>
													</label>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<?php echo form_label('Site Cache Time - (in minutes)', 'site_caching_time'); ?>
													<?php echo '<span class="text-danger">' . session('errors.site_caching_time') . '</span>'; ?>

													<?php echo form_input('site_caching_time', $site_configuration['site_caching_time'], 'class="form-control"'); ?>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
							<!-- SITE  DETAILS	[END]		-->

						</div>

					</div>

				</div>
				<!-- /.box-body -->

				<?php echo form_hidden('task', ''); ?>

				<div class="box-footer">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<a href="<?php echo site_url('administrator/dashboard'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</section>