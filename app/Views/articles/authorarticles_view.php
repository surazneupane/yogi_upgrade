<?php



?>


<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
			
	<!--	SUBCATEGORIES LAYOUT WRAPPER	[START]	  -->
	<div class="subcategories-layout-wrapper authorarticlespage-layout-wrapper" itemscope itemtype="https://schema.org/Blog">
		<div class="container">

			<div class="category-content-wrapper authorarticles-content-wrapper">
			
				<!--	BREADCRUMBS WRAPPER	[START]	  -->
				<?php $this->data['data'] = ''; ?>
				<?php $this->load->view('breadcrumbs/index_view', $this->data); ?>
				<!--	BREADCRUMBS WRAPPER	[END]	  -->
			
				<div class="row">
					<div class="col-md-9 authorarticles-content-right-col">
						
						<div class="title-block">
							<h3 class="title" itemprop="name">جميع مقالات الكاتب</h3>
							<div class="sepertator"></div>
						</div>
						
						<div class="author-data-row row">
							<div class="col-md-3 col-sm-3 author-pic-col">
								<?php if ($author_details['photo'] != '') { ?>
									<img src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo site_url('assets/media/'.$author_details['photo']); ?>" class="lazyload author-photo img-responsive" alt="<?php echo $author_details['first_name'].' '.$author_details['last_name']; ?>" title="<?php echo $author_details['first_name'].' '.$author_details['last_name']; ?>">
								<?php } else  { ?>
									<img src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo site_url('assets/administrator/source/no_user.png'); ?>" class="lazyload author-photo img-responsive" alt="<?php echo $author_details['first_name'].' '.$author_details['last_name']; ?>" title="<?php echo $author_details['first_name'].' '.$author_details['last_name']; ?>">
								<?php } ?>
							</div>
							<div class="col-md-9 col-sm-9 author-text-col">
								<h4><?php echo $author_details['first_name'].' '.$author_details['last_name']; ?></h4>
								<?php if($author_details['description'] != '') { ?>
								<div class="author-description">
									<?php echo $author_details['description']; ?>
								</div>
								<?php } ?>
								<div class="sharearticle-div">
									<?php if($author_details['twitter_link'] != '') { ?>
										<a href="<?php echo $author_details['twitter_link']; ?>" target="_blank" class="sharebtn twittershare">
								            <span class="fab fa-twitter"></span>
								        </a>
							        <?php } ?>
							        <!--<a href="#" target="_blank" class="sharebtn pinterestshare">
							            <span class="fab fa-pinterest-p"></span>
							        </a>-->
							        <?php if($author_details['facebook_link'] != '') { ?>
										<a href="<?php echo $author_details['facebook_link']; ?>" target="_blank" class="sharebtn facebookshare">
								            <span class="fab fa-facebook-f"></span>
								        </a>
							        <?php } ?>
							        <?php if($author_details['instagram_link'] != '') { ?>
								        <a href="<?php echo $author_details['instagram_link']; ?>" target="_blank" class="sharebtn instagramshare">
								            <span class="fab fa-instagram"></span>
								        </a>
							        <?php } ?>
							        <?php if($author_details['website_link'] != '') { ?>
								        <a href="<?php echo $author_details['website_link']; ?>" target="_blank" class="sharebtn websiteshare">
								            <span class="fas fa-globe"></span>
								        </a>
							        <?php } ?>
								</div>
							</div>
						</div>
					
					</div>
					<div class="col-md-3 authorarticles-content-left-col">
					
						<!--	MPU WRAPPER	[START]	  -->
						<?php $this->data['data'] = ''; ?>
						<?php $this->load->view('ads/mpu', $this->data); ?>
						<!--	MPU WRAPPER	[END]	  -->
						
					</div>
				</div>
				
				<div class="category-content-block">
				
					<div class="title-block">
						<h3 class="title">آخر المقالات</h3>
						<div class="sepertator"></div>
					</div>
				
					<?php if(!empty($author_articles)) { ?>
					<div id="authorarticles-content-row" class="row">
						<?php foreach ($author_articles AS $author_article) { ?>
						<?php 
						// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
						$article_link = generateArticleLink($author_article['category_ids'], $author_article['alias']); 
						?>
						<?php 
						
						$category_ids = explode(',', $author_article['category_ids']);
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
									<a href="<?php echo $article_link; ?>" title="<?php echo $author_article['title']; ?>">
										<?php 
										if($author_article['image'] == '') {
											$author_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										} else {
											$author_article['image'] = 'assets/media/'.$author_article['image'];
										}
										if($author_article['article_layout'] == 'text_mulitplemedia' || $author_article['article_layout'] == 'text_mulitplemedia_6' || $author_article['article_layout'] == 'fashion') {
											$media_images = json_decode($author_article['media_images']);
											if($media_images[$author_article['media_image_main']] == '') {
												$author_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
											} else {
												$author_article['image'] = 'assets/media/'.$media_images[$author_article['media_image_main']];
											}
										}
										
										$file_FCPath = FCPATH.urldecode($author_article['image']);
										if(!file_exists($file_FCPath)) {
											$author_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										}
										
										?>
										<img title="<?php echo $author_article['title']; ?>" alt="<?php echo $author_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo base_url().$author_article['image']; ?>" class="lazyload img-responsive center-block"/>
									</a>
								</div>
								<div class="content-div">
									<h4><a href="<?php echo $article_link; ?>" title="<?php echo $author_article['title']; ?>"><?php echo $author_article['title']; ?></a></h4>
									<?php if($author_article['description'] != '' || $author_article['article_layout'] != 'media') { ?>
										<?php 
										$description = strip_tags($author_article['description']);
										$description_string_count = mb_strlen($description);
										if($description_string_count > 155) {
											$description = mb_substr($description, 0, 150).'...';
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
										$day = date('l', strtotime($author_article['published_date']));
										$month = date('F', strtotime($author_article['published_date']));
										$date = date('d', strtotime($author_article['published_date']));
										$year = date('Y', strtotime($author_article['published_date']));
									
									?>
									<time><?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php if(count($author_articles) >= 6) { ?>
						<div id="form-articleloadmore-div" class="form-loadmore-div form-articleloadmore-div text-center">
							<!-- form start -->
							<?php echo form_open('',array('id'=>'authorarticles-list-form', 'class'=>'authorarticles-list-form')); ?>
								<?php echo form_button('button_loadmore', 'المزيد', 'id="button_loadmore" class="btn btn-loadmore btn-red-border"');?>
								<?php echo form_hidden('author', $author);?>
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
				
				
				
				<!--	LATEST VIDEOS WRAPPER	[START]	  -->
				<div class="video-content-wrapper latestvideos-content-wrapper">
					<div class="title-block">
						<h3 class="title">آخر الفيديوهات</h3>
						<div class="sepertator"></div>
					</div>
					<div class="video-content-block">
						<?php if(!empty($latest_videos)) { ?>
						<div id="latestvideos-content-row" class="row">
							<?php foreach ($latest_videos AS $latest_video) { ?>
							<div class="col-md-3" data-mh="heightConsistancy">
								<div class="content-block">
									<div class="img-div">
										<a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$latest_video->id); ?>" title="<?php echo $latest_video->title_ar; ?>">
											<img title="<?php echo $latest_video->title_ar; ?>" alt="<?php echo $latest_video->title_ar; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="http://admango.cdn.mangomolo.com/analytics/<?php echo $latest_video->img; ?>" class="lazyload img-responsive center-block"/>
											<div class="overlay"></div>
										</a>
									</div>
									<div class="content-div">
										<h4><a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$latest_video->id); ?>" title="<?php echo $latest_video->title_ar; ?>"><?php echo $latest_video->title_ar; ?></a></h4>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
						<?php } else { ?>
						<div class="alert alert-warning">
							لا فيديو.
						</div>
						<?php } ?>
					</div>
					
					<div id="form-latestvideoloadmore-div" class="form-loadmore-div form-latestvideoloadmore-div text-center">
						<!-- form start -->
						<?php echo form_open('',array('id'=>'latestvideos-list-form', 'class'=>'latestvideos-list-form')); ?>
							<?php echo form_button('button_latestvideo_loadmore', 'المزيد', 'id="button_latestvideo_loadmore" class="btn btn-loadmore btn-latestvideo_loadmore btn-red-border"');?>
							<?php echo form_hidden('limit_start', '4');?>
							<?php echo form_hidden('limit_end', '8');?>
						<?php echo form_close();?>
					</div>
					
				</div>
				<!--	LATEST VIDEOS WRAPPER	[END]	  -->
				
				
			</div>
			
		</div>
	</div>
	<!--	SUBCATEGORIES LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->

<script src="<?php echo base_url(); ?>assets/site/js/authorarticles_view.js"></script>