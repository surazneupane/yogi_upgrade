<?php



?>


	<!-- Header section Start -->
    <header id="topheader" class="top">
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <ul class="nav flex-column">
            	<?php $i = 0; foreach ($mainmenu_tree_array AS $mainmenu) { ?>
					<?php 
					if ($mainmenu['menu_type'] == 'category') { 
						$parent_alias = $mainmenu['menu_category_alias']; 
					}
					?>
					<?php if(isset($mainmenu['submenu']) && !empty($mainmenu['submenu'])) { ?>
						<?php 
							$active = '';
							if($mainmenu['menu_type'] == 'custom_link') { 
								$menu_link = $mainmenu['menu_custom_link'];
								$active = show_current_class(rawurldecode($mainmenu['menu_article_alias']));
							} elseif ($mainmenu['menu_type'] == 'article') { 
								$menu_link = base_url('article/'.rawurldecode($mainmenu['menu_article_alias']));
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
							}
						?>
						<li class="nav-item item-<?php echo $mainmenu['id']; ?> <?php echo $active; ?>">
							<a href="<?php echo $menu_link; ?>" target="<?php echo $mainmenu['menu_target']; ?>" class="nav-link <?php echo $mainmenu['menu_class'].' '.$active; ?>" title="<?php echo $mainmenu['title']; ?>">
								<?php echo $mainmenu['title']; ?>
							</a>
							<div class="submenu pl-2">
								<?php foreach ($mainmenu['submenu'] AS $submenu) { ?>
								<?php 
									$active = '';
									if($submenu['menu_type'] == 'custom_link') { 
										$menu_link = $submenu['menu_custom_link'];
										$active = show_current_class(rawurldecode($submenu['menu_article_alias']));
									} elseif ($submenu['menu_type'] == 'article') { 
										$menu_link = base_url('article/'.rawurldecode($submenu['menu_article_alias']));
										$active = show_current_class(rawurldecode($submenu['menu_article_alias']));
									} elseif ($submenu['menu_type'] == 'category') { 
										$menu_link = base_url('الفئة/'.rawurldecode($parent_alias).'/'.rawurldecode($submenu['menu_category_alias']));
										$active = show_current_class(rawurldecode($submenu['menu_category_alias']));
									}  elseif ($submenu['menu_type'] == 'tag') { 
										$menu_link = base_url('مقالات-الوسم/'.rawurldecode($submenu['menu_tag_alias']));
										$active = show_current_class(rawurldecode($submenu['menu_tag_alias']));
									}  elseif ($submenu['menu_type'] == 'video_category') { 
										if($submenu['menu_video_category_id'] != 0) {
											$menu_link = base_url('فيديوهات/'.$submenu['menu_video_category_id']);
											$active = show_current_class(rawurldecode($submenu['menu_video_category_id']));
										} else {
											$menu_link = base_url('فيديوهات/all');
											$active = show_current_class(rawurldecode('all'));
										}
									} else {
										$menu_link = 'javascript:void(0)';
									}
								?>
	                            <a href="<?php echo $menu_link; ?>" target="<?php echo $submenu['menu_target']; ?>" class="nav-link item-<?php echo $submenu['id']; ?> <?php echo $submenu['menu_class'].' '.$active; ?>" title="<?php echo $submenu['title']; ?>">- <?php echo $submenu['title']; ?></a>
	                            <?php } ?>
							</div>
						</li>
					<?php } else { ?>
						<?php 
							$active = '';
							if($mainmenu['menu_type'] == 'custom_link') { 
								$menu_link = $mainmenu['menu_custom_link'];
							} elseif ($mainmenu['menu_type'] == 'article') { 
								$menu_link = base_url('article/'.rawurldecode($mainmenu['menu_article_alias']));
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
							}
						?>
						<li class="nav-item item-<?php echo $mainmenu['id']; ?> <?php echo $active; ?>">
							<a href="<?php echo $menu_link; ?>" target="<?php echo $mainmenu['menu_target']; ?>" class="nav-link <?php echo $mainmenu['menu_class']; ?>" title="<?php echo $mainmenu['title']; ?>"><?php echo $mainmenu['title']; ?></a>
						</li>
					<?php } ?>
				<?php $i++; } ?>
			</ul>
        </div>
        <!-- Nav section Start -->
        <nav id="navbar">
            <!-- container Start-->
            <div class="container">
                <!--Row Start-->
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-4 align-self-center left-side">
                        <p><?php echo lang('InformationLang.BETWEEN_YOGIS'); ?></p>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-5 align-self-center logo">
                        <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url('assets/site/images/nav-logo.png'); ?>" alt="logo" class="d-block mx-auto"></a>
                        <div class="d-block d-sm-none text-white mobile-title">Between Yogis</div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-4 col-7 align-self-center right-side">
                        <div class="d-block d-md-none social-icons square">
                            <!-- Page Content -->
                            <div id="page-content-wrapper">
                                <span class="slide-menu" onclick="openNav()"><i class="fa fa-bars" aria-hidden="true"></i></span>
                            </div>
                        </div>
                        <div class="social-icons another">
                           <a href="https://www.facebook.com/betweenyogis" target="_blank" title="Facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                            <!--<i class="fa fa-twitter" aria-hidden="true"></i>-->
                            <a href="https://www.instagram.com/explore/locations/501213800352357/association-between-yogis/?hl=en" target="_blank" title="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            <a href="https://www.youtube.com/channel/UCKCwTHjtyZoYZ4_eefXAAcA" target="_blank" title="Youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a>
                        </div>
                        <div class="d-inline pull-right language-switcher mr-3">
						   <a href="<?=base_url()?>en" class="text-uppercase <?php if($clang == 'en') { ?>active<?php } ?>">En</a>
						   <a href="<?=base_url()?>fr" class="text-uppercase <?php if($clang == 'fr') { ?>active<?php } ?>">Fr</a>
						   <!--<a href="<?=base_url()?>es">Spanish</a>-->
						</div>
						<div class="d-block text-right clearfix text-white">
							<?php echo lang('InformationLang.CALL_06_18_70_74_21'); ?>
						</div>
                    </div>
                </div>
                <!--Row Ended-->
                
            </div>
            <!-- container Ended-->
            
            <div class="desktop-mainmenu-wrapper d-none d-md-block">
		        <ul class="nav justify-content-center">
		        	<?php $i = 0; foreach ($mainmenu_tree_array AS $mainmenu) { ?>
						<?php 
						if ($mainmenu['menu_type'] == 'category') { 
							$parent_alias = $mainmenu['menu_category_alias']; 
						}
						?>
						<?php if(isset($mainmenu['submenu']) && !empty($mainmenu['submenu'])) { ?>
							<?php 
								$active = '';
								if($mainmenu['menu_type'] == 'custom_link') { 
									$menu_link = $mainmenu['menu_custom_link'];
									$active = show_current_class(rawurldecode($submenu['menu_article_alias']));
								} elseif ($mainmenu['menu_type'] == 'article') { 
									$menu_link = base_url('article/'.rawurldecode($mainmenu['menu_article_alias']));
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
								}
							?>
							<li class="nav-item item-<?php echo $mainmenu['id']; ?> dropdown  <?php echo $active; ?>">
								<a href="<?php echo $menu_link; ?>" target="<?php echo $mainmenu['menu_target']; ?>" class="nav-link <?php echo $mainmenu['menu_class'].' '.$active; ?> dropdown-toggle" title="<?php echo $mainmenu['title']; ?>">
									<?php echo $mainmenu['title']; ?>
								</a>
								<div class="dropdown-menu">
									<?php foreach ($mainmenu['submenu'] AS $submenu) { ?>
									<?php 
										$active = '';
										if($submenu['menu_type'] == 'custom_link') { 
											$menu_link = $submenu['menu_custom_link'];
											$active = show_current_class(rawurldecode($submenu['menu_article_alias']));
										} elseif ($submenu['menu_type'] == 'article') { 
											$menu_link = base_url('article/'.rawurldecode($submenu['menu_article_alias']));
											$active = show_current_class(rawurldecode($submenu['menu_article_alias']));
										} elseif ($submenu['menu_type'] == 'category') { 
											$menu_link = base_url('الفئة/'.rawurldecode($parent_alias).'/'.rawurldecode($submenu['menu_category_alias']));
											$active = show_current_class(rawurldecode($submenu['menu_category_alias']));
										}  elseif ($submenu['menu_type'] == 'tag') { 
											$menu_link = base_url('مقالات-الوسم/'.rawurldecode($submenu['menu_tag_alias']));
											$active = show_current_class(rawurldecode($submenu['menu_tag_alias']));
										}  elseif ($submenu['menu_type'] == 'video_category') { 
											if($submenu['menu_video_category_id'] != 0) {
												$menu_link = base_url('فيديوهات/'.$submenu['menu_video_category_id']);
												$active = show_current_class(rawurldecode($submenu['menu_video_category_id']));
											} else {
												$menu_link = base_url('فيديوهات/all');
												$active = show_current_class(rawurldecode('all'));
											}
										} else {
											$menu_link = 'javascript:void(0)';
										}
									?>
		                            <a href="<?php echo $menu_link; ?>" target="<?php echo $submenu['menu_target']; ?>" class="dropdown-item item-<?php echo $submenu['id']; ?> <?php echo $submenu['menu_class'].' '.$active; ?>" title="<?php echo $submenu['title']; ?>"><?php echo $submenu['title']; ?></a>
		                            <?php } ?>
								</div>
							</li>
						<?php } else { ?>
							<?php 
								$active = '';
								if($mainmenu['menu_type'] == 'custom_link') { 
									$menu_link = $mainmenu['menu_custom_link'];
								} elseif ($mainmenu['menu_type'] == 'article') { 
									$menu_link = base_url('article/'.rawurldecode($mainmenu['menu_article_alias']));
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
								}
							?>
							<li class="nav-item item-<?php echo $mainmenu['id']; ?> <?php echo $active; ?>">
								<a href="<?php echo $menu_link; ?>" target="<?php echo $mainmenu['menu_target']; ?>" class="nav-link <?php echo $mainmenu['menu_class']; ?>" title="<?php echo $mainmenu['title']; ?>"><?php echo $mainmenu['title']; ?></a>
							</li>
						<?php } ?>
					<?php $i++; } ?>
				</ul>
	        </div>
	            
        </nav>
        <!-- Nav section Ended -->
        <img class="border-img" src="<?php echo base_url('assets/site/images/border.png'); ?>" width="100%" alt="">
        
    </header>
    <!-- Header section Ended-->


