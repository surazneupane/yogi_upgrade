<?php

helper('form');

$category_slide = json_decode($category['slider_data']);
$category_slide_cnt = count((array)$category_slide);


?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#category-edit-form').submit();

		});


		var meta_title_elem = jQuery("#meta_title_chars");
		jQuery("#meta_title").limiter(60, meta_title_elem);

		var meta_desc_elem = jQuery("#meta_description_chars");
		jQuery("#meta_description").limiter(300, meta_desc_elem);


		// Media Images ADD MORE [START]

		var slider_max_fields = 10; //maximum input boxes allowed
		var slider_images_list_wrapper = jQuery("#slider_images_list-wrapper"); //Fields wrapper
		var slider_images_add_button = jQuery("#add_slider_image"); //Add button ID
		var x = <?php echo $category_slide_cnt; ?>; //initlal text box count
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
				slider_images_list_html += '<input name="category_slide[' + x + '][image]" value="" id="category_slide_' + x + '_image" class="form-control" type="text">';
				slider_images_list_html += '<span class="input-group-btn">';
				slider_images_list_html += '<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=category_slide_' + x + '_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>';
				slider_images_list_html += '</span>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-4 form-group">';
				slider_images_list_html += '<input name="category_slide[' + x + '][title]" id="category_slide_' + x + '_title" value="" class="form-control" placeholder="Title" type="text" />';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-4 form-group">';
				slider_images_list_html += '<input name="category_slide[' + x + '][link]" id="category_slide_' + x + '_link" value="" class="form-control" placeholder="Link" type="text" />';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-1 form-group">';
				slider_images_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-3">';
				slider_images_list_html += '<label for="category_slide_' + x + '_on_date">On Date</label>';
				slider_images_list_html += '<div class="input-group date datetimepicker-control datetimepicker_start_date-control">';
				slider_images_list_html += '<input name="category_slide[' + x + '][on_date]" value="<?php echo date('Y-m-d H:i:s'); ?>" id="category_slide_' + x + '_on_date" class="form-control pull-right" type="text">';
				slider_images_list_html += '<div class="input-group-addon">';
				slider_images_list_html += '<i class="fa fa-calendar"></i>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '</div>';
				slider_images_list_html += '<div class="col-md-3">';
				slider_images_list_html += '<label for="category_slide_' + x + '_off_date">On Date</label>';
				slider_images_list_html += '<div class="input-group date datetimepicker-control datetimepicker_off_date-control">';
				slider_images_list_html += '<input name="category_slide[' + x + '][off_date]" value="<?php echo date('Y-m-d H:i:s', strtotime("+5 days")); ?>" id="category_slide_' + x + '_off_date" class="form-control pull-right" type="text">';
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
<style type="text/css">
	.select2-container {
		width: 100% !important;
	}
</style>


