<?php



$media_images = json_decode($article['media_images']);

?>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
		
		<?php if(!empty($media_images) && $media_images[0] != '') { ?>
		jQuery('#text_mulitplemedia_6_carousel<?php echo $article['id']; ?>').lightSlider({
			gallery : true,
            item : 1,
            thumbItem : 5,
            slideMargin : 0,
            speed : 500,
            auto : false,
            loop : true,
            rtl : true,
            onSliderLoad : function() {
                
            }
		});
		<?php } ?>
		
		jQuery('.btn-login-in').click( function() {
			swal({
				text: "الرجاء تسجيل الدخول.",
				icon: "error",
			}).then((value) => {
			   window.location.href = "<?php echo base_url().'تسجيل'; ?>"; 
			});
		});
		
	});

</script>

<div class="text_mulitplemedia_6_view-layout-wrapper">

	<div class="article-text_mulitplemedia_6-layout-wrapper">
	
		<?php if(!empty($media_images) && $media_images[0] != '') { ?>
		<div class="article-text_mulitplemedia_6-media-div">
			<ul id="text_mulitplemedia_6_carousel<?php echo $article['id']; ?>" class="text_mulitplemedia_6_carousel">
				<?php foreach ($media_images AS $media_image) { ?>
				<li data-thumb="<?php echo site_url('assets/media/'.$media_image); ?>">
					<img src="<?php echo site_url('assets/media/'.$media_image); ?>" class="img-responsive" title="<?php echo $article['title']; ?>" alt="<?php echo $article['title']; ?>" />
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	
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
					<a href="<?php echo site_url('مقالات-المؤلف/'.$article['created_by_username']); ?>" id="yawmiyati-ga-article-author" itemprop="author" itemscope itemtype="https://schema.org/Person" title="مقالات المؤلف - <?php echo $article['created_by_first_name'].' '.$article['created_by_last_name']; ?>" class="btn btn-black-border btn-author"><img src="<?php echo site_url('assets/site/images/icon-pen.png'); ?>" title="بيا ابو ملهب" alt="بيا ابو ملهب" /> <?php echo $article['created_by_first_name'].' '.$article['created_by_last_name']; ?></a>
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

</div>