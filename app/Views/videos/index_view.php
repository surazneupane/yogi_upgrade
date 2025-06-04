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
			
			<?php foreach ($videosindex_data AS $videoindex_data) { ?>
			<div class="videocategories-latestvideos-wrapper">

				<div class="title-block">
					<h3 class="title"><?php echo $videoindex_data['category']->title_ar; ?></h3>
					<div class="sepertator"></div>
					<a href="<?php echo site_url('فيديوهات/'.$videoindex_data['category']->id); ?>" title="<?php echo $videoindex_data['category']->title_ar; ?>" class="more-link">المزيد</a>
				</div>
				<div class="video-content-block">
					<?php if(!empty($videoindex_data['videos'])) { ?>
						<div id="categoryvideos-content-row" class="row">
							<?php foreach ($videoindex_data['videos'] AS $category_video) { ?>
								<div class="col-md-3 col-sm-4 content-block-col" data-mh="heightConsistancy">
									<div class="content-block">
										<div class="img-div">
											<a href="<?php echo site_url('فيديوهات/فيديو/'.$category_video->id); ?>" title="<?php echo $category_video->title_ar; ?>">
												<img title="<?php echo $category_video->title_ar; ?>" alt="<?php echo $category_video->title_ar; ?>" src="<?php echo base_url(); ?>assets/site/images/yawmiyati-default.jpg" data-src="http://admango.cdn.mangomolo.com/analytics/<?php echo $category_video->img; ?>" class="lazyload img-responsive center-block"/>
												<div class="overlay"></div>
												<div class="overlay-2"></div>
												<div class="overlay-text"><?php echo $category_video->title_ar; ?></div>
											</a>
										</div>
										<div class="content-div">
											<h4><a href="<?php echo site_url('فيديوهات/فيديو/'.$category_video->id); ?>" title="<?php echo $category_video->title_ar; ?>"><?php echo $category_video->title_ar; ?></a></h4>
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
			<?php } ?>
		
		</div>
		
	</div>
	<!--	VIDEOS TV PAGE WRAPPER	[END]	  -->
	
</div>
<!--	CONTENT WRAPPER	[END]	  -->

<script src="<?php echo base_url(); ?>assets/site/js/videos_index_view.js"></script>