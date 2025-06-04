<?php



?>

<script type="text/javascript">

	jQuery(document).ready( function() {

		jQuery('.btn-login-in').click( function() {
			swal({
				text: "الرجاء تسجيل الدخول.",
				icon: "error",
			}).then((value) => {
			   window.location.href = "<?php echo base_url().'تسجيل'; ?>"; 
			});
		});
		
		var owl = jQuery('#travel_carousel<?php echo $article['id']; ?>').owlCarousel({
			rtl : true,
			loop : true,
			margin : 0,
			autoplay : true,
			autoplayTimeout : 5000,
			autoplayHoverPause : true,
			center : true,
			items : 1,
			nav : false,
			dots : false
		});
		
		// Go to the next item
		jQuery('.customNextBtn').click(function() {
		    owl.trigger('next.owl.carousel');
		});
		// Go to the previous item
		jQuery('.customPrevBtn').click(function() {
		    // With optional speed parameter
		    // Parameters has to be in square bracket '[]'
		    owl.trigger('prev.owl.carousel', [300]);
		});
		
	});

</script>

<div class="travel_view-layout-wrapper">

	<?php 
	
	if($article['image'] == '') {
		$article['image'] = 'assets/site/images/yawmiyati-default.jpg';	
	} else {
		$article['image'] = 'assets/media/'.$article['image'];
	}
	
	?>
	
	<div class="article-image-div">
		<img title="<?php echo $article['title']; ?>" alt="<?php echo $article['title']; ?>" src="<?php echo base_url().$article['image']; ?>" class="img-responsive center-block"/>
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
			<div class="col-md-6 col-sm-6 articlepage-content-characteristic-left-col">
				<div class="btn-actions">
					<a href="<?php echo site_url('مقالات-المؤلف/'.$article['created_by_username']); ?>" id="yawmiyati-ga-article-author" itemprop="author" itemscope itemtype="https://schema.org/Person" title="مقالات المؤلف - <?php echo $article['created_by_first_name'].' '.$article['created_by_last_name']; ?>" class="btn btn-black-border btn-author"><img src="<?php echo site_url('assets/site/images/icon-pen.png'); ?>" title="بيا ابو ملهب" alt="بيا ابو ملهب" /><?php echo $article['created_by_first_name'].' '.$article['created_by_last_name']; ?></a>
					<?php if($this->ion_auth->logged_in()) { ?>
						
						<?php if($ismyfavarticle == 0) { ?>
							<a href="<?php echo site_url('أضف-إلى-قائمتي/'.$article['id']); ?>" class="btn btn-black-border btn-addtofavorite"><img src="<?php echo site_url('assets/site/images/icon-add-favorite.png'); ?>" title="أضف إلى قائمتي" alt="أضف إلى قائمتي" /> أضف إلى قائمتي</a>
						<?php } else { ?>
							<a href="<?php echo site_url('حذف-من-قائمتي‎/'.$article['id']); ?>" class="btn btn-red-border btn-addtofavorite"><img src="<?php echo site_url('assets/site/images/icon-add-favorite.png'); ?>" title="حذف من قائمتي‎" alt="حذف من قائمتي‎" /> حذف من قائمتي‎</a>
						<?php } ?>
						
					<?php } else { ?>
					<a href="javascript:void(0)" class="btn btn-black-border btn-addtofavorite btn-login-in"><img src="<?php echo site_url('assets/site/images/icon-add-favorite.png'); ?>" title="أضف إلى قائمتي" alt="أضف إلى قائمتي" /> أضف إلى قائمتي</a>
					<?php } ?>
				</div>
			</div>			
		</div>	
	</div>
		
	<?php 
	
		$the_date = strtotime($article['published_date']);
		$publishedTDate = date("Y-d-mTG:i:s:z",$the_date);
													
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
		$day = date('l', strtotime($article['published_date']));
		$month = date('F', strtotime($article['published_date']));
		$date = date('d', strtotime($article['published_date']));
		$year = date('Y', strtotime($article['published_date']));
	
	?>
	
	<time id="yawmiyati-ga-article-datePublished" class="article-published_date" itemprop="datePublished" datetime="<?php echo $publishedTDate; ?>"><?php echo date('h:i A', strtotime($article['published_date'])); ?> | <?php echo $arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year; ?></time>
	
	<div class="article-description-div">
	
		<?php // echo $article['description']; ?>
		
		<?php 
		
			$insertion = $outstream;
			$paragraph_id = 2;
			$content = $article['description'];
			$closing_p = '</p>';
			$paragraphs = explode( $closing_p, $content );
			foreach ($paragraphs as $index => $paragraph) {
				// Only add closing tag to non-empty paragraphs
				if ( trim( $paragraph ) ) {
					// Adding closing markup now, rather than at implode, means insertion
					// is outside of the paragraph markup, and not just inside of it.
					$paragraphs[$index] .= $closing_p;
				}
				// + 1 allows for considering the first paragraph as #1, not #0.
				if ( $paragraph_id == $index + 1 ) {
					$paragraphs[$index] .= $insertion;
				}
			}
			$html = implode( '', $paragraphs );
			
			echo $html;
		
		?>
		
	</div>
		
	<div class="article-tags-div">
		<?php if(!empty($article_tags)) { ?>
			<label class="tag-label">سمات : </label>
			<?php foreach ($article_tags AS $article_tag) { ?>
			<span><a href="<?php echo site_url('مقالات-الوسم/'.$article_tag['alias']); ?>"><?php echo $article_tag['title']; ?></a></span>
			<?php } ?>
		<?php } ?>
	</div>
	
	<?php $travel_data = json_decode($article['travel_data']); ?>
	
	<?php if(!empty($travel_data)) { ?>
	<div class="article-travel-information-div">
	
		<div id="travel_carousel<?php echo $article['id']; ?>" class="travel_carousel owl-carousel custom-nav-carousel">
			<?php foreach ($travel_data AS $data) { ?>
				<?php if($data->image != '') { ?>
				<div class="item">
					<div class="row">
						<div class="col-md-6 col-sm-6 image-col">
							<div class="img-div">
								<img src="<?php echo site_url('assets/media/'.$data->image); ?>" class="img-responsive" title="<?php echo $article['title']; ?>" alt="<?php echo $article['title']; ?>" />
								<button class="customNavBtn customPrevBtn">Prev</button>
								<button class="customNavBtn customNextBtn">Next</button>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 text-col">
							<?php echo $data->description; ?>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
		
	</div>
	<?php } ?>

</div>
