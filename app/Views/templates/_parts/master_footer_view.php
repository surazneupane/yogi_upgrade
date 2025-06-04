<?php



?>

		<!-- Footer section start-->
	    <footer class="contact">
	        <!-- Gradient -->
	        <div class="gradient"></div>
	        <?php if(service('uri')->getSegment(1) == '') { ?>
	        <!-- container Start-->
	        <div class="container">
	            <div class="row" data-aos="fade-up" data-aos-duration="400">
	                <div class="col-lg-12 col-md-12 col-12 columns-1" style="border: none;">
	                    <h2><?php echo lang('InformationLang.OUR_ADDRESS'); ?></h2>
	                    <address>
	                    	<?php echo lang('InformationLang.ADDRESS'); ?>
	                   </address>
	                </div>
	            </div>
	        </div>
	        <!-- container Ended-->
	        <?php } else { ?>
	        <div class="container">
	            <div class="row" data-aos="fade-up" data-aos-duration="400">
	                <div class="col-lg-12 col-md-12 col-12 columns-1" style="border: none;">	                    
	                    <address>
	                    	<?php echo lang('InformationLang.EMAL_PHONE'); ?>
	                   </address>
	                </div>
	            </div>
	        </div>
	        <?php } ?>
	        <div class="copyright pt-0">
	            <div class="container">
	                <div class="row border-img">
	                    <div class="col-md-12">
	                        <img src="<?php echo base_url('assets/site/images/border.png'); ?>" alt="">
	                    </div>
	                </div>
	            </div>
	            <div class="container">
	                <!--<div class="row" data-aos="fade-up" data-aos-duration="400">-->
	                <div class="row">
	                    <div class="col-lg-1 col-md-12">
	                        <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/site/images/footer-logo-bg.png'); ?>" alt="logo"></a>
	                    </div>
	                    <div class="col-lg-11 col-md-12 right-part">
	                        <ul class="ml-auto">
	                        	<!--<?php // $i = 0; foreach ($mainmenu_tree_array AS $mainmenu) { ?>
								<?php 
								/*if ($mainmenu['menu_type'] == 'category') { 
									$parent_alias = $mainmenu['menu_category_alias']; 
								}*/
								?>
								<?php // if(isset($mainmenu['submenu']) && !empty($mainmenu['submenu'])) { ?>
									<?php 
										/*$active = '';
										if($mainmenu['menu_type'] == 'custom_link') { 
											$menu_link = $mainmenu['menu_custom_link'];
											$active = show_current_class(rawurldecode($submenu['menu_article_alias']));
										} elseif ($mainmenu['menu_type'] == 'article') { 
											$menu_link = base_url('article/index/'.rawurldecode($mainmenu['menu_article_alias']));
											$active = show_current_class(rawurldecode($mainmenu['menu_article_alias']));
										} elseif ($mainmenu['menu_type'] == 'category') { 
											$menu_link = base_url('category/'.rawurldecode($mainmenu['menu_category_alias']));
											$active = show_current_class(rawurldecode($mainmenu['menu_category_alias']));
										}  elseif ($mainmenu['menu_type'] == 'tag') { 
											$menu_link = base_url('مقالات-الوسم/'.rawurldecode($mainmenu['menu_tag_alias']));
											$active = show_current_class(rawurldecode($mainmenu['menu_tag_alias']));
										} elseif ($mainmenu['menu_type'] == 'video_category') { 
											if($mainmenu['menu_video_category_id'] != 0) {
												$menu_link = base_url('فيديوهات/'.$mainmenu['menu_video_category_id']);
												$active = show_current_class(rawurldecode($mainmenu['menu_video_category_id']));
											} else {
												$menu_link = base_url('فيديوهات/all');
												$active = show_current_class(rawurldecode('all'));
											}
										} else {
											$menu_link = 'javascript:void(0)';
										}*/
									?>
									<li><a class="hidden-xs">~</a></li>
									<li class="nav-item item-<?php // echo $mainmenu['id']; ?> <?php // echo $active; ?>">
										<a href="<?php // echo $menu_link; ?>" target="<?php // echo $mainmenu['menu_target']; ?>" class="nav-link <?php // echo $mainmenu['menu_class'].' '.$active; ?>" title="<?php // echo $mainmenu['title']; ?>">
											<?php // echo $mainmenu['title']; ?>
										</a>
									</li>
								<?php // } else { ?>
									<?php 
										/*$active = '';
										if($mainmenu['menu_type'] == 'custom_link') { 
											$menu_link = $mainmenu['menu_custom_link'];
										} elseif ($mainmenu['menu_type'] == 'article') { 
											$menu_link = base_url('article/index/'.rawurldecode($mainmenu['menu_article_alias']));
											$active = show_current_class(rawurldecode($mainmenu['menu_article_alias']));
										} elseif ($mainmenu['menu_type'] == 'category') { 
											$menu_link = base_url('الفئة/'.rawurldecode($mainmenu['menu_category_alias']));
											$active = show_current_class(rawurldecode($mainmenu['menu_category_alias']));
										} elseif ($mainmenu['menu_type'] == 'tag') { 
											$menu_link = base_url('مقالات-الوسم/'.rawurldecode($mainmenu['menu_tag_alias']));
											$active = show_current_class(rawurldecode($mainmenu['menu_tag_alias']));
										} elseif ($mainmenu['menu_type'] == 'video_category') { 
											if($mainmenu['menu_video_category_id'] != 0) {
												$menu_link = base_url('فيديوهات/'.$mainmenu['menu_video_category_id']);
												$active = show_current_class(rawurldecode($mainmenu['menu_video_category_id']));
											} else {
												$menu_link = base_url('فيديوهات/all');
												$active = show_current_class(rawurldecode('all'));
											}
										} else {
											$menu_link = 'javascript:void(0)';
										}*/
									?>
									<?php // if($i != 0) { ?>
									<li><a class="hidden-xs">~</a></li>
									<?php // } ?>
									<li class="nav-item item-<?php // echo $mainmenu['id']; ?> <?php // echo $active; ?>">
										<a href="<?php // echo $menu_link; ?>" target="<?php // echo $mainmenu['menu_target']; ?>" class="nav-link <?php // echo $mainmenu['menu_class']; ?>" title="<?php // echo $mainmenu['title']; ?>"><?php // echo $mainmenu['title']; ?></a>
									</li>
								<?php // } ?>
							<?php // $i++; } ?>-->
								<li class="nav-item ">
									<a href="<?php echo base_url('schedule'); ?>" target="_parent" class="nav-link " title="<?php echo lang('InformationLang.SCHEDULE'); ?>"><?php echo lang('InformationLang.SCHEDULE'); ?></a>
								</li>
								<li><a class="hidden-xs">~</a></li>
								<li class="nav-item">
									<a href="<?php echo base_url('contact'); ?>" target="_parent" class="nav-link " title="Contact">Contact</a>
								</li>
	                        </ul>
	                        <p>&copy; <?php echo date('Y'); ?> <?php echo lang('InformationLang.COPYTIGHT'); ?></p>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </footer>
	    <!-- Footer section Ended-->
	    <!-- Return to Top -->
	    <a href="javascript:" id="return-to-top"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>

		
		<!--<div class="alert alert-info alert-dismissible" role="alert" style="margin: 0; border-radius: 0;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			Page rendered in <strong>{elapsed_time}</strong> seconds.<?php // echo (ENVIRONMENT === 'development') ? ' | <b>Version</b> ' . CI_VERSION : '' ?>
		</div>-->
		
		
		<?php echo $before_body;?>
		
		<!-- Custom JavaScript -->
	    <script src="<?php echo base_url('assets/site/js/animate.js'); ?>"></script>
	    <script src="<?php echo base_url('assets/site/js/custom.js'); ?>"></script>
		
		
	</body>
	 
</html>