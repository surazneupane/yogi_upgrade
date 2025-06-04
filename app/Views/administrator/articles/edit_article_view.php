<?php


helper('form');
$media_images = json_decode($article['media_images']);

$media_images_cnt = count($media_images);

$media_texts = json_decode($article['media_texts']);

$recipe_ingredients = json_decode($article['recipe_ingredients']);
$ingredients_cnt = count($recipe_ingredients);

$travel_data = json_decode($article['travel_data']);
$travel_data_cnt = count((array)$travel_data);

$selected_categories = explode(',', $article['category_ids']);

?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#article-edit-form').submit();

		});


		var meta_title_elem = jQuery("#meta_title_chars");
		jQuery("#meta_title").limiter(60, meta_title_elem);

		var meta_desc_elem = jQuery("#meta_description_chars");
		jQuery("#meta_description").limiter(300, meta_desc_elem);


		var meta_title_fr_elem = jQuery("#meta_title_fr_chars");
		jQuery("#meta_title_fr").limiter(60, meta_title_fr_elem);

		var meta_desc_fr_elem = jQuery("#meta_description_fr_chars");
		jQuery("#meta_description_fr").limiter(300, meta_desc_fr_elem);


		var main_category_id = <?php echo $selected_categories[0]; ?>;
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
		var x = <?php echo $media_images_cnt; ?>; //initlal text box count
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
				media_images_list_html += '<a href="#" class="btn btn-default" data-toggle="modal" data-target="#mediaModal' + (x - 1) + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
				media_images_list_html += '<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=media_imagesID' + (x - 1) + '&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>';
				media_images_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
				media_images_list_html += '</span>';
				media_images_list_html += '</div>';
				media_images_list_html += '<div class="modal fade" id="mediaModal' + (x - 1) + '" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel">';
				media_images_list_html += '<div class="modal-dialog" role="document">';
				media_images_list_html += '<div class="modal-content">';
				media_images_list_html += '<div class="modal-header">';
				media_images_list_html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
				media_images_list_html += '<h4 class="modal-title" id="mediaLabel">Media Description</h4>';
				media_images_list_html += '</div>';
				media_images_list_html += '<div class="modal-body">';
				media_images_list_html += '<div class="form-group">';
				media_images_list_html += '<textarea name="media_texts[]" rows="4" id="media_textsID' + (x - 1) + '" class="form-control" placeholder="Media Description"></textarea>';
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
		var y = <?php echo $travel_data_cnt; ?>; //initlal text box count
		jQuery(travel_images_add_button).click(function(e) {
			e.preventDefault();
			if (y < travel_max_fields) { //max input box allowed
				y++; //text box increment

				var travel_images_list_html = '<div class="col-md-6 travel_list-container">';
				travel_images_list_html += '<div class="input-group">';
				//travel_images_list_html += '<!-- drag handle -->';
				//travel_images_list_html += '<span class="input-group-addon input-group-move">';
				//	travel_images_list_html += '<span class="handle">';
				//		travel_images_list_html += '<i class="fa fa-arrows"></i>';
				//	travel_images_list_html += '</span>';
				//travel_images_list_html += '</span>';
				travel_images_list_html += '<input name="travel_data[' + y + '][image]" value="" id="travel_imagesID' + y + '_image" class="form-control" type="text">';
				travel_images_list_html += '<span class="input-group-btn">';
				travel_images_list_html += '<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=travel_imagesID' + y + '_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>';
				travel_images_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
				travel_images_list_html += '</span>';
				travel_images_list_html += '</div>';
				travel_images_list_html += '<div class="form-group">';
				travel_images_list_html += '<textarea name="travel_data[' + y + '][description]" id="travel_descriptionsID' + y + '_description" class="form-control" placeholder="Travel Description"></textarea>';
				travel_images_list_html += '</div>';
				travel_images_list_html += '<hr/>';
				travel_images_list_html += '</div>';

				jQuery(travel_images_list_wrapper).append(travel_images_list_html); //add input box
			}
		});


		// INGREDIENTS ADD MORE [START]

		var ingredients_max_fields = 20; //maximum input boxes allowed
		var ingredients_list_wrapper = jQuery("#ingredients_list-wrapper"); //Fields wrapper
		var ingredients_add_button = jQuery("#add_ingredients"); //Add button ID
		var z = <?php echo $ingredients_cnt; ?>; //initlal text box count
		jQuery(ingredients_add_button).click(function(e) {
			e.preventDefault();
			if (z < ingredients_max_fields) { //max input box allowed
				z++; //text box increment

				var ingredients_list_html = '<div class="input-group">';
				ingredients_list_html += '<!-- drag handle -->';
				ingredients_list_html += '<span class="input-group-addon input-group-move">';
				ingredients_list_html += '<span class="handle">';
				ingredients_list_html += '<i class="fa fa-arrows"></i>';
				ingredients_list_html += '</span>';
				ingredients_list_html += '</span>';
				ingredients_list_html += '<input name="recipe_ingredients[]" value="" id="recipe_ingredientsID' + z + '" class="form-control" type="text">';
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
			z--;
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


		/*var article_layout = jQuery('#article_layout').val();
		changeArticleLayoutDivs(article_layout);
				
		jQuery('#article_layout').change(function (e) { 
			var article_layout = jQuery(this).val();
			changeArticleLayoutDivs(article_layout);
		});
						
		function changeArticleLayoutDivs(article_layout) {
			
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
				
			}  else if(article_layout == 'runaway') {
				
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
			
		}*/

		function getSubCategories(main_category_id) {

			// get the hash 
			var selected_categories = <?php echo json_encode($selected_categories); ?>;
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
						if (jQuery.inArray(subcategory.id, selected_categories) != -1) {
							options += '<option value="' + subcategory.id + '" selected="selected">' + subcategory.title + '</option>';
						} else {
							options += '<option value="' + subcategory.id + '">' + subcategory.title + '</option>';
						}
					});
					jQuery('#main_subcategory_ids').html(options).trigger('change');
				});

		}

	});
