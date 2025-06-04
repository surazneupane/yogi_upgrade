<?php



?>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
			
	<!--	CATEGORIES LAYOUT WRAPPER	[START]	  -->
	<div class="categories-layout-wrapper categoriespage-layout-wrapper">
	
		<section class="banner-another ">
	        <!-- Banner section Start-->
	    </section>
	
	    <section class="blog-page">
	        <div class="container">
	            <div class="row">
	                <div class="col-md-12 col-12 heading" data-aos="fade-up" data-aos-delay="300">
	                    <h2 style="color: <?php echo $main_category['color_style']; ?>;"><?php echo $main_category['title']; ?></h2>
	                    <h3><?php echo $main_category['description']; ?></h3>
	                </div>
	            </div>	            
	            <div class="row">
	            	<?php foreach ($newadded_articles AS $newadded_articleArr) { ?>
	                <div class="col-md-12 col-12" data-aos="fade-up" data-aos-delay="400">
	                    <div class="row">
	                    	<?php foreach ($newadded_articleArr AS $newadded_article) { ?>
	                    	<?php 
			            	// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
							$article_link = generateArticleLink($newadded_article['category_ids'], $newadded_article['alias']); 
							?>
	                        <div class="col-lg-6 col-md-12 col-12">
	                            <div class="row">
	                                <div class="col-md-4">
	                                    <figure>
	                                        <a href="<?php echo $article_link; ?>">
	                                        	<?php 
													if($newadded_article['image'] == '') {
														$newadded_article['image'] = 'assets/site/images/nav-logo.png';
													} else {
														$newadded_article['image'] = 'assets/media/'.$newadded_article['image'];
													}
													if($newadded_article['article_layout'] == 'text_mulitplemedia' || $newadded_article['article_layout'] == 'text_mulitplemedia_6' || $newadded_article['article_layout'] == 'fashion') {
														$media_images = json_decode($newadded_article['media_images']);
														if($media_images[$newadded_article['media_image_main']] == '') {
															$newadded_article['image'] = 'assets/site/images/nav-logo.png';
														} else {
															$newadded_article['image'] = 'assets/media/'.$media_images[$newadded_article['media_image_main']];
														}
													}
													
													$file_FCPath = FCPATH.urldecode($newadded_article['image']);
													if(!file_exists($file_FCPath)) {
														$newadded_article['image'] = 'assets/site/images/nav-logo.png';
													}
													
												?>
	                                        	<img title="<?php echo $newadded_article['title']; ?>" alt="<?php echo $newadded_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/nav-logo.png" data-src="<?php echo base_url().$newadded_article['image']; ?>" class="lazyload" width="304" height="228">
	                                        </a>
	                                    </figure>
	                                </div>
	                                <div class="col-md-8 inner-content">
	                                    <h4><a href="<?php echo $article_link; ?>"><?php echo $newadded_article['title']; ?></a></h4>
	                                    <?php if($newadded_article['description'] != '' || $newadded_article['article_layout'] != 'media') { ?>
										<?php 
											$description = strip_tags($newadded_article['description']);
											$description_string_count = mb_strlen($description);
											if($description_string_count > 100) {
												$description = mb_substr($description, 0, 97).'...';
											}
											?>
											<p><?php echo $description; ?></p>
										<?php } ?>
	                                    <p class="p-0"><!--<span><?php // echo date('F d, Y', strtotime($newadded_article['published_date'])); ?></span>--></p>
	                                </div>
	                            </div>
	                        </div>
	                        <?php } ?>
	                    </div>
	                </div>
	                <?php } ?>
	            </div>
	            <!--<div class="row">
	                <div class="col-md-12 col-12 button" data-aos="fade-up" data-aos-delay="400">
	                    <a class="btn btn-success" href="blog-page.html" role="button">load more</a>
	                </div>
	            </div>-->
	        </div>
	    </section>
	    
		
	</div>
	<!--	CATEGORIES LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->