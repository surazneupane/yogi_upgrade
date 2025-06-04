<?php

helper('form');

$homepage_slide = json_decode($home_configuration['slider_data']);
$homepage_slide_cnt = count((array)$homepage_slide);

?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#home_configuration-edit-form').submit();

		});



		// Media Images ADD MORE [START]

		var slider_max_fields = 10; //maximum input boxes allowed
		var slider_images_list_wrapper = jQuery("#slider_images_list-wrapper"); //Fields wrapper
		var slider_images_add_button = jQuery("#add_slider_image"); //Add button ID
		var x = <?php echo $homepage_slide_cnt; ?>; //initlal text box count
		jQuery(slider_images_add_button).click(function(e) {
			e.preventDefault();
			if (x < slider_max_fields) { //max input box allowed
				x++; //text box increment

				var slider_images_list_html = '<div class="slide-block">';
				slider_images_list_html += '<div class="row">';

				slider_images_list_html += '<div class="col-md-3 form-group">';
				slider_images_list_html += '<div class="input-group">';
				slider_images_list_html += '<!-- drag handle -->';
				slider_images_list_html += '<span class="input-group-addon input-group-move">';
				slider_images_list_html += '<span class="handle">';
				slider_images_list_html += '<i class="fa fa-arrows"></i>';
				slider_images_list_html += '</span>';
				slider_images_list_html += '</span>';
				slider_images_list_html += '<input name="homepage_slide[' + x + '][image]" value="" id="homepage_slide_' + x + '_image" class="form-control" type="text">';
				slider_images_list_html += '<span class="input-group-btn">';
				slider_images_list_html += '<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=homepage_slide_' + x + '_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>';
				slider_images_list_html += '</span>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-4 form-group">';
				slider_images_list_html += '<input name="homepage_slide[' + x + '][title]" id="homepage_slide_' + x + '_title" value="" class="form-control" placeholder="Title" type="text" />';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-4 form-group">';
				slider_images_list_html += '<input name="homepage_slide[' + x + '][link]" id="homepage_slide_' + x + '_link" value="" class="form-control" placeholder="Link" type="text" />';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-1 form-group">';
				slider_images_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-3">';
				slider_images_list_html += '<label for="homepage_slide_' + x + '_on_date">On Date</label>';
				slider_images_list_html += '<div class="input-group date datetimepicker-control datetimepicker_start_date-control">';
				slider_images_list_html += '<input name="homepage_slide[' + x + '][on_date]" value="<?php echo date('Y-m-d H:i:s'); ?>" id="homepage_slide_' + x + '_on_date" class="form-control pull-right" type="text">';
				slider_images_list_html += '<div class="input-group-addon">';
				slider_images_list_html += '<i class="fa fa-calendar"></i>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-3">';
				slider_images_list_html += '<label for="homepage_slide_' + x + '_off_date">On Date</label>';
				slider_images_list_html += '<div class="input-group date datetimepicker-control datetimepicker_off_date-control">';
				slider_images_list_html += '<input name="homepage_slide[' + x + '][off_date]" value="<?php echo date('Y-m-d H:i:s', strtotime("+5 days")); ?>" id="homepage_slide_' + x + '_off_date" class="form-control pull-right" type="text">';
				slider_images_list_html += '<div class="input-group-addon">';
				slider_images_list_html += '<i class="fa fa-calendar"></i>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';

				slider_images_list_html += '</div>';
				slider_images_list_html += '<hr/>';
				slider_images_list_html += '</div>';
				jQuery(slider_images_list_wrapper).append(slider_images_list_html); //add input box

				jQuery('.datetimepicker-control').datetimepicker({
					format: 'YYYY-MM-DD HH:mm:ss'
				});

			}
		});

		jQuery(slider_images_list_wrapper).on("click", ".remove_field", function(e) { //user click on remove text
			e.preventDefault();
			jQuery(this).parent().parent('div').parent('div').remove();
			x--;
		});

		// Media Images ADD MORE [END]


	});
</script>