<section class="content-header">
	<h1>
		Edit Category
		<small>Edit article category</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/categories'); ?>">Categories</a></li>
		<li class="active">Edit Category</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open('', array('id' => 'category-edit-form', 'class' => 'category-edit-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"'); ?>
						<a href="<?php echo site_url('administrator/categories'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<div class="box-body">

					<!-- Nav tabs -->
					<div class="data-tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
							<li role="presentation"><a href="#fr_details" aria-controls="fr_details" role="tab" data-toggle="tab">FR Details</a></li>
							<li role="presentation" class="hidden"><a href="#slides" aria-controls="slides" role="tab" data-toggle="tab">Slides</a></li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="details">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Title', 'title'); ?>
											<?php echo '<span class="text-danger">' . session('errors.title') . '</span>'; ?>

											<?php echo form_input('title', $category['title'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-4 hidden">
										<div class="form-group">
											<?php echo form_label('EN Title', 'en_title'); ?>
											<?php echo '<span class="text-danger">' . session('errors.en_title') . '</span>'; ?>
											<?php echo form_input('en_title', $category['en_title'] ?? "", 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Alias', 'alias'); ?>
											<?php echo '<span class="text-danger">' . session('errors.alias') . '</span>'; ?>

											<?php echo form_input('alias', $category['alias'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Parent', 'parent_id'); ?>
											<?php echo '<span class="text-danger">' . session('errors.parent_id') . '</span>'; ?>

											<?php
											$options = array();
											$options[0] = '- No Parent -';
											foreach ($categorieslist as $categorylist) {
												$options[$categorylist['id']] = $categorylist['title'];
											}
											echo form_dropdown('parent_id', $options, $category['parent_id'], 'class="form-control select2"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Color Style', 'color_style'); ?>
											<?php echo '<span class="text-danger">' . session('errors.color_style') . '</span>'; ?>

											<div class="input-group colorpicker-group">
												<?php echo form_input('color_style', $category['color_style'], 'class="form-control"'); ?>
												<div class="input-group-addon">
													<i></i>
												</div>
											</div>
											<!-- /.input group -->
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="form-group">
										<?php echo form_label('Description', 'description'); ?>
										<?php echo '<span class="text-danger">' . session('errors.description') . '</span>'; ?>

										<?php echo form_textarea('description', $category['description'], 'class="textarea"'); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Image', 'image'); ?>
											<?php echo '<span class="text-danger">' . session('errors.image') . '</span>'; ?>

											<div class="row">
												<div class="col-lg-7">
													<div class="input-group">

														<?php
														if ($category['image'] != '') {
															$category_image = "<img src='" . site_url('assets/media/') . $category['image'] . "' class='img-responsive' />";
														} else {
															$category_image = 'No image selected.';
														}
														?>

														<span class="input-group-btn">
															<a href="javascript:void(0)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" title="Selected Image" data-content="<?php echo $category_image; ?>" data-content="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'>Selected Image</h3><div class='popover-content'><?php echo $category_image; ?></div></div>" class="btn btn-default"><i class="fa fa-eye"></i></a>
														</span>

														<?php echo form_input('image', $category['image'], 'id="fieldImgID" class="form-control"'); ?>
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
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Status', 'status'); ?>
											<?php echo '<span class="text-danger">' . session('errors.status') . '</span>'; ?>

											<?php
											$options = array();
											$options[1] = 'Publish';
											$options[0] = 'Unpublish';
											echo form_dropdown('status', $options, $category['status'], 'class="form-control"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Created Date', 'created_date'); ?>
											<?php echo '<span class="text-danger">' . session('errors.created_date') . '</span>'; ?>

											<?php echo form_input('created_date', $category['created_date'], 'class="form-control readonly" readonly="readonly"'); ?>
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

											echo form_dropdown('created_by', $options, $category['created_by'], 'class="form-control"');
											?>
										</div>
									</div>
								</div>

								<hr />

								<div class="form-group">
									<?php echo form_label('Meta Title', 'meta_title'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_title_chars" class="text-primary">60</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_title') . '</span>'; ?>

									<?php echo form_input('meta_title', $category['meta_title'], 'id="meta_title" class="form-control" placeholder="Meta Title"'); ?>
								</div>
								<div class="form-group">
									<?php echo form_label('Meta Description', 'meta_description'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_description_chars" class="text-primary">300</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_description') . '</span>'; ?>

									<?php echo form_textarea('meta_description', $category['meta_description'], 'id="meta_description" class="form-control" placeholder="Meta Description"'); ?>
								</div>

							</div>

							<div role="tabpanel" class="tab-pane" id="fr_details">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('FR Title', 'title_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.title_fr') . '</span>'; ?>

											<?php echo form_input('title_fr', $category['title_fr'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('FR Alias', 'alias_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.alias_fr') . '</span>'; ?>

											<?php echo form_input('alias_fr', $category['alias_fr'], 'class="form-control"'); ?>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="form-group">
										<?php echo form_label('FR Description', 'description_fr'); ?>
										<?php echo '<span class="text-danger">' . session('errors.description_fr') . '</span>'; ?>

										<?php echo form_textarea('description_fr', $category['description_fr'], 'class="textarea"'); ?>
									</div>
								</div>

							</div>

							<div role="tabpanel" class="tab-pane" id="slides">

								<div id="category-slider_images-field" class="form-group category-slider_images-field">
									<br />
									<div class="slider_images_action">
										<p><button type="button" id="add_slider_image" class="btn bg-orange btn-add_slider_image"><i class="fa fa-plus"></i>&nbsp; Add</button></p>
										<p class="text-danger text-bold">Note : Off Date must be greater than On Date.</p>
									</div>
									<hr />
									<div id="slider_images_list-wrapper" class="slider_images_list-wrapper todo-list">
										<?php if (!empty($category_slide) && isset($slide->off_date) && isset($slide->on_date)) { ?>
											<?php $i = 1;
											foreach ($category_slide as $slide) { ?>
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
																<?php echo form_input('category_slide[' . $i . '][image]', $slide->image, 'id="category_slide_' . $i . '_image" class="form-control"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=category_slide_<?php echo $i; ?>_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="col-md-4 form-group">
															<?php echo form_input('category_slide[' . $i . '][title]', $slide->title, 'id="category_slide_' . $i . '_title" class="form-control" placeholder="Title"'); ?>
														</div>
														<div class="col-md-4 form-group">
															<?php echo form_input('', rawurldecode($slide->link), 'id="category_slide_' . $i . '_link_label" class="form-control" placeholder="Link" disabled="disabled"'); ?>
															<div class="hidden">
																<?php echo form_input('category_slide[' . $i . '][link]', $slide->link, 'id="category_slide_' . $i . '_link" class="form-control" placeholder="Link"'); ?>
															</div>
														</div>
														<div class="col-md-1 form-group">
															<a href="#" class="btn btn-danger remove_field">Remove</a>
														</div>
														<div class="col-md-3">
															<?php echo form_label('On Date', 'category_slide_' . $i . '_on_date'); ?>
															<div class="input-group date datetimepicker-control datetimepicker_start_date-control">
																<?php echo form_input('category_slide[' . $i . '][on_date]', $slide->on_date, 'id="category_slide_' . $i . '_on_date" class="form-control pull-right"'); ?>
																<div class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<?php echo form_label('Off Date', 'category_slide_' . $i . '_off_date'); ?>
															<div class="input-group date datetimepicker-control datetimepicker_end_date-control">
																<?php echo form_input('category_slide[' . $i . '][off_date]', $slide->off_date, 'id="category_slide_' . $i . '_off_date" class="form-control pull-right"'); ?>
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

					</div>

				</div>
				<!-- /.box-body -->

				<?php echo form_hidden('category_id', set_value('category_id', $category['id'])); ?>
				<?php echo form_hidden('task', ''); ?>

				<div class="box-footer">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"'); ?>
						<a href="<?php echo site_url('administrator/categories'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</section>