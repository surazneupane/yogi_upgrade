<?php



?>


<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
	
	<!--	VIDEOS TV PAGE WRAPPER	[START]	  -->
	<div class="articledetails-layout-wrapper videodetails-layout-wrapper">
	
		<div class="container">
		
			<!--	BREADCRUMBS WRAPPER	[START]	  -->
			<?php $this->data['data'] = ''; ?>
			<?php $this->load->view('breadcrumbs/index_view', $this->data); ?>
			<!--	BREADCRUMBS WRAPPER	[END]	  -->
			
			
			<div class="row">
			
				<!--				-->
				<div class="col-md-9 articledetails-content-right-col videodetails-content-right-col">
									
					<!--		ARTICLE DETAIL CONTAINER   [START]			-->
					<div class="articledetails-content-container">
										
						<div class="title-block">
							<h1 class="title"><?php echo $video_details->video->title_ar; ?></h1>
							<div class="sepertator"></div>
						</div>
					
						<div class="articledetails-content-layout-container videodetails-content-layout-container">
						
							<div class="mangomolo-video-player-wrapper">
								<div class="embed-responsive embed-responsive-16by9">
									<iframe class="embed-responsive-item" src="<?php echo $video_details->embed; ?>"></iframe>
								</div>
							</div>
							
							<div class="articlepage-content-characteristic-wrapper">
								<div class="row">
									<div class="col-md-6 col-sm-6 articlepage-content-characteristic-right-col">
										<div class="sharearticle-div">
											<div class="sharebtn twittershare" onclick="tweetPopup('<?php echo current_url(); ?>')">            
									            <span class="fab fa-twitter"></span>
									        </div>
									        <div class="sharebtn pinterestshare" onclick="pinterestShare('<?php echo urldecode(current_url()); ?>')">
									            <span class="fab fa-pinterest-p"></span>
									        </div>
											<div class="sharebtn facebookshare" onclick="facebookShare('<?php echo urldecode(current_url()); ?>')">
									            <!--<span class="sharecount"></span>-->
									            <span class="fab fa-facebook-f"></span>
									        </div>
									        <div class="sharebtn gplusshare" onclick="googlePlusShare('<?php echo urldecode(current_url()); ?>')">
									            <span class="fab fa-google-plus-g"></span>
									        </div>
										</div>
									</div>	
								</div>	
							</div>
							
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
								$day = date('l', strtotime($video_details->video->create_time));
								$month = date('F', strtotime($video_details->video->create_time));
								$date = date('d', strtotime($video_details->video->create_time));
								$year = date('Y', strtotime($video_details->video->create_time));
							
							?>
							
							<time class="article-created_date"><?php echo date('h:i A', strtotime($video_details->video->create_time)); ?> | <?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
							
							<div class="article-description-div">
								<?php echo $video_details->video->description_ar; ?>
							</div>
						
						</div>
						
					</div>
					<!--		ARTICLE DETAIL CONTAINER   [END]			-->
					
					
					<!--	LATEST ARTICLES CONTENT WRAPPER	[START]	  -->
					<div class="category-content-wrapper latest-articles-content-wrapper">
						<div class="title-block">
							<h3 class="title">أحدث المقالات</h3>
							<div class="sepertator"></div>
						</div>
						<div class="category-content-block">
							<?php if(!empty($latest_articles)) { ?>
								<div class="row">
									<?php foreach ($latest_articles AS $latest_article) { ?>
									<?php 
									// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
									$article_link = generateArticleLink($latest_article['category_ids'], $latest_article['alias']); 
									?>
									<?php 
									
									$category_ids = explode(',', $latest_article['category_ids']);
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
									<div class="col-md-6 col-sm-6 content-block-col" data-mh="heightConsistancy">
										<div class="content-block">
											<div class="content-category" style="border-bottom: 1px solid <?php echo $category_details['color_style']; ?>;">
												<span style="background: <?php echo $category_details['color_style']; ?>;"><?php echo $category_details['title']; ?></span>
											</div>
											<div class="img-div">
												<!--<div class="read-count">
													<span><?php // echo $latest_article['hits']; ?></span>
												</div>-->
												<a href="<?php echo $article_link; ?>" title="<?php echo $latest_article['title']; ?>">
													<?php 
													if($latest_article['image'] == '') {
														$latest_article['image'] = 'assets/site/images/yawmiyati-default.jpg';	
													} else {
														$latest_article['image'] = 'assets/media/'.$latest_article['image'];
													}
													if($latest_article['article_layout'] == 'text_mulitplemedia' || $latest_article['article_layout'] == 'text_mulitplemedia_6' || $latest_article['article_layout'] == 'fashion') {
														$media_images = json_decode($latest_article['media_images']);
														if($media_images[$latest_article['media_image_main']] == '') {
															$latest_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
														} else {
															$latest_article['image'] = 'assets/media/'.$media_images[$latest_article['media_image_main']];
														}
													}
													
													$file_FCPath = FCPATH.$latest_article['image'];
													if(!file_exists($file_FCPath)) {
														$latest_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
													}
													
													?>
													<img title="<?php echo $latest_article['title']; ?>" alt="<?php echo $latest_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo base_url().$latest_article['image']; ?>" class="lazyload img-responsive center-block"/>
												</a>
											</div>
											<div class="content-div">
												<h4><a href="<?php echo $article_link; ?>" title="<?php echo $latest_article['title']; ?>"><?php echo $latest_article['title']; ?></a></h4>
												<?php //if($latest_article['description'] != '' || $latest_article['article_layout'] != 'media') { ?>
												<?php 
													//$description = strip_tags($latest_article['description']);
													//$description_string_count = mb_strlen($description);
													//if($description_string_count > 160) {
													//	$description = mb_substr($description, 0, 157).'...';
													//}
													?>
													<!--<p><?php //echo $description; ?></p>-->
												<?php //} ?>
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
												$day = date('l', strtotime($latest_article['created_date']));
												$month = date('F', strtotime($latest_article['created_date']));
												$date = date('d', strtotime($latest_article['created_date']));
												$year = date('Y', strtotime($latest_article['created_date']));
												
												?>
												<time><?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							<?php } else { ?>
								<div class="alert alert-warning">
									لا يوجد مقالات‎.
								</div>
							<?php } ?>
						</div>
					</div>
					<!--	MOSTREAD CONTENT WRAPPER	[END]	  -->
					
				</div>
				
				<!--				-->
				<div class="col-md-3 articledetails-content-left-col videodetails-content-left-col">
					
					<!--	MPU WRAPPER	[START]	  -->
					<?php $this->data['data'] = ''; ?>
					<?php $this->load->view('ads/mpu', $this->data); ?>
					<!--	MPU WRAPPER	[END]	  -->
					
					
					<!--	RELATEDVIDEOS WRAPPER	[START]	  -->
					<div class="video-content-wrapper featuredvideos-content-wrapper">
						<div class="title-block">
							<h3 class="title">فيديوهات ذات علاقة</h3>
							<div class="sepertator"></div>
							<?php if(!empty($related_videos)) { ?>
							<a href="<?php echo site_url('yawmiyati-tv/'.$related_videos_categoryid); ?>" class="more-link">المزيد</a>
							<?php } ?>
						</div>
						<div class="video-content-block">
							<?php if(!empty($related_videos)) { ?>
								<div class="row">
									<?php foreach ($related_videos AS $related_video) { ?>
									<?php if ($related_video->id != $video_details->video->id) { ?>
									<div class="col-md-12" data-mh="heightConsistancy">
										<div class="content-block">
											<!--<div class="video-category" style="border-bottom: 1px solid #6e2585;">
												<span style="background: #6e2585;">عرسي</span>
											</div>-->
											<div class="img-div">
												<a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$related_video->id); ?>" title="<?php echo $related_video->title_ar; ?>">
													<img alt="<?php echo $related_video->title_ar; ?>" title="<?php echo $related_video->title_ar; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="http://admango.cdn.mangomolo.com/analytics/<?php echo $related_video->img; ?>" class="lazyload img-responsive center-block"/>
													<div class="overlay"></div>
												</a>
											</div>
											<div class="content-div">
												<h4><a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$related_video->id); ?>" title="<?php echo $related_video->title_ar; ?>"><?php echo $related_video->title_ar; ?></a></h4>
											</div>
										</div>
									</div>
									<?php } ?>
									<?php } ?>
								</div>
							<?php } else { ?>
								<div class="alert alert-warning">
									لا فيديو.
								</div>
							<?php } ?>
						</div>
					</div>
					<!--	RELATEDVIDEOS WRAPPER	[END]	  -->
					
				</div>
				<!--				-->
				
			</div>
			
			<!--	MOSTREAD CONTENT WRAPPER	[START]	  -->
			<div class="category-content-wrapper mostread-content-wrapper">
				<div class="title-block">
					<h3 class="title">الأكثر قراءة</h3>
					<div class="sepertator"></div>
					<?php if(!empty($mostread_articles)) { ?>
					<a href="<?php echo site_url('أكثر-المقالات-قراءة/'); ?>" title="المزيد" class="more-link">المزيد</a>
					<?php } ?>
				</div>
				<div class="category-content-block">
					<?php if(!empty($mostread_articles)) { ?>
						<div class="row">
							<?php foreach ($mostread_articles AS $mostread_article) { ?>
							<?php 
							// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
							$article_link = generateArticleLink($mostread_article['category_ids'], $mostread_article['alias']); 
							?>
							<?php 
							
							$category_ids = explode(',', $mostread_article['category_ids']);
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
										<div class="read-count">
											<span><?php echo $mostread_article['hits']; ?></span>
										</div>
										<a href="<?php echo $article_link; ?>" title="<?php echo $mostread_article['title']; ?>">
											<?php 
											if($mostread_article['image'] == '') {
												$mostread_article['image'] = 'assets/site/images/yawmiyati-default.jpg';	
											} else {
												$mostread_article['image'] = 'assets/media/'.$mostread_article['image'];
											}
											if($mostread_article['article_layout'] == 'text_mulitplemedia' || $mostread_article['article_layout'] == 'text_mulitplemedia_6' || $mostread_article['article_layout'] == 'fashion') {
												$media_images = json_decode($mostread_article['media_images']);
												if($media_images[$mostread_article['media_image_main']] == '') {
													$mostread_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
												} else {
													$mostread_article['image'] = 'assets/media/'.$media_images[$mostread_article['media_image_main']];
												}
											}
											
											$file_FCPath = FCPATH.$mostread_article['image'];
											if(!file_exists($file_FCPath)) {
												$mostread_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
											}
											
											?>
											<img title="<?php echo $mostread_article['title']; ?>" alt="<?php echo $mostread_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="<?php echo base_url().$mostread_article['image']; ?>" class="lazyload img-responsive center-block"/>
										</a>
									</div>
									<div class="content-div">
										<h4><a href="<?php echo $article_link; ?>" title="<?php echo $mostread_article['title']; ?>"><?php echo $mostread_article['title']; ?></a></h4>
										<?php if($mostread_article['description'] != '' || $mostread_article['article_layout'] != 'media') { ?>
										<?php 
											$description = strip_tags($mostread_article['description']);
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
										$day = date('l', strtotime($mostread_article['created_date']));
										$month = date('F', strtotime($mostread_article['created_date']));
										$date = date('d', strtotime($mostread_article['created_date']));
										$year = date('Y', strtotime($mostread_article['created_date']));
										
										?>
										<time><?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					<?php } else { ?>
						<div class="alert alert-warning">
							لا يوجد مقالات‎.
						</div>
					<?php } ?>
				</div>
			</div>
			<!--	MOSTREAD CONTENT WRAPPER	[END]	  -->
			
		
		</div>
		
	</div>
	<!--	VIDEOS TV PAGE WRAPPER	[END]	  -->
	
</div>
<!--	CONTENT WRAPPER	[END]	  -->

<script src="<?php echo base_url(); ?>assets/site/js/videos_index_view.js"></script>