<?php


?>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
	
	<div class="homepage-layout-wrapper" itemscope itemtype="https://schema.org/Blog">
	
		<!-- Banner section Start -->
	    <section class="banner-home">
	        <!-- Gradient -->
	        <div class="gradient"></div>
	        <!-- container Start-->
	        <div class="container">
	            <!--Row Start-->
	            <div class="row">
	                <div class="col-sm-12">
	                    <?php echo lang('InformationLang.HOME_BANNER_TEXT'); ?>
	                </div>
	            </div>
	            <!--Row Ended-->
	        </div>
	        <!-- container Ended-->
	    </section>
	    <!-- Banner section Ended -->
	    
	</div>

	<!-- About section start-->
    <section class="about">
        <!-- container Start-->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12 heading">
                    <img src="<?php echo base_url('assets/site/images/leaf.png'); ?>" alt="">
                    <h2><?php echo lang('InformationLang.SECTION_1_TITLE'); ?></h2>
                    <h3><?php echo lang('InformationLang.SECTION_1_SUBTITLE'); ?></h3>
                </div>
            </div>
            <?php echo $section_1_data; ?>
        </div>
        <!-- container Ended-->
    </section>
    <!-- About section Ended-->
    
    <!-- Services section start-->
    <section class="services">
        <!-- container-fluid Start-->
        <div class="container-fluid">
            <div class="row" data-aos="fade-up" data-aos-duration="400">
                <div class="col-md-3">
                    <figure>
                        <img src="<?php echo base_url('assets/site/images/services-bg.jpg'); ?>" alt="The Pulpit Rock">
                    </figure>
                    <div class="gradient"></div>
                </div>
                <div class="col-md-9 right-part">
                    <?php echo $section_2_data; ?>
                </div>
            </div>
        </div>
        <!-- container-fluid Ended-->
    </section>
    <!-- Services section Ended-->
    
    <!-- Section-4 section start-->
    <section class="section-4">
        <!-- container-fluid Start-->
        <div class="container-fluid">
            <div class="row" data-aos="fade-up"  data-aos-duration="400">
                <div class="col-md-9 right-part">
                
                	<?php echo $section_3_data; ?>                

                </div>
                <div class="col-md-3">
                    <figure>
                        <img src="<?php echo base_url('assets/site/images/section-4-bg.jpg'); ?>" alt="The Pulpit Rock">
                    </figure>
                    <div class="gradient"></div>
                </div>
            </div>
        </div>
        <!-- container-fluid Ended-->
    </section>
    <!-- Section-4 section Ended-->
    
    <!-- Blog section start-->
    <section class="blog">
        <!-- container Start-->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12 heading">
                    <img src="<?php echo base_url('assets/site/images/leaf.png'); ?>" alt="">
                    <h2><?php echo lang('InformationLang.SECTION_4_TITLE'); ?></h2>
                    <h3><?php echo lang('InformationLang.SECTION_4_SUBTITLE'); ?></h3>
                </div>
            </div>
            <div class="row" data-aos="fade-up" data-aos-duration="400">
                <div class="col-md-12 col-12">
                	<?php if(!empty($latest_articles)) { ?>
                    <div class="row">
                    	<?php foreach ($latest_articles AS $related_article) { ?>									
						<?php 
						// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
						$article_link = generateArticleLink($related_article['category_ids'], $related_article['alias']); 
						?>
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <figure>
                                        <a href="<?php echo $article_link; ?>">
                                        	<?php 
												
											if($related_article['image'] == '') {
												$related_article['image'] = 'assets/site/images/nav-logo.png';	
											} else {
												$related_article['image'] = 'assets/media/'.$related_article['image'];
											}
											
											if($related_article['article_layout'] == 'text_mulitplemedia' || $related_article['article_layout'] == 'text_mulitplemedia_6' || $related_article['article_layout'] == 'fashion') {
												$media_images = json_decode($related_article['media_images']);
												if($media_images[$related_article['media_image_main']] == '') {
													$related_article['image'] = 'assets/site/images/nav-logo.png';	
												} else {
													$related_article['image'] = 'assets/media/'.$media_images[$related_article['media_image_main']];
												}
											}
											
											$file_FCPath = FCPATH.urldecode($related_article['image']);
											if(!file_exists($file_FCPath)) {
												$related_article['image'] = 'assets/site/images/nav-logo.png';
											}
											
											?>
											<img title="<?php echo $related_article['title']; ?>" alt="<?php echo $related_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/nav-logo.png" data-src="<?php echo base_url().$related_article['image']; ?>" class="lazyload zoom" width="304" height="228"/>
                                        </a>
                                    </figure>
                                </div>
                                <div class="col-md-8 inner-content">
                                    <h4><a href="<?php echo $article_link; ?>"><?php echo $related_article['title']; ?></a></h4>
                                    <p><!--<span><?php // echo date('F d, Y', strtotime($related_article['published_date'])); ?></span>--><!--Posted by <b><a href="blog_single.html">Bizzee</a></b>--></p>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } else { ?>
                    <div class="alert alert-success text-center"><?php echo lang('InformationLang.NO_ARTICLES_AT_THE_MOMENT'); ?></div>
                    <?php } ?>
                </div>
            </div><!--
            <div class="row">
                <div class="col-md-12 col-12 button">
                    <a class="btn btn-success" href="#" role="button">Gallery</a>
                </div>
            </div>-->
        </div>
        <!-- container Ended-->
    </section>
    <!-- Blog section Ended-->

	
</div>
<!--	CONTENT WRAPPER	[END]	  -->