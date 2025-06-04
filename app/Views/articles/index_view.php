<?php

use App\Libraries\IonAuth;

$ionAuthLibrary = new IonAuth();


?>

<section class="banner-another"></section>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">

	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->
	<div class="articledetails-layout-wrapper articledetails-<?php echo $article['article_layout']; ?>-layout-wrapper">


		<!-- About Section Start -->
		<div id="blog_single">
			<div class="container">

				<!--	BREADCRUMBS WRAPPER	[START]	  -->
				<?php // $this->data['data'] = ''; 
				?>
				<?php // echo view('breadcrumbs/index_view', $this->data); 
				?>
				<!--	BREADCRUMBS WRAPPER	[END]	  -->


				<!--		ARTICLE DETAIL CONTAINER   [START]			-->
				<div id="article-<?php echo $article['id']; ?>" class="articledetails-content-container" itemscope itemtype="https://schema.org/Article">
					<h3 data-aos="fade-up" data-aos-delay="300" class="title" id="yogasite-ga-article-headline" itemprop="headline"><?php echo $article['title']; ?></h3>

					<!--<div class="inner-text" data-aos="fade-up" data-aos-delay="400">
		            	<?php if ($clang == 'fr') { ?>
		                <h4><?php // echo dateToFrench($article['published_date'], 'F d, Y'); 
							?></h4>
		                <?php } else { ?>
		                <h4><?php // echo date('F d, Y', strtotime($article['published_date'])); 
							?></h4>
		                <?php } ?>
		            </div>-->
					<ul class="mbm_social" data-aos="fade-up" data-aos-delay="500">
						<li><a class="social-facebook" href="javascript:void(0)" onclick="facebookShare('<?php echo urldecode(current_url()); ?>')">
								<i class="fa fa-facebook"><small><?php echo lang('InformationLang.SHARE'); ?></small></i>

								<div class="tooltip"><span>facebook</span></div>
							</a>
						</li>
						<li>
							<a class="social-twitter" href="javascript:void(0)" onclick="tweetPopup('<?php echo current_url(); ?>')">
								<i class="fa fa-twitter"><small><?php echo lang('InformationLang.SHARE'); ?></small></i>
								<div class="tooltip"><span>Twitter</span></div>
							</a>
						</li>
						<!--<li><a class="social-google-plus" href="javascript:void(0)" onclick="googlePlusShare('<?php // echo urldecode(current_url()); 
																													?>')">
		            		<i class="fa fa-google-plus" aria-hidden="true"><small><?php // echo lang('InformationLang.SHARE'); 
																					?></small></i>
		            		<div class="tooltip"><span>google+</span></div>
		            		</a>
		                </li>-->
						<?php if ($ionAuthLibrary->in_group('admin')) { ?>
							<li><a class="social-google-plus" href="<?php echo site_url('administrator/articles/edit/' . $article['id']); ?>" target="_blank">
									<i class="fa fa-pencil" aria-hidden="true"><small><?php echo lang('InformationLang.EDIT'); ?></small></i>
									<div class="tooltip"><span><?php echo lang('InformationLang.EDIT'); ?></span></div>
								</a>
							</li>
						<?php } ?>
					</ul>
					<div data-aos="fade-up" data-aos-delay="600">
						<div>

							<div class="articledetails-content-layout-container">
								<?php if ($article['article_layout'] == 'text') { ?>
									<?php echo view('articles/layouts/text_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'text_media') { ?>
									<?php echo view('articles/layouts/text_media_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'text_mulitplemedia') { ?>
									<?php echo view('articles/layouts/text_mulitplemedia_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'text_mulitplemedia_6') { ?>
									<?php echo view('articles/layouts/text_mulitplemedia_6_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'media') { ?>
									<?php echo view('articles/layouts/media_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'horoscope') { ?>
									<?php echo view('articles/layouts/horoscope_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'recipe') { ?>
									<?php echo view('articles/layouts/recipe_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'fashion') { ?>
									<?php echo view('articles/layouts/fashion_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'travel') { ?>
									<?php echo view('articles/layouts/travel_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'runaway') { ?>
									<?php echo view('articles/layouts/runaway_view', $article); ?>
								<?php } elseif ($article['article_layout'] == 'questions_test') { ?>
									<?php echo view('articles/layouts/questions_test_view', $article); ?>
								<?php } ?>
							</div>

						</div>
					</div>
				</div>


			</div>
		</div>


		<!-- About Section End -->
		<section class="blog-page-another <?php if (empty($related_articles)) { ?> p-0 <?php } ?> <?php if ($article['category_ids'] == 1) { ?> p-0 <?php } ?>">
			<?php if ($article['category_ids'] != 1) { ?>
				<?php if (!empty($related_articles)) { ?>
					<div class="container">
						<div class="row">
							<div class="col-md-12 col-12 heading" data-aos="fade-up" data-aos-delay="300">
								<h2><?php echo lang('InformationLang.SIMILAR_ARTICLES'); ?></h2>
							</div>
						</div>

						<div class="row" data-aos="fade-up" data-aos-delay="400">
							<div class="col-md-12 col-12">
								<div class="row">
									<?php foreach ($related_articles as $related_article) { ?>

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

															if ($related_article['image'] == '') {
																$related_article['image'] = 'assets/site/images/blog-3.jpg';
															} else {
																$related_article['image'] = 'assets/media/' . $related_article['image'];
															}

															if ($related_article['article_layout'] == 'text_mulitplemedia' || $related_article['article_layout'] == 'text_mulitplemedia_6' || $related_article['article_layout'] == 'fashion') {
																$media_images = json_decode($related_article['media_images']);
																if ($media_images[$related_article['media_image_main']] == '') {
																	$related_article['image'] = 'assets/site/images/blog-3.jpg';
																} else {
																	$related_article['image'] = 'assets/media/' . $media_images[$related_article['media_image_main']];
																}
															}

															$file_FCPath = FCPATH . urldecode($related_article['image']);
															if (!file_exists($file_FCPath)) {
																$related_article['image'] = 'assets/site/images/blog-3.jpg';
															}

															?>
															<img title="<?php echo $related_article['title']; ?>" alt="<?php echo $related_article['title']; ?>" src="<?php echo base_url(); ?>assets/site/images/blog-3.jpg" data-src="<?php echo base_url() . $related_article['image']; ?>" class="lazyload" width="304" height="228" />
														</a>
													</figure>
												</div>
												<div class="col-md-8 inner-content">
													<h4><a href="<?php echo $article_link; ?>"><?php echo $related_article['title']; ?></a></h4>
													<?php if ($clang == 'fr') { ?>
														<p><!--<span><?php // echo dateToFrench($related_article['published_date'], 'F d, Y'); 
																		?></span>--><!--Posted by <b><a href="blog_single.html">Bizzee</a></b>--></p>
													<?php } else { ?>
														<p><!--<span><?php // echo date('F d, Y', strtotime($related_article['published_date'])); 
																		?></span>--><!--Posted by <b><a href="blog_single.html">Bizzee</a></b>--></p>
													<?php } ?>

												</div>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</section>



	</div>
	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->