<section class="content-header">
	<h1>
		<i class="fa fa-gear"></i> Home Configuration
		<small>Change home configurations</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/home_configurations/edit'); ?>">Home Configuration</a></li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open_multipart('', array('id' => 'home_configuration-edit-form', 'class' => 'home_configuration-edit-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<a href="<?php echo site_url('administrator/dashboard'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<div class="box-body data-tabs">

					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">EN Details</a></li>
						<li role="presentation"><a href="#details_fr" aria-controls="details_fr" role="tab" data-toggle="tab">FR Details</a></li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">

						<!--  DETAILS	[START]		-->
						<div role="tabpanel" class="tab-pane active" id="details">

							<div class="form-group">
								<?php echo form_label('Section 1', 'section_1_data'); ?>
								<?php echo '<span class="text-danger">' . session('errors.section_1_data') . '</span>'; ?>
								<?php echo form_textarea('section_1_data', $home_configuration['section_1_data'], 'class="textarea"'); ?>
							</div>


							<div class="form-group">
								<?php echo form_label('Section 2', 'section_2_data'); ?>
								<?php echo '<span class="text-danger">' . session('errors.section_2_data') . '</span>'; ?>

								<?php echo form_textarea('section_2_data', $home_configuration['section_2_data'], 'class="textarea"'); ?>
							</div>


							<div class="form-group">
								<?php echo form_label('Section 3', 'section_3_data'); ?>
								<?php echo '<span class="text-danger">' . session('errors.section_3_data') . '</span>'; ?>

								<?php echo form_textarea('section_3_data', $home_configuration['section_3_data'], 'class="textarea"'); ?>
							</div>

						</div>
						<!--  DETAILS	[START]		-->
						<div role="tabpanel" class="tab-pane" id="details_fr">

							<div class="form-group">
								<?php echo form_label('FR Section 1', 'section_1_data_fr'); ?>
								<?php echo '<span class="text-danger">' . session('errors.section_1_data_fr') . '</span>'; ?>

								<?php echo form_textarea('section_1_data_fr', $home_configuration['section_1_data_fr'], 'class="textarea"'); ?>
							</div>


							<div class="form-group">
								<?php echo form_label('FR Section 2', 'section_2_data_fr'); ?>
								<?php echo '<span class="text-danger">' . session('errors.section_2_data_fr') . '</span>'; ?>

								<?php echo form_textarea('section_2_data_fr', $home_configuration['section_2_data_fr'], 'class="textarea"'); ?>
							</div>


							<div class="form-group">
								<?php echo form_label('FR Section 3', 'section_3_data_fr'); ?>
								<?php echo '<span class="text-danger">' . session('errors.section_3_data_fr') . '</span>'; ?>

								<?php echo form_textarea('section_3_data_fr', $home_configuration['section_3_data_fr'], 'class="textarea"'); ?>
							</div>

						</div>

					</div>

					<!--<hr/>-->

					<div class="homepage-slider-wrapper hidden">

						<div id="homepage-slider_images-field" class="form-group homepage-slider_images-field">
							<?php echo form_label('Homepage Slider Images', 'slider_images'); ?>
							<?php echo '<span class="text-danger">' . session('errors.slider_images') . '</span>'; ?>


							<div class="slider_images_action">
								<p><button type="button" id="add_slider_image" class="btn bg-orange btn-add_slider_image"><i class="fa fa-plus"></i>&nbsp; Add</button></p>
								<p class="text-danger text-bold">Note : Off Date must be greater than On Date.</p>
							</div>

							<hr />
							<div id="slider_images_list-wrapper" class="slider_images_list-wrapper todo-list">
								<?php if (!empty($homepage_slide)) { ?>
									<?php $i = 1;
									foreach ($homepage_slide as $slide) { ?>
										<?php
										$slide_class = "";
										$common_class = "";
										$currentdate = date('Y-m-d H:i:s');
										if (strtotime($currentdate) >= strtotime($slide->off_date)) {
											$slide_class = 'bg-danger';
											$common_class = 'xslide-class';
										} elseif (strtotime($currentdate) <= strtotime($slide->on_date)) {
											$slide_class = 'bg-warning';
											$common_class = 'xslide-class';
										}
										?>
										<div class="slide-block">
											<div class="row <?php echo $common_class; ?> <?php echo $slide_class; ?>">
												<?php if ($slide_class == 'bg-danger') { ?>
													<div class="col-md-12">
														<p class="text-danger">This slide is expired. Please remove it or active it again by change Off Date.</p>
													</div>
												<?php } elseif ($slide_class == 'bg-warning') { ?>
													<div class="col-md-12">
														<p class="text-warning">This slide is scheduled. When On Date is come, slide will be automatically visible on site.</p>
													</div>
												<?php } ?>
												<div class="col-md-3 form-group">
													<div class="input-group">
														<?php
														if ($slide->image != '') {
															$slide_image = "<img src='" . site_url('assets/media/') . $slide->image . "' class='img-responsive' />";
														} else {
															$slide_image = 'No image selected.';
														}
														?>
														<!-- drag handle -->
														<span class="input-group-addon input-group-move">
															<span class="handle">
																<i class="fa fa-arrows"></i>
															</span>
														</span>
														<span class="input-group-btn">
															<a href="javascript:void(0)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" title="Selected Image" data-content="<?php echo $slide_image; ?>" data-content="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'>Selected Image</h3><div class='popover-content'><?php echo $slide_image; ?></div></div>" class="btn btn-default"><i class="fa fa-eye"></i></a>
														</span>
														<?php echo form_input('homepage_slide[' . $i . '][image]', $slide->image, 'id="homepage_slide_' . $i . '_image" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=homepage_slide_<?php echo $i; ?>_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
														</span>
													</div>
												</div>
												<div class="col-md-4 form-group">
													<?php echo form_input('homepage_slide[' . $i . '][title]', $slide->title, 'id="homepage_slide_' . $i . '_title" class="form-control" placeholder="Title"'); ?>
												</div>
												<div class="col-md-4 form-group">
													<?php echo form_input('', rawurldecode($slide->link), 'id="homepage_slide_' . $i . '_link_label" class="form-control" placeholder="Link" disabled="disabled"'); ?>
													<div class="hidden">
														<?php echo form_input('homepage_slide[' . $i . '][link]', $slide->link, 'id="homepage_slide_' . $i . '_link" class="form-control" placeholder="Link"'); ?>
													</div>
												</div>
												<div class="col-md-1 form-group">
													<a href="#" class="btn btn-danger remove_field">Remove</a>
												</div>
												<div class="col-md-3">
													<?php echo form_label('On Date', 'homepage_slide_' . $i . '_on_date'); ?>
													<div class="input-group date datetimepicker-control datetimepicker_start_date-control">
														<?php echo form_input('homepage_slide[' . $i . '][on_date]', $slide->on_date, 'id="homepage_slide_' . $i . '_on_date" class="form-control pull-right"'); ?>
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<?php echo form_label('Off Date', 'homepage_slide_' . $i . '_off_date'); ?>
													<div class="input-group date datetimepicker-control datetimepicker_end_date-control">
														<?php echo form_input('homepage_slide[' . $i . '][off_date]', $slide->off_date, 'id="homepage_slide_' . $i . '_off_date" class="form-control pull-right"'); ?>
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
													</div>
												</div>
											</div>
											<hr />
										</div>
									<?php $i++;
									} ?>
								<?php } ?>
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
						<a href="<?php echo site_url('administrator/dashboard'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</section>