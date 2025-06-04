<?php
namespace App\Controllers;



class Tags extends BaseController {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('user_agent');
		$this->load->library('Breadcrumbs');
		$this->load->helper('articles');
		$this->load->helper('tags');
		$this->load->helper('categories');
		$siteConfiguration = siteConfiguration();
		
		if($siteConfiguration['site_caching']) {
       		$this->output->cache($siteConfiguration['site_caching_time']);
		}
		
		$this->data['current_user'] = $this->ion_auth->user()->row();
		$this->data['current_user_menu'] = '';
		$this->data['site_title'] = $siteConfiguration['site_title'];
		
	}
	
		
	
	// TAG ARTICLES LIST PAGE
	public function tagarticles($tag_alias)
	{
		
		$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');
		
		if($tag_alias == '') {
			
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', '404 - Page not found.');
			$this->render('/');
			
		}
		
		$tag_detail = getTagDetailsByAlias($tag_alias);
				
		$where = ' WHERE 1';		
		//$where .= ' AND `status` = "1" AND `published_date` <= "'.date('Y-m-d H:i:s').'" AND `tag_ids` = '.$tag_detail['id'];
		$where .= ' AND `status` = "1" AND `published_date` <= "'.date('Y-m-d H:i:s').'" AND FIND_IN_SET('.$tag_detail['id'].', `tag_ids`)';
       	$qry ='SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM articles'.$where.' ORDER BY published_date DESC LIMIT 0, 6'; // select data from db
       	
       	$tag_articles = $this->db->query($qry)->result_array();
       	
       	$this->data['tag_alias'] = $tag_alias;       	
       	$this->data['tag_detail'] = $tag_detail;
       	$this->data['tag_articles'] = $tag_articles;
       	$this->data['current_user'] = $current_user;
       	
       	if($tag_detail['meta_title'] == '') {
       		$page_title = $this->data['page_title'].' : '.$tag_detail['title'];	
       	} else {
       		$page_title = $tag_detail['meta_title'];
       	}
       		
       	$page_description = $tag_detail['meta_description'];
       	
       	$page_title = str_replace('"', '`', $page_title);
   		$page_title = str_replace("'", "'", $page_title);
   		
   		$page_description = str_replace('"', '`', $page_description);
   		$page_description = str_replace("'", "'", $page_description);
       	
       	$this->data['page_title'] = $page_title;
       	$this->data['page_description'] = $page_description;
       	
       	
       	
       	
       	/********************************************/
       	//              ADS BLOCKS
       	/********************************************/
       	
       	
       	if ($this->agent->is_mobile()) {
       	
       		$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_Billboard', [[320, 100], [320, 120]], 'div-gpt-ad-1542802129681-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";
       		
	       	$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Mobile_Billboard -->
			<div id='div-gpt-ad-1542802129681-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802129681-0'); });
			</script>
			</div>";
	       	
       	} else {
       		
       		$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_Billbaord_TOP', [[970, 250], [728, 90]], 'div-gpt-ad-1543493299817-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";
       		
       		$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Desk_Billbaord_TOP -->
			<div id='div-gpt-ad-1543493299817-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1543493299817-0'); });
			</script>
			</div>";
       		
       	}
       	
       	
       	
       	
       	$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = base_url();
       	if($tag_detail['image'] != '') {
       		$tag_detail['image'] = 'assets/site/images/yawmiyati-default.jpg';
       	}
       	$this->data['fb_og_image'] = base_url().$tag_detail['image'];
       	$this->data['fb_og_title'] = $page_title;
       	$this->data['fb_og_description'] = $page_description;
       	$this->data['fb_og_published_time'] = '';
       	
  		$this->render('tags/tagsarticles_view');
		  		
	}
	
	
	
	// TAG ARTICLES LIST PAGE AJAX
	public function tagarticlesAjax()
	{
		
		$tag_alias = $this->input->post("tag_alias");
		$limit_start = $this->input->post("limit_start");
		$limit_end = $this->input->post("limit_end");
				
		$tag_detail = getTagDetailsByAlias($tag_alias);
				
		$where = ' WHERE 1';		
		//$where .= ' AND `status` = "1" AND `published_date` <= "'.date('Y-m-d H:i:s').'" AND `tag_ids` = '.$tag_detail['id'];
		$where .= ' AND `status` = "1" AND `published_date` <= "'.date('Y-m-d H:i:s').'" AND FIND_IN_SET('.$tag_detail['id'].', `tag_ids`)';
       	$qry ='SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM articles'.$where.' ORDER BY published_date DESC LIMIT '.$limit_start.', '.$limit_end; // select data from db
       	
       	$tag_articles = $this->db->query($qry)->result_array();
       	
       	$data_html = '';
       	foreach ($tag_articles AS $tag_article) {
       		
       		// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_link = generateArticleLink($tag_article['category_ids'], $tag_article['alias']); 
			$category_ids = explode(',', $tag_article['category_ids']);
			$category_idsCnt = count($category_ids);
			if($category_idsCnt == 1) {
				$category_details = getCategoryDetails($category_ids[0]);
			} else {
				$category_details = getCategoryDetails($category_ids[1]);
			}
			
			if($category_details['parent_id'] != 0) {
				$parent_category_details = getParentCategoryDetails($category_details['id']);
			} 
			
			$color_style = $category_details['color_style'];
			if($color_style == '') {
				$category_details['color_style'] = $parent_category_details['color_style'];
			}
			
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
			$day = date('l', strtotime($tag_article['published_date']));
			$month = date('F', strtotime($tag_article['published_date']));
			$date = date('d', strtotime($tag_article['published_date']));
			$year = date('Y', strtotime($tag_article['published_date']));
			
			$data_html .= '<div class="col-md-4 content-block-col" data-mh="heightConsistancy">';
				$data_html .= '<div class="content-block">';
					$data_html .= '<div class="content-category" style="border-bottom: 1px solid '.$category_details['color_style'].';">';
						$data_html .= '<span style="background: '.$category_details['color_style'].';">'.$category_details['title'].'</span>';
					$data_html .= '</div>';
					$data_html .= '<div class="img-div">';
						$data_html .= '<a href="'.$article_link.'" title="'.$tag_article['title'].'">';
						
										if($tag_article['image'] == '') {
											$tag_article['image'] = 'assets/site/images/yawmiyati-default.jpg';	
										} else {
											$tag_article['image'] = 'assets/media/'.$tag_article['image'];
										}
										if($tag_article['article_layout'] == 'text_mulitplemedia' || $tag_article['article_layout'] == 'text_mulitplemedia_6' || $tag_article['article_layout'] == 'fashion') {
											$media_images = json_decode($tag_article['media_images']);
											if($media_images[$tag_article['media_image_main']] == '') {
												$tag_article['image'] = 'assets/site/images/yawmiyati-default.jpg';	
											} else {
												$tag_article['image'] = 'assets/media/'.$media_images[$tag_article['media_image_main']];
											}
										}
										
										$file_FCPath = FCPATH.urldecode($tag_article['image']);
										if(!file_exists($file_FCPath)) {
											$tag_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										}
										
							$data_html .= '<img title="'.$tag_article['title'].'" alt="'.$tag_article['title'].'" src="'.base_url().'assets/site/images/yawmiyati-default.jpg" data-src="'.base_url().$tag_article['image'].'" class="lazyload img-responsive center-block"/>';
						$data_html .= '</a>';
					$data_html .= '</div>';
					$data_html .= '<div class="content-div">';
						$data_html .= '<h4><a href="'.$article_link.'" title="'.$tag_article['title'].'">'.$tag_article['title'].'</a></h4>';
								if($tag_article['description'] != '' || $tag_article['article_layout'] != 'media') {
									
									$description = strip_tags($tag_article['description']);
									$description_string_count = mb_strlen($description);
									if($description_string_count > 153) {
										$description = mb_substr($description, 0, 150).'...';
									}
									
									$data_html .= '<p>'.$description.'</p>';
								} 
								
						$data_html .= '<time>'.$arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year.'</time>';
					$data_html .= '</div>';
				$data_html .= '</div>';
			$data_html .= '</div>';
			
       	}
       	
       	
       	$response = array();
       	$response['csrf_test_name'] = $this->security->get_csrf_hash();
       	$response['tag_alias'] = $tag_alias;
       	$response['limit_start'] = $limit_start + $limit_end;
       	$response['data'] = $data_html;
       	
       	echo json_encode($response);
		exit;
		
	}
	
	
}