</script>

<section class="content-header">
	<h1>
		Edit Article
		<small>Edit content article</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/articles'); ?>">Articles</a></li>
		<li class="active">Edit Article</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open('', array('id' => 'article-edit-form', 'class' => 'article-edit-form')); ?>

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
													<label class="btn btn-primary <?php if ($article['featured'] == 0) { ?>active<?php } ?>">
														<?php echo form_radio(array('name' => 'featured', 'value' => '0', 'checked' => ('0' == $article['featured']) ? TRUE : FALSE, 'id' => 'featured_0')); ?> <?php echo form_label('No', 'featured_0', ['class' => 'radio_label']); ?>
													</label>
													<label class="btn btn-primary <?php if ($article['featured'] == 1) { ?>active<?php } ?>">
														<?php echo form_radio(array('name' => 'featured', 'value' => '1', 'checked' => ('1' == $article['featured']) ? TRUE : FALSE, 'id' => 'featured_1')); ?> <?php echo form_label('Yes', 'featured_1', ['class' => 'radio_label']); ?>
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
											<?php echo form_input('title', $article['title'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Alias', 'alias'); ?>
											<?php echo '<span class="text-danger">' . session('errors.alias') . '</span>'; ?>

											<?php echo form_input('alias', $article['alias'], 'class="form-control"'); ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Category', 'category_ids'); ?>
											<?php echo '<span class="text-danger">' . session('errors.category_ids') . '</span>'; ?>

											<?php
											$selected_categories = explode(',', $article['category_ids']);
											$options = array();
											foreach ($categorieslist as $categorylist) {
												$options[$categorylist['id']] = $categorylist['title'];
											}
											echo form_dropdown('main_category_id', $options, $selected_categories[0], 'id="main_category_id" class="form-control select2" data-placeholder="Select a Category"');
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_label('Sub Categories', 'main_subcategory_ids[]'); ?>
											<?php echo '<span class="text-danger">' . session('errors.main_subcategory_ids') . '</span>'; ?>

											<?php
											$options = array();
											$default = $selected_categories;
											echo form_dropdown('main_subcategory_ids[]', '', $default, 'id="main_subcategory_ids" class="form-control select2" data-placeholder="Select a Sub Categories" multiple="multiple"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Keywords', 'keywords'); ?>
											<?php echo '<span class="text-danger">' . session('errors.keywords') . '</span>'; ?>

											<?php echo form_input('keywords', $article['keywords'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Tag', 'tag_ids'); ?>
											<?php echo '<span class="text-danger">' . session('errors.tag_ids') . '</span>'; ?>

											<?php
											$selected_tags = explode(',', $article['tag_ids']);
											$options = array();
											foreach ($taglist as $tag) {
												$options[$tag['id']] = $tag['title'];
											}
											echo form_dropdown('tag_ids[]', $options, $selected_tags, 'class="form-control select2" data-placeholder="Select a Tag" multiple="miltiple"');
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
										$options['text_media'] = 'Text + Image';
										/*$options['text'] = 'Text';
												$options['text_mulitplemedia'] = 'Text + Multiplemedia (Images Only)';
												$options['text_mulitplemedia_6'] = 'Text + Multiplemedia 6++ (Images Only)';
												$options['media'] = 'Media (Image or Video)';
												$options['recipe'] = 'Recipe';
												$options['horoscope'] = 'Horoscope';
												$options['fashion'] = 'Fashion';
												$options['travel'] = 'Travel';
												$options['runaway'] = 'Runway';
												$options['questions_test'] = 'Questions Test';*/
										echo form_dropdown('article_layout', $options, $article['article_layout'], 'id="article_layout" class="form-control" data-placeholder="Select a Layout"');
										?>
									</div>
								</div>
								<div class="row">
									<div id="article_layout_left_col" class="col-md-12 article_layout_left_col">
										<div id="article-description-field" class="form-group article-description-field">
											<div class="form-group">
												<?php echo form_label('Description', 'description'); ?>
												<?php echo '<span class="text-danger">' . session('errors.description') . '</span>'; ?>

												<?php echo form_textarea('description', $article['description'], 'class="textarea"'); ?>
											</div>
										</div>
									</div>
									<div id="article_layout_right_col" class="col-md-4 article_layout_right_col" style="display: none;">

										<div id="article-image-field" class="form-group article-image-field" style="display: none;">
											<?php echo form_label('Image', 'image'); ?>
											<?php echo '<span class="text-danger">' . session('errors.image') . '</span>'; ?>

											<div class="row">
												<div class="col-lg-9">
													<div class="input-group">
														<?php
														if ($article['image'] != '') {
															$article_image = "<img src='" . site_url('assets/media/') . $article['image'] . "' class='img-responsive' />";
														} else {
															$article_image = 'No image selected.';
														}
														?>
														<span class="input-group-btn">
															<a href="javascript:void(0)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" title="Selected Image" data-content="<?php echo $article_image; ?>" data-content="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'>Selected Image</h3><div class='popover-content'><?php echo $article_image; ?></div></div>" class="btn btn-default"><i class="fa fa-eye"></i></a>
														</span>
														<?php echo form_input('image', $article['image'], 'id="fieldImgID" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImgID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
														</span>
													</div><!-- /input-group -->
												</div>
												<?php if ($article['image'] != '') { ?>
													<div class="col-lg-3">
														<img src='<?php echo site_url('assets/media/') . $article['image']; ?>' class='icon_div fieldImgID' width="50" />
													</div>
												<?php } ?>
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
											echo form_dropdown('mangomolo_videoid', $options, $article['mangomolo_videoid'], 'class="form-control select2"');
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

												<?php if (!empty($media_images)) { ?>
													<?php $i = 0;
													foreach ($media_images as $key => $value) {  ?>
														<div class="media_images_list-container">
															<div class="input-group">
																<!-- drag handle -->
																<span class="input-group-addon input-group-move">
																	<span class="handle">
																		<i class="fa fa-arrows"></i>
																	</span>
																</span>
																<span class="input-group-addon">

																	<?php if ($key == $article['media_image_main']) { ?>
																		<?php echo form_radio(array('name' => 'media_image_main', 'value' => $key, 'checked' => TRUE, 'id' => 'media_image_main_1')); ?>
																	<?php } else { ?>
																		<?php echo form_radio(array('name' => 'media_image_main', 'value' => $key, 'checked' => FALSE, 'id' => 'media_image_main_1')); ?>
																	<?php } ?>

																</span>
																<?php
																if ($value != '') {
																	$article_image = "<img src='" . site_url('assets/media/') . $value . "' class='img-responsive' />";
																} else {
																	$article_image = 'No image selected.';
																}
																?>

																<span class="input-group-btn">
																	<a href="javascript:void(0)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" title="Selected Image" data-content="<?php echo $article_image; ?>" data-content="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'>Selected Image</h3><div class='popover-content'><?php echo $article_image; ?></div></div>" class="btn btn-default"><i class="fa fa-eye"></i></a>
																</span>
																<?php echo form_input('media_images[]', $value, 'id="media_imagesID' . $i . '" class="form-control"'); ?>
																<span class="input-group-btn">
																	<a href="#" class="btn btn-default" data-toggle="modal" data-target="#mediaModal<?php echo $i; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=media_imagesID<?php echo $i; ?>&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																	<a href="#" class="btn btn-danger remove_field">Remove</a>
																</span>
															</div>
															<!-- Modal -->
															<div class="modal fade" id="mediaModal<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel">
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
																				'id' => 'media_textsID' . $i,
																				'class' => 'form-control',
																				'placeholder' => 'Media Description',
																				'rows' => '4',
																				'value' => $media_texts[$i]
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
													<?php $i++;
													} ?>
												<?php } ?>
											</div>
										</div>

										<div id="article-recipe-block" class="article-recipe-field" style="display: none;">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<?php echo form_label('Total Time', 'recipe_time'); ?>
														<?php echo '<span class="text-danger">' . session('errors.recipe_time') . '</span>'; ?>

														<?php echo form_input('recipe_time', $article['recipe_time'], 'class="form-control"'); ?>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<?php echo form_label('Servings Persons', 'recipe_persons'); ?>
														<?php echo '<span class="text-danger">' . session('errors.recipe_persons') . '</span>'; ?>

														<?php echo form_input('recipe_persons', $article['recipe_persons'], 'class="form-control"'); ?>
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
													<?php if (!empty($recipe_ingredients)) { ?>
														<?php foreach ($recipe_ingredients as $key => $value) {  ?>
															<div class="input-group">
																<!-- drag handle -->
																<span class="input-group-addon input-group-move">
																	<span class="handle">
																		<i class="fa fa-arrows"></i>
																	</span>
																</span>
																<?php echo form_input('recipe_ingredients[]', $value, 'id="recipe_ingredientsID1" class="form-control"'); ?>
																<span class="input-group-btn">
																	<a href="#" class="btn btn-danger remove_field">Remove</a>
																</span>
															</div>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div>

										<div id="article-horoscope-block" class="article-horoscope-field" style="display: none;">

											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<?php echo form_label('Horoscope Date', 'horoscope_date'); ?>
														<div class="input-group date datepicker-control">
															<?php echo form_input('horoscope_date', $article['horoscope_date'], 'class="form-control pull-right"'); ?>
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

											<?php $horoscope_datas = json_decode($article['horoscope_data']); ?>

											<hr />
											<div id="horoscope_data_list-wrapper" class="horoscope_data_list-wrapper">
												<div class="row">
													<?php $i = 1;
													foreach ($horoscope_datas as $horoscope_data) { ?>
														<div class="col-md-4">
															<div class="form-group">
																<div class="input-group">
																	<?php echo form_input('horoscope_data[' . $i . '][image]', $horoscope_data->image, 'id="horoscope_data_' . $i . '_image" class="form-control" placeholder="Horoscope Image"'); ?>
																	<span class="input-group-btn">
																		<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=horoscope_data_<?php echo $i; ?>_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																	</span>
																</div>
															</div>
															<div class="form-group">
																<?php echo form_input('horoscope_data[' . $i . '][title]', $horoscope_data->title, 'id="horoscope_data_' . $i . '_title" class="form-control" placeholder="Horoscope Title"'); ?>
															</div>
															<div class="form-group">
																<?php echo form_textarea('horoscope_data[' . $i . '][description]', $horoscope_data->description, 'id="horoscope_data_' . $i . '_description" class="form-control" placeholder="Horoscope Description"'); ?>
															</div>
															<hr />
														</div>
													<?php $i++;
													} ?>
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
											<?php if (!empty($travel_data)) { ?>
												<!--<div id="travel_list-wrapper" class="row travel_list-wrapper todo-list">-->
												<div id="travel_list-wrapper" class="row travel_list-wrapper">
													<?php $i = 1;
													foreach ($travel_data as $travel) { ?>
														<div class="travel_list-container col-md-6">
															<div class="input-group">
																<!-- drag handle -->
																<!--<span class="input-group-addon input-group-move">
																<span class="handle">
																	<i class="fa fa-arrows"></i>
																</span>
															</span>-->

																<?php
																if ($travel->image != '') {
																	$travel_image = "<img src='" . site_url('assets/media/') . $travel->image . "' class='img-responsive' />";
																} else {
																	$travel_image = 'No image selected.';
																}
																?>
																<span class="input-group-btn">
																	<a href="javascript:void(0)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" title="Selected Image" data-content="<?php echo $travel_image; ?>" data-content="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'>Selected Image</h3><div class='popover-content'><?php echo $travel_image; ?></div></div>" class="btn btn-default"><i class="fa fa-eye"></i></a>
																</span>

																<?php echo form_input('travel_data[' . $i . '][image]', $travel->image, 'id="travel_imagesID' . $i . '_image" class="form-control"'); ?>
																<span class="input-group-btn">
																	<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=travel_imagesID<?php echo $i; ?>_image&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
																	<a href="#" class="btn btn-danger remove_field">Remove</a>
																</span>
															</div>
															<div class="form-group">
																<?php echo form_textarea('travel_data[' . $i . '][description]', $travel->description, 'id="travel_descriptionsID' . $i . '_description" class="form-control" placeholder="Travel Description"'); ?>
															</div>
															<hr />
														</div>
													<?php $i++;
													} ?>
												</div>
											<?php } ?>

										</div>


										<div id="article-questions_test-block" class="article-questions_test-field" style="display: none;">

											<?php $questions_test_data = json_decode($article['questions_test_data']); ?>

											<?php echo form_label('Questions', 'questions_test_data'); ?>
											<?php echo '<span class="text-danger">' . session('errors.questions_test_data') . '</span>'; ?>

											<hr />
											<div id="questions_test_data_list-wrapper" class="questions_test_data_list-wrapper">
												<div class="row">
													<?php $i = 1;
													foreach ($questions_test_data as $question_test_data) { ?>
														<div class="col-md-6">
															<div class="form-group">
																<?php echo form_input('questions_test_data[' . $i . '][question]', $question_test_data->question, 'id="questions_test_data_' . $i . '_question" class="form-control" placeholder="Question 1"'); ?>
															</div>
															<div class="row">
																<div class="col-md-4">
																	<?php echo form_input('questions_test_data[' . $i . '][answer_1]', $question_test_data->answer_1, 'id="questions_test_data_' . $i . '_answer_1" class="form-control" placeholder="Answer 1"'); ?>
																</div>
																<div class="col-md-4">
																	<?php echo form_input('questions_test_data[' . $i . '][answer_2]', $question_test_data->answer_2, 'id="questions_test_data_' . $i . '_answer_2" class="form-control" placeholder="Answer 2"'); ?>
																</div>
																<div class="col-md-4">
																	<?php echo form_input('questions_test_data[' . $i . '][answer_3]', $question_test_data->answer_3, 'id="questions_test_data_' . $i . '_answer_3" class="form-control" placeholder="Answer 3"'); ?>
																</div>
															</div>
															<hr />
														</div>
													<?php $i++;
													} ?>
												</div>
											</div>

											<?php $questions_test_answer_data = json_decode($article['questions_test_answer_data']); ?>
											<?php $questions_test_answer_titles = json_decode($article['questions_test_answer_titles']); ?>

											<?php echo form_label('Answers', 'questions_test_answer_data'); ?>
											<?php echo '<span class="text-danger">' . session('errors.questions_test_answer_data') . '</span>'; ?>

											<hr />
											<div id="questions_test_answer_data_list-wrapper" class="questions_test_answer_data_list-wrapper">
												<div class="row">
													<?php $i = 1;
													foreach ($questions_test_answer_data as $question_test_answer_data) { ?>
														<div class="col-md-6">
															<div class="form-group">
																<?php echo form_input('questions_test_answer_titles[' . $i . '][title]', $questions_test_answer_titles->{$i}->title, 'id="questions_test_answer_titles_' . $i . '_title" class="form-control" placeholder="Title ' . $i . '"'); ?>
																<?php echo form_input('questions_test_answer_data[' . $i . '][answer]', $question_test_answer_data->answer, 'id="questions_test_answer_data_' . $i . '_question" class="form-control" placeholder="Answer ' . $i . '"'); ?>
															</div>
														</div>
													<?php $i++;
													} ?>
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
											echo form_dropdown('status', $options, $article['status'], 'class="form-control"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Created Date', 'created_date'); ?>
											<?php echo '<span class="text-danger">' . session('errors.created_date') . '</span>'; ?>

											<?php echo form_input('created_date', $article['created_date'], 'class="form-control readonly" readonly="readonly"'); ?>
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
											echo form_dropdown('created_by', $options, $article['created_by'], 'class="form-control"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php if ($article['updated_date'] == '0000-00-00 00:00:00') {
												$article['updated_date'] = date('Y-m-d H:i:s');
											} ?>
											<?php echo form_label('Updated Date', 'updated_date'); ?>
											<div class="input-group date datetimepicker-control">
												<?php echo form_input('updated_date', $article['updated_date'], 'class="form-control pull-right"'); ?>
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
											</div>
											<?php echo '<span class="text-danger">' . session('errors.updated_date') . '</span>'; ?>

										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('Updated By', 'updated_by'); ?>
											<?php echo '<span class="text-danger">' . session('errors.updated_by') . '</span>'; ?>

											<?php
											$options = array();
											foreach ($userlist as $user) {
												$options[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
											}

											echo form_dropdown('updated_by', $options, $article['updated_by'], 'class="form-control"');
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">

											<?php echo form_label('Published Date', 'published_date'); ?>
											<div class="input-group date datetimepicker-control">
												<?php echo form_input('published_date', $article['published_date'], 'class="form-control pull-right"'); ?>
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

											echo form_dropdown('published_by', $options, $article['published_by'], 'class="form-control"');
											?>
										</div>
									</div>
								</div>

								<hr />
								<div class="form-group">
									<?php echo form_label('Meta Title', 'meta_title'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_title_chars" class="text-primary">60</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_title') . '</span>'; ?>

									<?php echo form_input('meta_title', $article['meta_title'], 'id="meta_title" class="form-control" placeholder="Meta Title"'); ?>
								</div>
								<div class="form-group">
									<?php echo form_label('Meta Description', 'meta_description'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_description_chars" class="text-primary">300</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_description') . '</span>'; ?>

									<?php echo form_textarea('meta_description', $article['meta_description'], 'id="meta_description" class="form-control" placeholder="Meta Description"'); ?>
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

											<?php echo form_input('title_fr', $article['title_fr'], 'class="form-control"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php echo form_label('FR Alias', 'alias_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.alias_fr') . '</span>'; ?>

											<?php echo form_input('alias_fr', $article['alias_fr'], 'class="form-control"'); ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="article_layout_left_col" class="col-md-8 article_layout_left_col">
										<div id="article-description-field" class="form-group article-description-field">
											<div class="form-group">
												<?php echo form_label('FR Description', 'description_fr'); ?>
												<?php echo '<span class="text-danger">' . session('errors.description_fr') . '</span>'; ?>

												<?php echo form_textarea('description_fr', $article['description_fr'], 'class="textarea"'); ?>
											</div>
										</div>
									</div>
									<div id="article_layout_right_col" class="col-md-4 article_layout_right_col">

										<div id="article-image-field" class="form-group article-image-field">
											<?php echo form_label('FR Image', 'image_fr'); ?>
											<?php echo '<span class="text-danger">' . session('errors.image_fr') . '</span>'; ?>

											<div class="row">
												<div class="col-lg-9">
													<div class="input-group">
														<?php
														if ($article['image_fr'] != '') {
															$article_image = "<img src='" . site_url('assets/media/') . $article['image_fr'] . "' class='img-responsive' />";
														} else {
															$article_image = 'No image selected.';
														}
														?>
														<span class="input-group-btn">
															<a href="javascript:void(0)" data-toggle="popover" data-html="true" data-placement="top" data-trigger="hover" title="Selected Image" data-content="<?php echo $article_image; ?>" data-content="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'>Selected Image</h3><div class='popover-content'><?php echo $article_image; ?></div></div>" class="btn btn-default"><i class="fa fa-eye"></i></a>
														</span>
														<?php echo form_input('image_fr', $article['image_fr'], 'id="fieldImg_frID" class="form-control"'); ?>
														<span class="input-group-btn">
															<a href="<?php echo site_url('assets/administrator/'); ?>filemanager/dialog.php?type=1&amp;field_id=fieldImg_frID&amp;relative_url=1" class="btn btn-default iframe-btn" data-fancybox-type="iframe">Select</a>
														</span>
													</div><!-- /input-group -->
												</div>
												<?php if ($article['image_fr'] != '') { ?>
													<div class="col-lg-3">
														<img src='<?php echo site_url('assets/media/') . $article['image_fr']; ?>' class='icon_div fieldImg_frID' width="50" />
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>

								<hr />
								<div class="form-group">
									<?php echo form_label('Meta Title', 'meta_title_fr'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_title_fr_chars" class="text-primary">60</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_title_fr') . '</span>'; ?>

									<?php echo form_input('meta_title_fr', $article['meta_title_fr'], 'id="meta_title_fr" class="form-control" placeholder="Meta Title"'); ?>
								</div>
								<div class="form-group">
									<?php echo form_label('Meta Description', 'meta_description_fr'); ?>
									<p><label class="text-danger">Text Limit : <span id="meta_description_fr_chars" class="text-primary">300</span></label></p>
									<?php echo '<span class="text-danger">' . session('errors.meta_description') . '</span>'; ?>

									<?php echo form_textarea('meta_description_fr', $article['meta_description_fr'], 'id="meta_description_fr" class="form-control" placeholder="Meta Description"'); ?>
								</div>

							</div>
							<!--  DETAILS	[END]		-->

						</div>

					</div>
				</div>
				<!-- /.box-body -->

				<?php echo form_hidden('article_id', set_value('article_id', $article['id'])); ?>
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