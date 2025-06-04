<?php



?>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
		
		jQuery('#btn-reset-submit').click( function() {
			
			jQuery('#query').val('');
			jQuery('#main_category_id').val('');
			jQuery('#main_subcategory_ids').val('');
			jQuery('#search-form').submit();
			
		});
		
		//var main_category_id = jQuery("#main_category_id").val();
		<?php // if($main_category_id != '') {	?>
		//	getSubCategories(<?php echo $main_category_id; ?>);
		<?php // } else { ?>
		//	getSubCategories(main_category_id);
		<?php // } ?>
		jQuery("#main_category_id").on('change', function(e) {
			
			var main_category_id_data = jQuery(this).select2('data');
			console.log(main_category_id_data);
			var main_category_id = main_category_id_data[0].id;
			
			jQuery('#main_subcategory_ids').val(null).trigger('change');
			getSubCategories(main_category_id);
			
		});
		
		
		function getSubCategories(main_category_id) {
	    		    	
	    	// get the hash 
   			var csrf_test_name = jQuery("input[name=csrf_test_name]").val();
	    	jQuery.ajax({
				method: "POST",
				url: "<?php echo site_url('search/getsubcategorieslist'); ?>",
				data: { csrf_test_name: csrf_test_name, main_category_id: main_category_id },
				dataType: "json",
				beforeSend: function() {
					swal("Wait...", { closeOnClickOutside: false, buttons: false, timer: 500});
				}
			})
			.done(function( response ) {
				jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);
				swal.close();
				console.log(response.subcategories);				
				var options;
				jQuery.each(response.subcategories, function(i, subcategory) {
					options += '<option value="'+subcategory.id+'">'+subcategory.title+'</option>';
				});
				jQuery('#main_subcategory_ids').html(options).trigger('change');
			});
	    	
	    }
		
	});

