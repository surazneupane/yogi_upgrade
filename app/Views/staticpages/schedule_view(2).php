<?php



/*echo "<pre>";
print_r($schedules);
exit;*/

?>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
	
	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->
	<div class="articledetails-layout-wrapper scheudule-content-wrapper">
	
		<section class="banner-another ">
	        <!-- Banner section Start-->
	    </section>
	
	    <section id="blog_single" class="scheudule-page">
	    
	    	<!-- container Start-->
	        <div class="container">
	            
	        	<h3 data-aos="fade-up" data-aos-delay="300" class="title text-center" id="yogasite-ga-article-headline" itemprop="headline"><?php echo $this->lang->line('SCHEDULE'); ?></h3>
	        	
	        	<div class="scheudule-content-container">
	        		
	        		<div id="scheudule-carousel" class="scheudule-carousel owl-carousel">
	        			<?php foreach ($schedules AS $scheduleA) { ?>
    					<div class="item"> 
    						<div class="row seven-cols">
    							<?php foreach ($scheduleA AS $key => $schedule) { ?>
	    							<?php if(!empty($schedule)) { ?>
		    							<?php $schedule_timing = json_decode($schedule['schedule_timing']); ?>
		    							<div class="col-lg-1 col-sm-3 col-xs-6 col">
		    								<div class="card scheudule-box mb-3">
												<div class="card-header p-0 schedule-dateday">
													<h4><?php echo date('l', strtotime($schedule['schedule_date'])); ?></h4>
				    								<?php echo date('d F', strtotime($schedule['schedule_date'])); ?>
												</div>
												<?php foreach ($schedule_timing AS $timing) { ?>
												<?php if($timing->timing != '') { ?>
													<?php $techerdetails = GetTeacherDetailsById($timing->teacher_id); ?>
													<div class="card-body p-0 schedule-data">
														<div class="schedule-timing"><?php echo $timing->timing; ?></div>
														<div class="schedule-yoga_type"><?php echo $timing->yoga_type; ?></div>
														<div class="schedule-teacher_id"><?php echo $techerdetails['title']; ?></div>
														<div class="schedule-seat_info"><?php echo $timing->seat_info; ?></div>
													</div>
													<?php } ?>
												<?php } ?>
				    						</div>
		    							</div>
		    						<?php } else { ?>
		    							<div class="col-lg-1 col-sm-3 col-xs-6 col">
		    								<div class="card scheudule-box mb-3">
												<div class="card-header p-0 schedule-dateday">
													<h4><?php echo date('l', strtotime($key)); ?></h4>
				    								<?php echo date('d F', strtotime($key)); ?>
												</div>
												<div class="card-body p-0 schedule-data bg-97B2C7 text-white">
													<?php echo $this->lang->line('NO_SESSION'); ?>
												</div>
				    						</div>
		    							</div>
		    						<?php } ?>
    							<?php } ?>
    						</div>
    					</div>
    					<?php } ?>
	        		</div>
	        	</div>
	        
	        </div>
	        <!-- container Ended-->
	    
	        <div class="pricing py-2">
	    
		      <h3 data-aos="fade-up" data-aos-delay="300" class="title text-center"><?php echo $this->lang->line('PRICING'); ?></h3>
		    
			  <div class="container" data-aos="fade-left" data-aos-delay="300">
			    <div class="row">
			      <!-- Free Tier -->
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo $this->lang->line('PLAN_1'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo $this->lang->line('PLAN_1_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3 ml-0">
				              <li class="m-0 text-center"><?php echo $this->lang->line('PLAN_1_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo $this->lang->line('PLAN_1_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo $this->lang->line('BOOK_NOW'); ?>"><?php echo $this->lang->line('BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      <!-- Plus Tier -->
			      <!--<div class="col">
			        <div class="card mb-4 mb-xl-0">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo $this->lang->line('PLAN_2'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo $this->lang->line('PLAN_2_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo $this->lang->line('PLAN_2_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo $this->lang->line('PLAN_2_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo $this->lang->line('BOOK_NOW'); ?>"><?php echo $this->lang->line('BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>-->
			      <!-- Pro Tier -->
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo $this->lang->line('PLAN_3'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo $this->lang->line('PLAN_3_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo $this->lang->line('PLAN_3_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo $this->lang->line('PLAN_3_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo $this->lang->line('BOOK_NOW'); ?>"><?php echo $this->lang->line('BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      <!-- Plus Tier -->
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo $this->lang->line('PLAN_4'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo $this->lang->line('PLAN_4_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo $this->lang->line('PLAN_4_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo $this->lang->line('PLAN_4_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo $this->lang->line('BOOK_NOW'); ?>"><?php echo $this->lang->line('BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      <!-- Pro Tier -->
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo $this->lang->line('MONTHS_3'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo $this->lang->line('MONTHS_3_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo $this->lang->line('MONTHS_3_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo $this->lang->line('MONTHS_3_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo $this->lang->line('BOOK_NOW'); ?>"><?php echo $this->lang->line('BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      <!-- Pro Tier -->
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo $this->lang->line('PLAN_5'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo $this->lang->line('PLAN_5_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo $this->lang->line('PLAN_5_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo $this->lang->line('PLAN_5_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo $this->lang->line('BOOK_NOW'); ?>"><?php echo $this->lang->line('BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      <!-- Pro Tier -->
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo $this->lang->line('YEAR_1'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo $this->lang->line('YEAR_1_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo $this->lang->line('YEAR_1_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo $this->lang->line('YEAR_1_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo $this->lang->line('BOOK_NOW'); ?>"><?php echo $this->lang->line('BOOK_NOW'); ?></a>
				          
				        </div>
			          </div>
			        </div>
			      </div>
			    </div>
			  </div>
			</div>
	    </section>
	    
	    <section class="blog-page-another"></section>
	
	</div>
	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->

<script type="text/javascript">
	
	jQuery(document).ready( function() {
		
		var scheuduleCarousel = jQuery('#scheudule-carousel');
		scheuduleCarousel.owlCarousel({
		    loop:false,
		    navText:['Previous','Next'],
		    margin:10,
		    nav:true,
		    dots:false,
		    responsive:{
		        0:{
		            items:1
		        },
		        575:{
		            items:1
		        },
		        767:{
		            items:1
		        },
		        991:{
		            items:1
		        },
		        1200:{
		            items:1
		        }
		    }
		});
		
	});

</script>
