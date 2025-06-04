<?php

helper('form');

?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#article-create-form').submit();

		});


		var meta_title_elem = jQuery("#meta_title_chars");
		jQuery("#meta_title").limiter(60, meta_title_elem);

		var meta_desc_elem = jQuery("#meta_description_chars");
		jQuery("#meta_description").limiter(300, meta_desc_elem);


		var meta_title_fr_elem = jQuery("#meta_title_fr_chars");
		jQuery("#meta_title_fr").limiter(60, meta_title_fr_elem);

		var meta_desc_fr_elem = jQuery("#meta_description_fr_chars");
		jQuery("#meta_description_fr").limiter(300, meta_desc_fr_elem);


		var main_category_id = jQuery("#main_category_id").val();
		getSubCategories(main_category_id);
		jQuery("#main_category_id").on('change', function(e) {

			var main_category_id_data = jQuery(this).select2('data');
			console.log(main_category_id_data);
			var main_category_id = main_category_id_data[0].id;

			jQuery('#main_subcategory_ids').val(null).trigger('change');
			getSubCategories(main_category_id);

		});


		// Media Images ADD MORE [START]

		var media_max_fields = 10; //maximum input boxes allowed
		var media_images_list_wrapper = jQuery("#media_images_list-wrapper"); //Fields wrapper
		var media_images_add_button = jQuery("#add_media_image"); //Add button ID
		var x = 1; //initlal text box count
		jQuery(media_images_add_button).click(function(e) {
			e.preventDefault();
			if (x < media_max_fields) { //max input box allowed
				x++; //text box increment

				var media_images_list_html = '<div class="media_images_list-container">';
				media_images_list_html = '<div class="input-group">';
				media_images_list_html += '<!-- drag handle -->';
				media_images_list_html += '<span class="input-group-addon input-group-move">';
				media_images_list_html += '<span class="handle">';
				media_images_list_html += '<i class="fa fa-arrows"></i>';
				media_images_list_html += '</span>';
				media_images_list_html += '</span>';
				media_images_list_html += '<span class="input-group-addon">';
				if (x == 1) {
					media_images_list_html += '<input name="media_image_main" checked="checked" value="' + (x - 1) + '" id="media_image_main_' + x + '" type="radio">';
				} else {
					media_images_list_html += '<input name="media_image_main" value="' + (x - 1) + '" id="media_image_main_' + x + '" type="radio">';
				}
				media_images_list_html += '</span>';
				media_images_list_html += '<input name="media_images[]" value="" id="media_imagesID' + x + '" class="form-control" type="text">';
				media_images_list_html += '<span class="input-group-btn">';
				media_images_list_html += '<a href="#" class="btn btn-default" data-toggle="modal" data-target="#mediaModal' + x + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
				media_images_list_html += '<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=media_imagesID' + x + '&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>';
				media_images_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
				media_images_list_html += '</span>';
				media_images_list_html += '</div>';
				media_images_list_html += '<div class="modal fade" id="mediaModal' + x + '" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel">';
				media_images_list_html += '<div class="modal-dialog" role="document">';
				media_images_list_html += '<div class="modal-content">';
				media_images_list_html += '<div class="modal-header">';
				media_images_list_html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
				media_images_list_html += '<h4 class="modal-title" id="mediaLabel">Media Description - ' + x + '</h4>';
				media_images_list_html += '</div>';
				media_images_list_html += '<div class="modal-body">';
				media_images_list_html += '<div class="form-group">';
				media_images_list_html += '<textarea name="media_texts[]" rows="4" id="media_textsID' + x + '" class="form-control" placeholder="Media Description"></textarea>';
				media_images_list_html += '</div>';
				media_images_list_html += '</div>';
				media_images_list_html += '<div class="modal-footer">';
				media_images_list_html += '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>';
				media_images_list_html += '</div>';
				media_images_list_html += '</div>';
				media_images_list_html += '</div>';
				media_images_list_html += '</div>';
				media_images_list_html += '</div>';

				jQuery(media_images_list_wrapper).append(media_images_list_html); //add input box
			}
		});

		jQuery(media_images_list_wrapper).on("click", ".remove_field", function(e) { //user click on remove text
			e.preventDefault();
			jQuery(this).parent().parent('div').remove();
			x--;
		});

		// Media Images ADD MORE [END]


		// Travel Images ADD MORE [START]

		var travel_max_fields = 10; //maximum input boxes allowed
		var travel_images_list_wrapper = jQuery("#travel_list-wrapper"); //Fields wrapper
		var travel_images_add_button = jQuery("#add_travel_image"); //Add button ID
		var x = 1; //initlal text box count
		jQuery(travel_images_add_button).click(function(e) {
			e.preventDefault();
			if (x < travel_max_fields) { //max input box allowed
				x++; //text box increment

				var travel_images_list_html = '<div class="col-md-6 travel_list-container">';
				travel_images_list_html += '<div class="input-group">';
				//travel_images_list_html += '<!-- drag handle -->';
				//travel_images_list_html += '<span class="input-group-addon input-group-move">';
				//	travel_images_list_html += '<span class="handle">';
				//		travel_images_list_html += '<i class="fa fa-arrows"></i>';
				//	travel_images_list_html += '</span>';
				//travel_images_list_html += '</span>';
				travel_images_list_html += '<input name="travel_data[' + x + '][image]" value="" id="travel_imagesID' + x + '_image" class="form-control" type="text">';
				travel_images_list_html += '<span class="input-group-btn">';
				travel_images_list_html += '<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=travel_imagesID' + x + '_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>';
				travel_images_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
				travel_images_list_html += '</span>';
				travel_images_list_html += '</div>';
				travel_images_list_html += '<div class="form-group">';
				travel_images_list_html += '<textarea name="travel_data[' + x + '][description]" id="travel_descriptionsID' + x + '_description" class="form-control" placeholder="Travel Description"></textarea>';
				travel_images_list_html += '</div>';
				travel_images_list_html += '<hr/>';
				travel_images_list_html += '</div>';

				jQuery(travel_images_list_wrapper).append(travel_images_list_html); //add input box
			}
		});

		jQuery(travel_images_list_wrapper).on("click", ".remove_field", function(e) { //user click on remove text
			e.preventDefault();
			jQuery(this).parent().parent('div').parent('div').remove();
			x--;
		});

		// Travel Images ADD MORE [END]



		// INGREDIENTS ADD MORE [START]

		var ingredients_max_fields = 20; //maximum input boxes allowed
		var ingredients_list_wrapper = jQuery("#ingredients_list-wrapper"); //Fields wrapper
		var ingredients_add_button = jQuery("#add_ingredients"); //Add button ID
		var x = 1; //initlal text box count
		jQuery(ingredients_add_button).click(function(e) {
			e.preventDefault();
			if (x < ingredients_max_fields) { //max input box allowed
				x++; //text box increment

				var ingredients_list_html = '<div class="input-group">';
				ingredients_list_html += '<!-- drag handle -->';
				ingredients_list_html += '<span class="input-group-addon input-group-move">';
				ingredients_list_html += '<span class="handle">';
				ingredients_list_html += '<i class="fa fa-arrows"></i>';
				ingredients_list_html += '</span>';
				ingredients_list_html += '</span>';
				ingredients_list_html += '<input name="recipe_ingredients[]" value="" id="recipe_ingredientsID' + x + '" class="form-control" type="text">';
				ingredients_list_html += '<span class="input-group-btn">';
				ingredients_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
				ingredients_list_html += '</span>';
				ingredients_list_html += '</div>';

				jQuery(ingredients_list_wrapper).append(ingredients_list_html); //add input box
			}
		});

		jQuery(ingredients_list_wrapper).on("click", ".remove_field", function(e) { //user click on remove text
			e.preventDefault();
			jQuery(this).parent().parent('div').remove();
			x--;
		});

		// INGREDIENTS ADD MORE [END]


		jQuery('#article-media_images-field').hide();
		jQuery('#article_layout_left_col').show();
		jQuery('#article_layout_left_col').removeClass('col-md-12');
		jQuery('#article_layout_left_col').removeClass('col-md-4');
		jQuery('#article_layout_left_col').removeClass('col-md-5');
		jQuery('#article_layout_left_col').removeClass('col-md-6');
		jQuery('#article_layout_left_col').addClass('col-md-8');
		jQuery('#article_layout_right_col').removeClass('col-md-8');
		jQuery('#article_layout_right_col').removeClass('col-md-12');
		jQuery('#article_layout_right_col').removeClass('col-md-6');
		jQuery('#article_layout_right_col').removeClass('col-md-7');
		jQuery('#article_layout_right_col').addClass('col-md-4');
		jQuery('#article_layout_right_col').show();
		jQuery('#article-image-field').show();
		//jQuery('#article-mangomolo_videoid-field').show();
		jQuery('#article-recipe-block').hide();
		jQuery('#article-horoscope-block').hide();
		jQuery('#article-travel-block').hide();
		jQuery('#article-questions_test-block').hide();


		/*jQuery('#article_layout').change(function (e) { 

			var article_layout = jQuery(this).val();
			
			if(article_layout == 'text') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-8');
				jQuery('#article_layout_left_col').removeClass('col-md-4');
				jQuery('#article_layout_left_col').removeClass('col-md-5');
				jQuery('#article_layout_left_col').removeClass('col-md-6');
				jQuery('#article_layout_left_col').addClass('col-md-12');
				jQuery('#article_layout_right_col').removeClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-12');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-4');
				jQuery('#article_layout_right_col').hide();
				jQuery('#article-image-field').hide();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-horoscope-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
								
			} else if(article_layout == 'text_media') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').removeClass('col-md-4');
				jQuery('#article_layout_left_col').removeClass('col-md-5');
				jQuery('#article_layout_left_col').removeClass('col-md-6');
				jQuery('#article_layout_left_col').addClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-12');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-4');
				jQuery('#article_layout_right_col').show();
				jQuery('#article-image-field').show();
				jQuery('#article-mangomolo_videoid-field').show();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-horoscope-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
				
			} else if(article_layout == 'text_mulitplemedia') {
				
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').removeClass('col-md-4');
				jQuery('#article_layout_left_col').removeClass('col-md-5');
				jQuery('#article_layout_left_col').removeClass('col-md-6');
				jQuery('#article_layout_left_col').addClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-12');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-4');
				jQuery('#article_layout_right_col').show();
				jQuery('#article-image-field').hide();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-media_images-field').show();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-horoscope-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
				
			} else if(article_layout == 'text_mulitplemedia_6') {
				
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').removeClass('col-md-4');
				jQuery('#article_layout_left_col').removeClass('col-md-5');
				jQuery('#article_layout_left_col').removeClass('col-md-6');
				jQuery('#article_layout_left_col').addClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-12');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-4');
				jQuery('#article_layout_right_col').show();
				jQuery('#article-image-field').hide();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-media_images-field').show();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-horoscope-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
				
			} else if(article_layout == 'media') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').hide();
				jQuery('#article_layout_right_col').show();
				jQuery('#article_layout_right_col').removeClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-12');
				jQuery('#article-image-field').show();
				jQuery('#article-mangomolo_videoid-field').show();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-horoscope-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
				
			} else if(article_layout == 'recipe') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').removeClass('col-md-4');
				jQuery('#article_layout_left_col').removeClass('col-md-5');
				jQuery('#article_layout_left_col').removeClass('col-md-6');
				jQuery('#article_layout_left_col').addClass('col-md-8');
				jQuery('#article_layout_right_col').show();
				jQuery('#article_layout_right_col').removeClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-12');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-4');
				jQuery('#article-image-field').show();
				jQuery('#article-mangomolo_videoid-field').show();
				jQuery('#article-recipe-block').show();
				jQuery('#article-horoscope-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
				
			} else if(article_layout == 'horoscope') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').addClass('col-md-4');
				jQuery('#article_layout_left_col').addClass('col-md-5');
				jQuery('#article_layout_left_col').addClass('col-md-6');
				jQuery('#article_layout_right_col').show();
				jQuery('#article_layout_right_col').removeClass('col-md-4');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-8');
				jQuery('#article-image-field').show();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-horoscope-block').show();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
				
			} else if(article_layout == 'fashion') {
			
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').removeClass('col-md-4');
				jQuery('#article_layout_left_col').removeClass('col-md-5');
				jQuery('#article_layout_left_col').removeClass('col-md-6');
				jQuery('#article_layout_left_col').addClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-8');
				jQuery('#article_layout_right_col').removeClass('col-md-12');
				jQuery('#article_layout_right_col').removeClass('col-md-6');
				jQuery('#article_layout_right_col').removeClass('col-md-7');
				jQuery('#article_layout_right_col').addClass('col-md-4');
				jQuery('#article_layout_right_col').show();
				jQuery('#article-image-field').hide();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-media_images-field').show();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-horoscope-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').hide();
				
			} else if(article_layout == 'travel') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').addClass('col-md-6');
				jQuery('#article_layout_right_col').show();
				jQuery('#article_layout_right_col').removeClass('col-md-4');
				jQuery('#article_layout_right_col').addClass('col-md-6');
				jQuery('#article-image-field').show();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-questions_test-block').hide();
				jQuery('#article-travel-block').show();
				
			} else if(article_layout == 'runaway') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').addClass('col-md-6');
				jQuery('#article_layout_right_col').show();
				jQuery('#article_layout_right_col').removeClass('col-md-4');
				jQuery('#article_layout_right_col').addClass('col-md-6');
				jQuery('#article-image-field').show();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-questions_test-block').hide();
				jQuery('#article-travel-block').show();
				
			} else if(article_layout == 'questions_test') {
				
				jQuery('#article-media_images-field').hide();
				jQuery('#article_layout_left_col').show();
				jQuery('#article_layout_left_col').removeClass('col-md-12');
				jQuery('#article_layout_left_col').addClass('col-md-5');
				jQuery('#article_layout_right_col').show();
				jQuery('#article_layout_right_col').removeClass('col-md-4');
				jQuery('#article_layout_right_col').addClass('col-md-7');
				jQuery('#article-image-field').show();
				jQuery('#article-mangomolo_videoid-field').hide();
				jQuery('#article-recipe-block').hide();
				jQuery('#article-travel-block').hide();
				jQuery('#article-questions_test-block').show();
				
			}
			
		});*/

		jQuery('.datepicker-control').datetimepicker({
			format: 'YYYY-MM-DD'
		});


		function getSubCategories(main_category_id) {

			// get the hash 
			var csrf_test_name = jQuery("input[name=csrf_test_name]").val();
			jQuery.ajax({
					method: "POST",
					url: "<?php echo site_url('administrator/categories/getsubcategories'); ?>",
					data: {
						csrf_test_name: csrf_test_name,
						main_category_id: main_category_id
					},
					dataType: "json",
					beforeSend: function() {
						swal("Wait...", {
							closeOnClickOutside: false,
							buttons: false,
							timer: 500
						});
					}
				})
				.done(function(response) {
					jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);
					swal.close();
					console.log(response.subcategories);
					var options;
					jQuery.each(response.subcategories, function(i, subcategory) {
						options += '<option value="' + subcategory.id + '">' + subcategory.title + '</option>';
					});
					jQuery('#main_subcategory_ids').html(options).trigger('change');
				});

		}

	});