</script>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
	
	<!--	SEARCH LAYOUT WRAPPER	[START]	  -->
	<div class="search-layout-wrapper search-page-layout-wrapper">
		<div class="container">
		
			<div class="searchpage-form-wrapper">
				<?php echo form_open('', 'class="form-inline search-form" id="search-form"'); ?>
				
				<div class="col-lg-9 col-md-10 center-col">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php 
									$data = array(
								        'type'          => 'text',
								        'name'          => 'query',
								        'id'            => 'query',
								        'value'         => $query,
								        'class'         => 'form-control search-query-input',
						        		'placeholder'   => 'صيحات جديدة'
									);
									echo form_input($data);
								?>
								<?php 							
									$data = array(
								        'name'          => 'btn-search-submit',
								        'id'            => 'btn-search-submit',
								        'class'         => 'btn btn-submit btn-search'
									);
									echo form_submit($data, 'Search');
								?>
								<?php 							
									$data = array(
								        'name'          => 'btn-reset-submit',
								        'id'            => 'btn-reset-submit',
								        'class'         => 'btn btn-submit btn-reset'
									);
									echo form_button($data, 'Reset');
								?>
							</div>
						</div>
						<div class="col-md-6">
							<?php 
								$options = array();
								if(!empty($categorieslist)) {
									$default = $main_category_id;
									$options[] = 'Select Category';
									foreach ($categorieslist AS $categorylist) {
										$options[$categorylist['id']] = $categorylist['title'];
									}
								} else {
									$default = '';
									$options[] = 'No Categories';
								}
								echo form_dropdown('main_category_id', $options, $default, 'id="main_category_id" class="form-control select2" data-placeholder="Select a Category"');
							?>
							<?php 
								$options = array();
								if(!empty($sub_categorieslist)) {
									$default = explode(',', $main_subcategory_ids);
									foreach ($sub_categorieslist AS $sub_categorylist) {
										$options[$sub_categorylist['id']] = $sub_categorylist['title'];
									}
								} else {
									$default = '';
									$options[] = 'No Categories';
								}
								echo form_dropdown('main_subcategory_ids[]', $options, $default, 'id="main_subcategory_ids" class="form-control select2" data-placeholder="Select a Sub Categories" multiple="multiple"');
							?>
						</div>
					</div>
				</div>
				
				<?php echo form_close(); ?>
			</div>
			<div class="searchpage-result-wrapper">
			
				<!--	CATEGORY CONTENT WRAPPER - SEARCH RESULT ARTICLE LIST WRAPPER	[START]	  -->
				<?php if(!empty($results['articles'])) { ?>
				<div class="category-content-wrapper search-resultarticlelist-wrapper">
					<div class="title-block">
						<h3 class="title">مقالات | <?php echo count($results['articles']); ?> نتائج</h3>
						<div class="sepertator"></div>
					</div>
					<div class="category-content-block">
						<div id="searchedarticles-content-row" class="row">
						<?php foreach ($results['articles'] AS $searched_article) { ?>
						<?php 
						// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
						$article_link = generateArticleLink($searched_article['category_ids'], $searched_article['alias']); 
						?>
						<?php 
						
						$category_ids = explode(',', $searched_article['category_ids']);
						$category_idsCnt = count($category_ids);
						if($category_idsCnt == 1) {
							$category_details = getCategoryDetails($category_ids[0]);
						} else {
							$category_details = getCategoryDetails($category_ids[1]);
						}
						
						if($category_details['parent_id'] != 0) {
							$parent_category_details = getParentCategoryDetails($category_details['id']);
						}
						
						$color_style = $category_details['color_style'];
						if($color_style == '') {
							$category_details['color_style'] = $parent_category_details['color_style'];
						}
																								
						?>
						<div class="col-md-4 content-block-col" data-mh="heightConsistancy">
							<div class="content-block">
								<div class="content-category" style="border-bottom: 1px solid <?php echo $category_details['color_style']; ?>;">
									<span style="background: <?php echo $category_details['color_style']; ?>;"><?php echo $category_details['title']; ?></span>
								</div>
								<div class="img-div">
									<a href="<?php echo $article_link; ?>" title="<?php echo $searched_article['title']; ?>">
										<?php 
										if($searched_article['image'] == '') {
											$searched_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										} else {
											$searched_article['image'] = 'assets/media/'.$searched_article['image'];
										}
										if($searched_article['article_layout'] == 'text_mulitplemedia' || $searched_article['article_layout'] == 'text_mulitplemedia_6' || $searched_article['article_layout'] == 'fashion') {
											$media_images = json_decode($searched_article['media_images']);
											if($media_images[$searched_article['media_image_main']] == '') {
												$searched_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
											} else {
												$searched_article['image'] = 'assets/media/'.$media_images[$searched_article['media_image_main']];
											}
										}
										
										$file_FCPath = FCPATH.urldecode($searched_article['image']);
										if(!file_exists($file_FCPath)) {
											$searched_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										}
										
										?>
										<img title="<?php echo $searched_article['title']; ?>" alt="<?php echo $searched_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo base_url().$searched_article['image']; ?>" class="lazyload img-responsive center-block"/>
									</a>
								</div>
								<div class="content-div">
									<h4><a href="<?php echo $article_link; ?>" title="<?php echo $searched_article['title']; ?>"><?php echo $searched_article['title']; ?></a></h4>
									<?php 
													
										$arabic_week_days = array(
											'Monday' => 'الإثنين', 
											'Tuesday' => 'الثلاثاء', 
											'Wednesday' => 'الأربعاء', 
											'Thursday' => 'الخميس', 
											'Friday' => 'الجمعه', 
											'Saturday' => 'السبت', 
											'Sunday' => 'الأحد'
										);
										$arabic_months = array(
											'January' => 'كانون الثاني', 
											'February' => 'شباط', 
											'March' => 'آذار', 
											'April' => 'نيسان', 
											'May' => 'أيار', 
											'June' => 'حزيران', 
											'July' => 'تموز', 
											'August' => 'آب', 
											'September' => 'أيلول', 
											'October' => 'تشرين الأول', 
											'November' => 'تشرين الثاني', 
											'December' => 'كانون الأول'
										);
										$day = date('l', strtotime($searched_article['published_date']));
										$month = date('F', strtotime($searched_article['published_date']));
										$date = date('d', strtotime($searched_article['published_date']));
										$year = date('Y', strtotime($searched_article['published_date']));
									
									?>
									<time><?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php if(count($results['articles']) >= 6) { ?>
						<div id="form-articleloadmore-div" class="form-loadmore-div form-articleloadmore-div text-center">
							<!-- form start -->
							<?php echo form_open('',array('id'=>'searchedarticles-list-form', 'class'=>'searchedarticles-list-form')); ?>
								<?php echo form_button('button_loadmore', 'المزيد', 'id="button_loadmore" class="btn btn-loadmore btn-red-border"');?>
								<?php echo form_hidden('query', $query);?>
								<?php echo form_hidden('main_category_id', $main_category_id);?>
								<?php echo form_hidden('main_subcategory_ids', $main_subcategory_ids);?>
								<?php echo form_hidden('limit_start', '6');?>
								<?php echo form_hidden('limit_end', '3');?>
							<?php echo form_close();?>
						</div>
					<?php } ?>
				</div>
				<?php } else { ?>
				<!--<div class="alert alert-warning">
					لا يوجد مقالات‎.
				</div>-->
				<?php } ?>
				<!--	CATEGORY CONTENT WRAPPER - SEARCH RESULT ARTICLE LIST WRAPPER	[END]	  -->
				
				
				<!--	VIDEO CONTENT WRAPPER -  SEARCH RESULT VIDEO LIST WRAPPER	[START]	  -->
				<?php if(!empty($results['videos'])) { ?>
				<div class="video-content-wrapper search-resultvideolist-wrapper">
					<div class="title-block">
						<!--<h3 class="title">الأحدث من عرسي</h3>-->
						<h3 class="title">النتائج</h3>
						<div class="sepertator"></div>
					</div>
					<div class="video-content-block">
						<div id="searchedvideos-content-row" class="row">
							<?php foreach ($results['videos'] AS $video) { ?>
							<div class="col-md-3" data-mh="heightConsistancy">
								<div class="content-block">
									<div class="img-div">
										<a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$video->id); ?>" title="<?php echo $video->title_ar; ?>">
											<img title="<?php echo $video->title_ar; ?>" alt="<?php echo $video->title_ar; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="http://admango.cdn.mangomolo.com/analytics/<?php echo $video->img; ?>" class="lazyload img-responsive center-block"/>
											<div class="overlay"></div>
										</a>
									</div>
									<div class="content-div">
										<h4><a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$video->id); ?>"><?php echo $video->title_ar; ?></a></h4>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
						
						<div id="form-searchedvideoloadmore-div" class="form-loadmore-div form-searchedvideoloadmore-div text-center">
							<!-- form start -->
							<?php echo form_open('',array('id'=>'searchedvideos-list-form', 'class'=>'searchedvideos-list-form')); ?>
								<?php echo form_button('button_searchedvideo_loadmore', 'المزيد', 'id="button_searchedvideo_loadmore" class="btn btn-loadmore btn-searchedvideo_loadmore btn-red-border"');?>
								<?php echo form_hidden('query', $query);?>
								<?php echo form_hidden('main_category_id', $main_category_id);?>
								<?php echo form_hidden('limit_start', '2');?>
								<?php echo form_hidden('limit_end', '4');?>
							<?php echo form_close();?>
						</div>
					</div>
				</div>
				<?php } else { ?>
				<!--<div class="alert alert-warning">
					لا فيديو.
				</div>-->
				<?php } ?>
				<!--	VIDEO CONTENT WRAPPER -  SEARCH RESULT VIDEO LIST WRAPPER	[END]	  -->
				
				
			</div>
			
		
		</div>
	</div>
	<!--	SEARCH LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->


<script src="<?php echo base_url(); ?>assets/site/js/searchedarticlesvideos_view.js"></script>