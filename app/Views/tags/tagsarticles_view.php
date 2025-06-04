<?php



?>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
			
	<!--	SUBCATEGORIES LAYOUT WRAPPER	[START]	  -->
	<div class="subcategories-layout-wrapper tagarticlespage-layout-wrapper" itemscope itemtype="https://schema.org/Blog">
		<div class="container">

			<div class="category-content-wrapper tagarticles-content-wrapper">
				<div class="title-block">
					<h1 class="title" itemprop="name"><?php echo $tag_detail['title']; ?></h1>
					<div class="sepertator"></div>
				</div>
				
				<div class="tag-data-row row">
					<div class="col-md-3 col-sm-3 tag-pic-col">
						<?php if ($tag_detail['image'] != '') { ?>
							<img src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo site_url('assets/media/'.$tag_detail['image']); ?>" class="lazyload tag-photo img-responsive" alt="<?php echo $tag_detail['title']; ?>" title="<?php echo $tag_detail['title']; ?>">
						<?php } else  { ?>
							<img src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo site_url('assets/administrator/source/yawmiyati-default.jpg'); ?>" class="lazyload author-photo img-responsive" title="<?php echo $tag_detail['title']; ?>" alt="<?php echo $tag_detail['title']; ?>">
						<?php } ?>
					</div>
					<div class="col-md-9 col-sm-9 tag-text-col">
						<?php if($tag_detail['description'] != '') { ?>
						<div class="author-description">
							<?php echo $tag_detail['description']; ?>
						</div>
						<?php } ?>
						<div class="sharearticle-div">
							<?php if($tag_detail['twitter_link'] != '') { ?>
								<a href="<?php echo $tag_detail['twitter_link']; ?>" target="_blank" class="sharebtn twittershare">
						            <span class="fab fa-twitter"></span>
						        </a>
					        <?php } ?>
					        <?php if($tag_detail['facebook_link'] != '') { ?>
								<a href="<?php echo $tag_detail['facebook_link']; ?>" target="_blank" class="sharebtn facebookshare">
						            <span class="fab fa-facebook-f"></span>
						        </a>
					        <?php } ?>
					        <?php if($tag_detail['instagram_link'] != '') { ?>
						        <a href="<?php echo $tag_detail['instagram_link']; ?>" target="_blank" class="sharebtn instagramshare">
						            <span class="fab fa-instagram"></span>
						        </a>
					        <?php } ?>
					        <?php if($tag_detail['website_link'] != '') { ?>
						        <a href="<?php echo $tag_detail['website_link']; ?>" target="_blank" class="sharebtn websiteshare">
						            <span class="fas fa-globe"></span>
						        </a>
					        <?php } ?>
						</div>
					</div>
				</div>
				
				<hr/>
				
				<div class="category-content-block">
				
					<?php if(!empty($tag_articles)) { ?>
					<div id="tagarticles-content-row" class="row">
						<?php foreach ($tag_articles AS $tag_article) { ?>
						<?php 
						// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
						$article_link = generateArticleLink($tag_article['category_ids'], $tag_article['alias']); 
						?>
						<?php 
						
						$category_ids = explode(',', $tag_article['category_ids']);
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
									<a href="<?php echo $article_link; ?>" title="<?php echo $tag_article['title']; ?>">
										<?php 
										if($tag_article['image'] == '') {
											$tag_article['image'] = 'assets/site/images/yawmiyati-default.jpg';	
										} else {
											$tag_article['image'] = 'assets/media/'.$tag_article['image'];
										}
										if($tag_article['article_layout'] == 'text_mulitplemedia' || $tag_article['article_layout'] == 'text_mulitplemedia_6') {
											$media_images = json_decode($tag_article['media_images']);
											if($media_images[$tag_article['media_image_main']] == '') {
												$tag_article['image'] = 'assets/site/images/yawmiyati-default.jpg';	
											} else {
												$tag_article['image'] = 'assets/media/'.$media_images[$tag_article['media_image_main']];
											}
										}
										
										$file_FCPath = FCPATH.urldecode($tag_article['image']);
										if(!file_exists($file_FCPath)) {
											$tag_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										}
										
										?>
										<img title="<?php echo $tag_article['title']; ?>" alt="<?php echo $tag_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo base_url().$tag_article['image']; ?>" class="lazyload img-responsive center-block"/>
									</a>
								</div>
								<div class="content-div">
									<h4><a href="<?php echo $article_link; ?>" title="<?php echo $tag_article['title']; ?>"><?php echo $tag_article['title']; ?></a></h4>
									<?php if($tag_article['description'] != '' || $tag_article['article_layout'] != 'media') { ?>
										<?php 
										$description = strip_tags($tag_article['description']);
										$description_string_count = mb_strlen($description);
										if($description_string_count > 153) {
											$description = mb_substr($description, 0, 153).'...';
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
										$day = date('l', strtotime($tag_article['published_date']));
										$month = date('F', strtotime($tag_article['published_date']));
										$date = date('d', strtotime($tag_article['published_date']));
										$year = date('Y', strtotime($tag_article['published_date']));
									
									?>
									<time><?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php if(count($tag_articles) >= 6) { ?>
						<div id="form-loadmore-div" class="form-loadmore-div">
							<!-- form start -->
							<?php echo form_open('',array('id'=>'tagarticles-list-form', 'class'=>'tagarticles-list-form')); ?>
								<?php echo form_hidden('tag_alias', $tag_alias);?>
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

<script src="<?php echo base_url(); ?>assets/site/js/tagarticles_view.js"></script>