</script>


<section class="content-header">
	<h1>
		Create Article
		<small>Create content article</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/articles'); ?>">Articles</a></li>
		<li class="active">Create article</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open('', array('id' => 'article-create-form', 'class' => 'article-create-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"'); ?>
						<a href="<?php echo site_url('administrator/articles'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<div class="box-body">

					<!-- Nav tabs -->
					<div class="data-tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">EN Details</a></li>
							<li role="presentation"><a href="#details_fr" aria-controls="details_fr" role="tab" data-toggle="tab">FR Details</a></li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">

							<!--  DETAILS	[START]		-->
							<div role="tabpanel" class="tab-pane active" id="details">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Featured', 'featured'); ?>
											<?php echo '<span class="text-danger">' . session('errors.featured') . '</span>'; ?>

											<div class="radiogroup_codeignitor">
												<div class="btn-group" data-toggle="buttons">
													<label class="btn btn-primary active">
														<?php echo form_radio(array('name' => 'featured', 'value' => '0', 'checked' => TRUE, 'id' => 'featured_0')); ?> <?php echo form_label('No', 'featured_0', ['class' => 'radio_label']); ?>
													</label>
													<label class="btn btn-primary">
														<?php echo form_radio(array('name' => 'featured', 'value' => '1', 'checked' => FALSE, 'id' => 'featured_1')); ?> <?php echo form_label('Yes', 'featured_1', ['class' => 'radio_label']); ?>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Title', 'title'); ?>
											<?php echo '<span class="text-danger">' . session('errors.title') . '</span>'; ?>

											<?php echo form_input('title', '', 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Alias', 'alias'); ?>
											<?php echo '<span class="text-danger">' . session('errors.alias') . '</span>'; ?>

											<?php echo form_input('alias', '', 'class="form-control"'); ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Category', 'main_category_id'); ?>
											<?php echo '<span class="text-danger">' . session('errors.main_category_id') . '</span>'; ?>

											<?php
											$options = array();
											if (!empty($categorieslist)) {
												//$default = $categorieslist[0]['id'];
												$default = '';
												foreach ($categorieslist as $categorylist) {
													$options[$categorylist['id']] = $categorylist['title'];
												}
											} else {
												$default = '';
												$options[] = 'No Categories';
											}
											echo form_dropdown('main_category_id', $options, $default, 'id="main_category_id" class="form-control select2" data-placeholder="Select a Category"');
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Sub Categories', 'main_subcategory_ids[]'); ?>
											<?php echo '<span class="text-danger">' . session('errors.main_subcategory_ids') . '</span>'; ?>

											<?php
											$options = array();
											$default = '';
											$options[] = '- Select Main Category first -';
											echo form_dropdown('main_subcategory_ids[]', $options, $default, 'id="main_subcategory_ids" class="form-control select2" data-placeholder="Select a Sub Categories" multiple="multiple"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Keywords', 'keywords'); ?>
											<?php echo '<span class="text-danger">' . session('errors.keywords') . '</span>'; ?>

											<?php echo form_input('keywords', '', 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Tags', 'tag_ids'); ?>
											<?php echo '<span class="text-danger">' . session('errors.tag_ids') . '</span>'; ?>

											<?php
											$options = array();
											foreach ($taglist as $tag) {
												$options[$tag['id']] = $tag['title'];
											}
											echo form_dropdown('tag_ids[]', $options, '', 'class="form-control select2" data-placeholder="Select a Tags" multiple="miltiple"');
											?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="form-group">
										<?php echo form_label('Article Layout', 'article_layout'); ?>
										<?php echo '<span class="text-danger">' . session('errors.article_layout') . '</span>'; ?>

										<?php
										$options   = array();
										$options['text_media'] = 'Text + Image';												/*$options['text'] = 'Text';
												$options['text_mulitplemedia'] = 'Text + Multiplemedia (Images Only)';
												$options['text_mulitplemedia_6'] = 'Text + Multiplemedia 6++ (Images Only)';
												$options['media'] = 'Media (Image or Video)';
												$options['recipe'] = 'Recipe';
												$options['horoscope'] = 'Horoscope';
												$options['fashion'] = 'Fashion';
												$options['travel'] = 'Travel';
												$options['runaway'] = 'Runway';
												$options['questions_test'] = 'Questions Test';*/
										echo form_dropdown('article_layout', $options, '', 'id="article_layout" class="form-control" data-placeholder="Select a Layout"');
										?>
									</div>
								</div>
								<div class="row">
									<div id="article_layout_left_col" class="col-md-12 article_layout_left_col">
										<div id="article-description-field" class="form-group article-description-field">
											<div class="form-group">
												<?php echo form_label('Description', 'description'); ?>
												<?php echo '<span class="text-danger">' . session('errors.description') . '</span>'; ?>

												<?php echo form_textarea('description', '', 'class="textarea"'); ?>
											</div>
										</div>
									</div>
									<div id="article_layout_right_col" class="col-md-4 article_layout_right_col" style="display: none;">

										<div id="article-image-field" class="form-group article-image-field" style="display: none;">
											<?php echo form_label('Image', 'image'); ?>
											<?php echo '<span class="text-danger">' . session('errors.image') . '</span>'; ?>

											<div class="row">
												<div class="col-lg-12">
													<div class="input-group">
														<?php echo form_input('image', '', 'id="fieldImgID" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImgID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
														</span>
													</div><!-- /input-group -->
												</div>
												<!--<div class="col-lg-5">
														<img src='' class='icon_div fieldImgID' width="50" />
													</div>-->
											</div>
										</div>

										<div id="article-mangomolo_videoid-field" class="form-group article-mangomolo_videoid-field" style="display: none;">
											<?php echo form_label('Select Video', 'mangomolo_videoid'); ?>
											<?php echo '<span class="text-danger">' . session('errors.mangomolo_videoid') . '</span>'; ?>

											<?php
											$options = array();
											$options[''] = 'Select video';
											foreach ($videolist as $video) {
												$options[$video['mangovideo_id']] = $video['title'];
											}
											echo form_dropdown('mangomolo_videoid', $options, '', 'class="form-control select2"');
											?>
										</div>

										<div id="article-media_images-field" class="form-group article-media_images-field" style="display: none;">
											<?php echo form_label('Media Images', 'media_images'); ?>
											<?php echo '<span class="text-danger">' . session('errors.media_images') . '</span>'; ?>

											<div class="media_images_action">
												<button type="button" id="add_media_image" class="btn bg-orange btn-add_media_image"><i class="fa fa-plus"></i>&nbsp; Add</button>
												<div class="text-danger small">Note : Select Radio button for main image of article.</div>
											</div>
											<hr />
											<div id="media_images_list-wrapper" class="media_images_list-wrapper todo-list">
												<div class="media_images_list-container">
													<div class="input-group">
														<!-- drag handle -->
														<span class="input-group-addon input-group-move">
															<span class="handle">
																<i class="fa fa-arrows"></i>
															</span>
														</span>
														<span class="input-group-addon">
															<?php echo form_radio(array('name' => 'media_image_main', 'value' => '0', 'checked' => TRUE, 'id' => 'media_image_main_1')); ?>
														</span>
														<?php echo form_input('media_images[]', '', 'id="media_imagesID1" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="#" class="btn btn-default" data-toggle="modal" data-target="#mediaModal1"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=media_imagesID1&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
															<a href="#" class="btn btn-danger remove_field">Remove</a>
														</span>
													</div>
													<!-- Modal -->
													<div class="modal fade" id="mediaModal1" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																	<h4 class="modal-title" id="mediaLabel">Media Description</h4>
																</div>
																<div class="modal-body">
																	<?php
																	$options = array(
																		'name' => 'media_texts[]',
																		'id' => 'media_textsID1',
																		'class' => 'form-control',
																		'placeholder' => 'Media Description',
																		'rows' => '4',
																		'value' => ''
																	);
																	echo form_textarea($options);
																	?>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div id="article-recipe-block" class="article-recipe-field" style="display: none;">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<?php echo form_label('Total Time', 'recipe_time'); ?>
														<?php echo '<span class="text-danger">' . session('errors.recipe_time') . '</span>'; ?>

														<?php echo form_input('recipe_time', '', 'class="form-control"'); ?>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<?php echo form_label('Servings Persons', 'recipe_persons'); ?>
														<?php echo '<span class="text-danger">' . session('errors.recipe_persons') . '</span>'; ?>

														<?php echo form_input('recipe_persons', '', 'class="form-control"'); ?>
													</div>
												</div>
											</div>
											<div class="form-group article-ingredients-field">
												<?php echo form_label('Ingredients', 'recipe_ingredients'); ?>
												<?php echo '<span class="text-danger">' . session('errors.recipe_ingredients') . '</span>'; ?>

												<div class="media_images_action">
													<button type="button" id="add_ingredients" class="btn bg-orange btn-add_ingredients"><i class="fa fa-plus"></i>&nbsp; Add</button>
												</div>
												<hr />
												<div id="ingredients_list-wrapper" class="ingredients_list-wrapper todo-list">
													<div class="input-group">
														<!-- drag handle -->
														<span class="input-group-addon input-group-move">
															<span class="handle">
																<i class="fa fa-arrows"></i>
															</span>
														</span>
														<?php echo form_input('recipe_ingredients[]', '', 'id="recipe_ingredientsID1" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="#" class="btn btn-danger remove_field">Remove</a>
														</span>
													</div>
												</div>
											</div>
										</div>

										<div id="article-horoscope-block" class="article-horoscope-field" style="display: none;">

											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<?php echo form_label('Horoscope Date', 'horoscope_date'); ?>
														<div class="input-group date datepicker-control">
															<?php echo form_input('horoscope_date', date('Y-m-d'), 'class="form-control pull-right"'); ?>
															<div class="input-group-addon">
																<i class="fa fa-calendar"></i>
															</div>
														</div>
														<?php echo '<span class="text-danger">' . session('errors.horoscope_date') . '</span>'; ?>

													</div>
												</div>
											</div>
											<?php echo form_label('Horoscopes', 'horoscope_data'); ?>
											<?php echo '<span class="text-danger">' . session('errors.horoscope_data') . '</span>'; ?>

											<hr />
											<div id="horoscope_data_list-wrapper" class="horoscope_data_list-wrapper">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[1][image]', 'horoscope/horoscope-aries.png', 'id="horoscope_data_1_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_1_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[1][title]', 'برج الحمل', 'id="horoscope_data_1_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[1][description]', '', 'id="horoscope_data_1_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[2][image]', 'horoscope/horoscope-taurus.png', 'id="horoscope_data_2_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_2_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[2][title]', 'برج الثور', 'id="horoscope_data_2_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[2][description]', '', 'id="horoscope_data_2_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[3][image]', 'horoscope/horoscope-gemini.png', 'id="horoscope_data_3_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_3_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[3][title]', 'برج الجوزاء', 'id="horoscope_data_3_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[3][description]', '', 'id="horoscope_data_3_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[4][image]', 'horoscope/horoscope-gemini.png', 'id="horoscope_data_4_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_4_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[4][title]', 'برج السرطان', 'id="horoscope_data_4_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[4][description]', '', 'id="horoscope_data_4_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[5][image]', 'horoscope/horoscope-leo.png', 'id="horoscope_data_5_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_5_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[5][title]', 'برج الأسد', 'id="horoscope_data_5_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[5][description]', '', 'id="horoscope_data_5_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[6][image]', 'horoscope/horoscope-virgo.png', 'id="horoscope_data_6_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_6_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[6][title]', 'برج العذراء', 'id="horoscope_data_6_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[6][description]', '', 'id="horoscope_data_6_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[7][image]', 'horoscope/horoscope-libra.png', 'id="horoscope_data_7_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_7_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[7][title]', 'برج الميزان', 'id="horoscope_data_7_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[7][description]', '', 'id="horoscope_data_7_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[8][image]', 'horoscope/horoscope-scorpio.png', 'id="horoscope_data_8_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_8_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[8][title]', 'برج العقرب', 'id="horoscope_data_8_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[8][description]', '', 'id="horoscope_data_8_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[9][image]', 'horoscope/horoscope-sagittarius.png', 'id="horoscope_data_9_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_9_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[9][title]', 'برج القوس', 'id="horoscope_data_9_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[9][description]', '', 'id="horoscope_data_9_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
														<hr />
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[10][image]', 'horoscope/horoscope-capricorn.png', 'id="horoscope_data_10_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_10_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[10][title]', 'برج الجدي', 'id="horoscope_data_10_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[10][description]', '', 'id="horoscope_data_10_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[11][image]', 'horoscope/horoscope-aquarius.png', 'id="horoscope_data_11_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_11_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[11][title]', 'برج الدلو', 'id="horoscope_data_11_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[11][description]', '', 'id="horoscope_data_11_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<div class="input-group">
																<?php echo form_input('horoscope_data[12][image]', 'horoscope/horoscope-pisces.png', 'id="horoscope_data_12_image" class="form-control" placeholder="Horoscope Image"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_12_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																</span>
															</div>
														</div>
														<div class="form-group">
															<?php echo form_input('horoscope_data[12][title]', 'برج الحوت', 'id="horoscope_data_12_title" class="form-control" placeholder="Horoscope Title"'); ?>
														</div>
														<div class="form-group">
															<?php echo form_textarea('horoscope_data[12][description]', '', 'id="horoscope_data_12_description" class="form-control" placeholder="Horoscope Description"'); ?>
														</div>
													</div>
												</div>
											</div>

										</div>


										<div id="article-travel-block" class="article-travel-field" style="display: none;">

											<?php echo form_label('Travel Information', 'travel_data'); ?>
											<?php echo '<span class="text-danger">' . session('errors.travel_data') . '</span>'; ?>

											<div class="travel_images_action">
												<button type="button" id="add_travel_image" class="btn bg-orange btn-add_travel_image"><i class="fa fa-plus"></i>&nbsp; Add</button>
											</div>
											<hr />
											<!--<div id="travel_list-wrapper" class="row travel_list-wrapper todo-list">-->
											<div id="travel_list-wrapper" class="row travel_list-wrapper">
												<div class="travel_list-container col-md-6">
													<div class="input-group">
														<!-- drag handle -->
														<!--<span class="input-group-addon input-group-move">
																<span class="handle">
																	<i class="fa fa-arrows"></i>
																</span>
															</span>-->
														<?php echo form_input('travel_data[1][image]', '', 'id="travel_imagesID1_image" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=travel_imagesID1_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
															<a href="#" class="btn btn-danger remove_field">Remove</a>
														</span>
													</div>
													<div class="form-group">
														<?php echo form_textarea('travel_data[1][description]', '', 'id="travel_descriptionsID1_description" class="form-control" placeholder="Travel Description"'); ?>
													</div>
													<hr />
												</div>
											</div>

										</div>


										<div id="article-questions_test-block" class="article-questions_test-field" style="display: none;">

											<?php echo form_label('Questions', 'questions_test_data'); ?>
											<?php echo '<span class="text-danger">' . session('errors.questions_test_data') . '</span>'; ?>

											<hr />
											<div id="questions_test_data_list-wrapper" class="questions_test_data_list-wrapper">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_data[1][question]', '', 'id="questions_test_data_1_question" class="form-control" placeholder="Question 1"'); ?>
														</div>
														<div class="row">
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[1][answer_1]', '', 'id="questions_test_data_1_answer_1" class="form-control" placeholder="Answer 1"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[1][answer_2]', '', 'id="questions_test_data_1_answer_2" class="form-control" placeholder="Answer 2"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[1][answer_3]', '', 'id="questions_test_data_1_answer_3" class="form-control" placeholder="Answer 3"'); ?>
															</div>
														</div>
														<hr />
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_data[2][question]', '', 'id="questions_test_data_2_question" class="form-control" placeholder="Question 2"'); ?>
														</div>
														<div class="row">
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[2][answer_1]', '', 'id="questions_test_data_2_answer_1" class="form-control" placeholder="Answer 1"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[2][answer_2]', '', 'id="questions_test_data_2_answer_2" class="form-control" placeholder="Answer 2"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[2][answer_3]', '', 'id="questions_test_data_2_answer_3" class="form-control" placeholder="Answer 3"'); ?>
															</div>
														</div>
														<hr />
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_data[3][question]', '', 'id="questions_test_data_3_question" class="form-control" placeholder="Question 3"'); ?>
														</div>
														<div class="row">
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[3][answer_1]', '', 'id="questions_test_data_3_answer_1" class="form-control" placeholder="Answer 1"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[3][answer_2]', '', 'id="questions_test_data_3_answer_2" class="form-control" placeholder="Answer 2"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[3][answer_3]', '', 'id="questions_test_data_3_answer_3" class="form-control" placeholder="Answer 3"'); ?>
															</div>
														</div>
														<hr />
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_data[4][question]', '', 'id="questions_test_data_4_question" class="form-control" placeholder="Question 4"'); ?>
														</div>
														<div class="row">
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[4][answer_1]', '', 'id="questions_test_data_4_answer_1" class="form-control" placeholder="Answer 1"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[4][answer_2]', '', 'id="questions_test_data_4_answer_2" class="form-control" placeholder="Answer 2"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[4][answer_3]', '', 'id="questions_test_data_4_answer_3" class="form-control" placeholder="Answer 3"'); ?>
															</div>
														</div>
														<hr />
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_data[5][question]', '', 'id="questions_test_data_5_question" class="form-control" placeholder="Question 5"'); ?>
														</div>
														<div class="row">
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[5][answer_1]', '', 'id="questions_test_data_5_answer_1" class="form-control" placeholder="Answer 1"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[5][answer_2]', '', 'id="questions_test_data_5_answer_2" class="form-control" placeholder="Answer 2"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[5][answer_3]', '', 'id="questions_test_data_5_answer_3" class="form-control" placeholder="Answer 3"'); ?>
															</div>
														</div>
														<hr />
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_data[6][question]', '', 'id="questions_test_data_6_question" class="form-control" placeholder="Question 6"'); ?>
														</div>
														<div class="row">
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[6][answer_1]', '', 'id="questions_test_data_6_answer_1" class="form-control" placeholder="Answer 1"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[6][answer_2]', '', 'id="questions_test_data_6_answer_2" class="form-control" placeholder="Answer 2"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[6][answer_3]', '', 'id="questions_test_data_6_answer_3" class="form-control" placeholder="Answer 3"'); ?>
															</div>
														</div>
														<hr />
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_data[7][question]', '', 'id="questions_test_data_7_question" class="form-control" placeholder="Question 7"'); ?>
														</div>
														<div class="row">
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[7][answer_1]', '', 'id="questions_test_data_7_answer_1" class="form-control" placeholder="Answer 1"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[7][answer_2]', '', 'id="questions_test_data_7_answer_2" class="form-control" placeholder="Answer 2"'); ?>
															</div>
															<div class="col-md-4">
																<?php echo form_input('questions_test_data[7][answer_3]', '', 'id="questions_test_data_7_answer_3" class="form-control" placeholder="Answer 3"'); ?>
															</div>
														</div>
														<hr />
													</div>
												</div>
											</div>


											<?php echo form_label('Answers', 'questions_test_answer_data'); ?>
											<?php echo '<span class="text-danger">' . session('errors.questions_test_answer_data') . '</span>'; ?>

											<hr />
											<div id="questions_test_answer_data_list-wrapper" class="questions_test_answer_data_list-wrapper">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_answer_titles[1][title]', '', 'id="questions_test_answer_titles_1_title" class="form-control" placeholder="Title 1"'); ?>
															<?php echo form_input('questions_test_answer_data[1][answer]', '', 'id="questions_test_answer_data_1_question" class="form-control" placeholder="Answer 1"'); ?>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_answer_titles[2][title]', '', 'id="questions_test_answer_titles_2_title" class="form-control" placeholder="Title 2"'); ?>
															<?php echo form_input('questions_test_answer_data[2][answer]', '', 'id="questions_test_answer_data_2_question" class="form-control" placeholder="Answer 2"'); ?>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_answer_titles[3][title]', '', 'id="questions_test_answer_titles_3_title" class="form-control" placeholder="Title 3"'); ?>
															<?php echo form_input('questions_test_answer_data[3][answer]', '', 'id="questions_test_answer_data_3_question" class="form-control" placeholder="Answer 3"'); ?>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?php echo form_input('questions_test_answer_titles[4][title]', '', 'id="questions_test_answer_titles_4_title" class="form-control" placeholder="Title 4"'); ?>
															<?php echo form_input('questions_test_answer_data[4][answer]', '', 'id="questions_test_answer_data_4_question" class="form-control" placeholder="Answer 4"'); ?>
														</div>
													</div>
												</div>
											</div>

										</div>


									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Status', 'status'); ?>
											<?php echo '<span class="text-danger">' . session('errors.status') . '</span>'; ?>

											<?php
											$options = array();
											$options[1] = 'Publish';
											$options[0] = 'Unpublish';
											$options[2] = 'Draft';
											$options[3] = 'Ready';
											$options[4] = 'Approved';
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
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">

											<?php echo form_label('Published Date', 'published_date'); ?>
											<div class="input-group date datetimepicker-control">
												<?php echo form_input('published_date', date('Y-m-d H:i:s'), 'class="form-control pull-right"'); ?>
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
											</div>
											<?php echo '<span class="text-danger">' . session('errors.published_date') . '</span>'; ?>

										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Published By', 'published_by'); ?>
											<?php echo '<span class="text-danger">' . session('errors.published_by') . '</span>'; ?>

											<?php
											$options = array();
											foreach ($userlist as $user) {
												$options[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
											}

											echo form_dropdown('published_by', $options, '', 'class="form-control"');
											?>
										</div>
									</div>
								</div>

								<hr />
								<div class="form-group">
									<?php echo form_label('Meta Title', 'meta_title'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_title_chars" class="text-primary">60</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_title') . '</span>'; ?>

									<?php echo form_input('meta_title', '', 'id="meta_title" class="form-control" placeholder="Meta Title"'); ?>
								</div>
								<div class="form-group">
									<?php echo form_label('Meta Description', 'meta_description'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_description_chars" class="text-primary">300</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_description') . '</span>'; ?>

									<?php echo form_textarea('meta_description', '', 'id="meta_description" class="form-control" placeholder="Meta Description"'); ?>
								</div>

							</div>
							<!--  DETAILS	[END]		-->

							<!--  DETAILS	[START]		-->
							<div role="tabpanel" class="tab-pane" id="details_fr">

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('FR Title', 'title_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.title_fr') . '</span>'; ?>

											<?php echo form_input('title_fr', '', 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('FR Alias', 'alias_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.alias_fr') . '</span>'; ?>

											<?php echo form_input('alias_fr', '', 'class="form-control"'); ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="article_layout_left_col" class="col-md-8 article_layout_left_col">
										<div id="article-description-field" class="form-group article-description-field">
											<div class="form-group">
												<?php echo form_label('FR Description', 'description_fr'); ?>
												<?php echo '<span class="text-danger">' . session('errors.description_fr') . '</span>'; ?>

												<?php echo form_textarea('description_fr', '', 'class="textarea"'); ?>
											</div>
										</div>
									</div>
									<div id="article_layout_right_col" class="col-md-4 article_layout_right_col">

										<div id="article-image-field" class="form-group article-image-field">
											<?php echo form_label('FR Image', 'image_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.image_fr') . '</span>'; ?>

											<div class="row">
												<div class="col-lg-12">
													<div class="input-group">
														<?php echo form_input('image_fr', '', 'id="fieldImg_frID" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImg_frID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
														</span>
													</div><!-- /input-group -->
												</div>
												<!--<div class="col-lg-5">
														<img src='' class='icon_div fieldImgID' width="50" />
													</div>-->
											</div>
										</div>
									</div>
								</div>

								<hr />
								<div class="form-group">
									<?php echo form_label('Meta Title', 'meta_title_fr'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_title_fr_chars" class="text-primary">60</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_title_fr') . '</span>'; ?>

									<?php echo form_input('meta_title_fr', '', 'id="meta_title_fr" class="form-control" placeholder="Meta Title"'); ?>
								</div>
								<div class="form-group">
									<?php echo form_label('Meta Description', 'meta_description_fr'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_description_fr_chars" class="text-primary">300</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_description_fr') . '</span>'; ?>

									<?php echo form_textarea('meta_description_fr', '', 'id="meta_description_fr" class="form-control" placeholder="Meta Description"'); ?>
								</div>

							</div>
							<!--  DETAILS	[END]		-->

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
						<a href="<?php echo site_url('administrator/articles'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>
</section>