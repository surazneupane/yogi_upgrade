<?php



?>


<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
	
	<!--	VIDEOS TV PAGE WRAPPER	[START]	  -->
	<div class="video-content-wrapper videos-tvpage-wrapper">
	
		<div class="container">
			
			<div class="featuredvideos-carousel-wrapper">
				
				<?php if(!empty($featured_videos)) { ?>
				<div id="featuredvideos-carousel" class="featuredvideos-carousel owl-carousel">
					<?php foreach ($featured_videos AS $featured_video) { ?>
					<div class="item">
						<div class="img-div">
							<a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$featured_video->id); ?>" title="<?php echo $featured_video->title_ar; ?>">
								<img title="<?php echo $featured_video->title_ar; ?>" alt="<?php echo $featured_video->title_ar; ?>" src="http://admango.cdn.mangomolo.com/analytics/<?php echo $featured_video->img; ?>" class="img-responsive center-block"/>
								<div class="overlay"></div>
							</a>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
			</div>
			
			<div class="videocategories-latestvideos-wrapper">
								
				<div class="title-block">
					<h3 class="title">مقالاتي المفضلة</h3>
					<div class="sepertator"></div>
					<div class="video-category-div">
						<!-- form start -->
						<?php echo form_open('',array('id'=>'categoryvideos-list-form', 'class'=>'categoryvideos-list-form')); ?>
							<?php 
								$options = array();
								if(!empty($video_categories)) {
									$default = $current_video_category_id;
									foreach ($video_categories AS $video_category) {
										if($video_category->title_en != '' && $video_category->title_en != $video_category->title_ar) {
											$options[$video_category->id] = $video_category->title_ar.' ('.$video_category->title_en.')';
										} else {
											$options[$video_category->id] = $video_category->title_ar;
										}
									}
								} else {
									$default = '';
									$options[] = 'No Categories';
								}
								echo form_dropdown('video_category_id', $options, $default, 'id="video_category_id" class="form-control select2" data-placeholder="Select a Category" readonly="readonly"');
							?>
							<?php echo form_hidden('cat_id', $current_video_category_id);?>
							<?php echo form_hidden('page', 4);?>
							<?php echo form_hidden('limit', 4);?>
						
						<?php echo form_close();?>
					</div>
				</div>
				<div class="video-content-block">
					<?php if(!empty($category_videos)) { ?>
						<div id="categoryvideos-content-row" class="row">
							<?php foreach ($category_videos AS $category_video) { ?>
								<div class="col-md-3 col-sm-4 content-block-col" data-mh="heightConsistancy">
									<div class="content-block">
										<div class="img-div">
											<a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$category_video->id); ?>" title="<?php echo $category_video->title_ar; ?>">
												<img title="<?php echo $category_video->title_ar; ?>" alt="<?php echo $category_video->title_ar; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="http://admango.cdn.mangomolo.com/analytics/<?php echo $category_video->img; ?>" class="lazyload img-responsive center-block"/>
												<div class="overlay"></div>
												<div class="overlay-2"></div>
												<div class="overlay-text"><?php echo $category_video->title_ar; ?></div>
											</a>
										</div>
										<div class="content-div">
											<h4><a href="<?php echo site_url('yawmiyati-tv/فيديو/'.$category_video->id); ?>" title="<?php echo $category_video->title_ar; ?>"><?php echo $category_video->title_ar; ?></a></h4>
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
									
			</div>
		
		</div>
		
	</div>
	<!--	VIDEOS TV PAGE WRAPPER	[END]	  -->
	
</div>
<!--	CONTENT WRAPPER	[END]	  -->

<script src="<?php echo base_url(); ?>assets/site/js/yawmiyatitv_view.js"></script>