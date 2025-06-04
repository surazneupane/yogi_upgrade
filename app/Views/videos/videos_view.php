<?php



?>


<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
			
	<!--	SUBCATEGORIES LAYOUT WRAPPER	[START]	  -->
	<div class="subcategories-layout-wrapper videoslistpage-layout-wrapper">
		<div class="container">

			<div class="category-content-wrapper videoslist-content-wrapper">
							
				<!--	CATEGORY VIDEOS WRAPPER	[START]	  -->
				<div class="video-content-wrapper">
				
					<div class="title-block">
						<h3 class="title"><?php echo $category_details->title_ar; ?></h3>
						<div class="sepertator"></div>
					</div>
							
					<div class="video-content-block">
						<?php if(!empty($category_videos)) { ?>
							<div id="categoryvideos-content-row" class="row">
								<?php foreach ($category_videos AS $category_video) { ?>
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
					
					<?php if(count($category_videos) >= 6) { ?>
						<div id="form-videoloadmore-div" class="form-loadmore-div form-videoloadmore-div text-center">
							<!-- form start -->
							<?php echo form_open('',array('id'=>'categoryvideos-list-form', 'class'=>'categoryvideos-list-form')); ?>								
								<?php echo form_hidden('video_category_id', $category_details->id);?>
								<?php echo form_hidden('page', 5);?>
								<?php echo form_hidden('limit', 4);?>
							<?php echo form_close();?>
						</div>
					<?php } ?>
					
				</div>
				<!--	CATEGORY VIDEOS WRAPPER	[END]	  -->
				
								
			</div>
			
		</div>
	</div>
	<!--	SUBCATEGORIES LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->

<script src="<?php echo base_url(); ?>assets/site/js/videos_view.js"></script>