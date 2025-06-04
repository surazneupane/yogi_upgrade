<?php



?>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
			
	<!--	SUBCATEGORIES LAYOUT WRAPPER	[START]	  -->
	<div class="subcategories-layout-wrapper subcategoriespage-layout-wrapper" itemscope itemtype="https://schema.org/Blog">
		<div class="container">
		
			<div class="category-content-wrapper articles-content-wrapper">
				<div class="title-block">
					<h3 class="title" itemprop="name"><?php echo $category['title']; ?></h3>
					<div class="sepertator"></div>
				</div>
				<div class="category-content-block">
				
					<?php if(!empty($category_articles)) { ?>
					<div id="articles-content-row" class="row">
						<?php foreach ($category_articles AS $category_article) { ?>
						<?php 
						// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
						$article_link = generateArticleLink($category_article['category_ids'], $category_article['alias']); 
						?>
						<?php 
						
						$category_ids = explode(',', $category_article['category_ids']);
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
									<a href="<?php echo $article_link; ?>" title="<?php echo $category_article['title']; ?>">
										<?php 
										if($category_article['image'] == '') {
											$category_article['image'] = 'assets/site/images/nav-logo.png';
										} else {
											$category_article['image'] = 'assets/media/'.$category_article['image'];
										}
										if($category_article['article_layout'] == 'text_mulitplemedia' || $category_article['article_layout'] == 'text_mulitplemedia_6' || $category_article['article_layout'] == 'fashion') {
											$media_images = json_decode($category_article['media_images']);
											if($media_images[$category_article['media_image_main']] == '') {
												$category_article['image'] = 'assets/site/images/nav-logo.png';
											} else {
												$category_article['image'] = 'assets/media/'.$media_images[$category_article['media_image_main']];
											}
										}
										
										$file_FCPath = FCPATH.urldecode($category_article['image']);
										if(!file_exists($file_FCPath)) {
											$category_article['image'] = 'assets/site/images/nav-logo.png';
										}
										
										?>
										<img title="<?php echo $category_article['title']; ?>" alt="<?php echo $category_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/nav-logo.png" data-src="<?php echo base_url().$category_article['image']; ?>" class="lazyload img-responsive center-block"/>
									</a>
								</div>
								<div class="content-div">
									<h4><a href="<?php echo $article_link; ?>" title="<?php echo $category_article['title']; ?>"><?php echo $category_article['title']; ?></a></h4>
									<?php if($category_article['description'] != '' || $category_article['article_layout'] != 'media') { ?>
										<?php 
										$description = strip_tags($category_article['description']);
										$description_string_count = mb_strlen($description);
										if($description_string_count > 160) {
											$description = mb_substr($description, 0, 157).'...';
										}
										?>
										<p><?php echo $description; ?></p>
									<?php } ?>
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
										$day = date('l', strtotime($category_article['published_date']));
										$month = date('F', strtotime($category_article['published_date']));
										$date = date('d', strtotime($category_article['published_date']));
										$year = date('Y', strtotime($category_article['published_date']));
									
									?>
									<time><?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php if(count($category_articles) >= 6) { ?>
						<div id="form-loadmore-div" class="form-loadmore-div">
							<!-- form start -->
							<?php echo form_open('',array('id'=>'articles-list-form', 'class'=>'articles-list-form')); ?>
								<?php echo form_hidden('category_alias', $category_alias);?>
								<?php echo form_hidden('subcategory_alias', $subcategory_alias);?>
								<?php echo form_hidden('limit_start', '6');?>
								<?php echo form_hidden('limit_end', '3');?>
							<?php echo form_close();?>
						</div>
					<?php } ?>
					<?php } else { ?>
					<div class="alert alert-warning">
						لا يوجد مقالات‎.
					</div>
					<?php } ?>
				
				</div>
			</div>
			
		</div>
	</div>
	<!--	SUBCATEGORIES LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->

<script src="<?php echo base_url(); ?>assets/site/js/articles_view.js"></script>