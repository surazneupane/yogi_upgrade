<?php

namespace App\Controllers;


class Videos extends BaseController {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('user_agent');
		$this->load->library('Mangomolo');
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
		$this->data['site_description'] = $siteConfiguration['site_description'];
		
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	// YAWMIYATI MAIN PAGE
	public function index($url_video_category_id = NULL)
	{
		
		$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');

  		if($url_video_category_id == 'all') {
  			
			// FEATURED VIDEOS FROM MANGOMOLO
			$mangoFeaturedVideos = $this->mangomolo->GetMangomoloFeaturedVideos(0, 10, 'all');
			$this->data['featured_videos'] = $mangoFeaturedVideos;
			
			// VIDEO CATEGORIES FROM MANGOMOLO
			$mangoVideoCategories = $this->mangomolo->GetMangomoloVideoCategoryList();
						
			$videosindex_data = array();
			$i = 0;
			if(!empty($mangoVideoCategories)) {
				foreach ($mangoVideoCategories AS $mangoVideoCategory) {
												
					// VIDEOLIST BY CATEGORIES FROM MANGOMOLO
					$mangoVideoCategory_videos = $this->mangomolo->GetMangomoloVideoListByCatID($mangoVideoCategory->id, $page = 1, $limit = 4);
					
					$videosindex_data[$i]['category'] = $mangoVideoCategory;
					$videosindex_data[$i]['videos'] = $mangoVideoCategory_videos;
					
				$i++;
				}
			}
			
	       	$this->data['videosindex_data'] = $videosindex_data;
	       	$this->data['current_user'] = $current_user;
	       	
	       	$this->data['page_title'] = $this->data['page_title'].' : فيديوهات';
	       	
       	
       	
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
	       	$this->data['fb_og_url'] = base_url().'/yawmiyati-tv';
	       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
	       	$this->data['fb_og_title'] = $this->data['page_title'].' : فيديوهات';
	       	$this->data['fb_og_description'] = $this->data['site_description'];
	       	$this->data['fb_og_published_time'] = '';
	       	
	       	
	  		$this->render('videos/index_view');
	  		
  		} else {
  			 			
  			
			// VIDEOLIST BY CATEGORIES FROM MANGOMOLO
			$mangoVideoCategory_videos = $this->mangomolo->GetMangomoloVideoListByShowID($url_video_category_id, $page = 1, $limit = 16);
			
			$this->data['category_details'] = $mangoVideoCategory_videos->cat;
			$this->data['category_videos'] = $mangoVideoCategory_videos->videos;
			
	       	$this->data['current_user'] = $current_user;
	       	
	       	$this->data['page_title'] = $this->data['page_title'].' : '.$mangoVideoCategory_videos->cat->title_ar;
	       	
       	
       	
       	
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
	       	
       	
       	
	       	$videos_slug = urlencode('فيديوهات');
	       	$this->data['fb_og_type'] = 'Website';
	       	$this->data['fb_og_url'] = base_url().urldecode($videos_slug).'/'.$url_video_category_id;
	       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
	       	$this->data['fb_og_title'] = $this->data['page_title'].' : '.$mangoVideoCategory_videos->cat->title_ar;
	       	$this->data['fb_og_description'] = $this->data['site_description'];
	       	$this->data['fb_og_published_time'] = '';
	       	
	  		$this->render('videos/videos_view');
  			
  		}
		  		
	}
	
	
	// YAWMIYATI MAIN PAGE
	public function yawmiyati_tv($url_video_category_id = NULL)
	{
		
		$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');

  			
		// FEATURED VIDEOS FROM MANGOMOLO
		$mangoFeaturedVideos = $this->mangomolo->GetMangomoloFeaturedVideos(6, 'all');
		$this->data['featured_videos'] = $mangoFeaturedVideos;
		
		
		// VIDEO CATEGORIES FROM MANGOMOLO
		$mangoVideoCategories = $this->mangomolo->GetMangomoloVideoCategoryList();
		$this->data['video_categories'] = $mangoVideoCategories;
		
		$video_category_id = $this->input->post('video_category_id');
		if($url_video_category_id != NULL) {
			$cat_id = $url_video_category_id;
		} elseif($video_category_id != '') {
			$cat_id = $video_category_id;
		} else {
			$cat_id = $mangoVideoCategories[0]->id;
		}
		
		// VIDEOLIST BY CATEGORIES FROM MANGOMOLO
		$mangoVideoCategory_videos = $this->mangomolo->GetMangomoloVideoListByCatID($cat_id, $page = 1, $limit = 12);
		$this->data['category_videos'] = $mangoVideoCategory_videos;
		
		
       	$this->data['current_video_category_id'] = $cat_id;
       	$this->data['current_user'] = $current_user;
       	
       	$this->data['page_title'] = $this->data['page_title'].' : Yawmiyati TV';
       	
       	
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
       	$this->data['fb_og_url'] = base_url().'/yawmiyati-tv';
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['page_title'].' : Yawmiyati TV';
       	$this->data['fb_og_description'] = $this->data['site_description'];
       	$this->data['fb_og_published_time'] = '';
       	
  		$this->render('videos/yawmiyatitv_view');
		  		
	}
	
	
	// CATEGORY VIDEOS LIST PAGE AJAX
	public function categoryvideosAjax()
	{
		
		$current_logged_id = $this->ion_auth->get_user_id();
		$cat_id = $this->input->post("cat_id");
		$page = $this->input->post("page");
		$limit = $this->input->post("limit");
		
		$mangoVideoCategory_videos = $this->mangomolo->GetMangomoloVideoListByCatID($cat_id, $page, $limit);
       	
       	$data_html = '';
       	foreach ($mangoVideoCategory_videos AS $category_video) {
       		$data_html .= '<div class="col-md-3 col-sm-4 content-block-col" data-mh="heightConsistancy">';
				$data_html .= '<div class="content-block">';
					$data_html .= '<div class="img-div">';
						$data_html .= '<a href="'.site_url('yawmiyati-tv/فيديو/'.$category_video->id).'" title="'.$category_video->title_ar.'">';
							$data_html .= '<img title="'.$category_video->title_ar.'" alt="'.$category_video->title_ar.'" src="'.base_url().'assets/site/images/yawmiyati-default.jpg" data-src="http://admango.cdn.mangomolo.com/analytics/'.$category_video->img.'" class="lazyload img-responsive center-block"/>';
							$data_html .= '<div class="overlay"></div>';
							$data_html .= '<div class="overlay-2"></div>';
							$data_html .= '<div class="overlay-text">'.$category_video->title_ar.'</div>';
						$data_html .= '</a>';
					$data_html .= '</div>';
					$data_html .= '<div class="content-div">';
						$data_html .= '<h4><a href="'.site_url('yawmiyati-tv/فيديو/'.$category_video->id).'" title="'.$category_video->title_ar.'">'.$category_video->title_ar.'</a></h4>';
					$data_html .= '</div>';
				$data_html .= '</div>';
			$data_html .= '</div>';
       	}
       	
       	$response = array();
       	$response['csrf_test_name'] = $this->security->get_csrf_hash();
       	$response['page'] = $page + 1;
       	$response['data'] = $data_html;
       	
       	echo json_encode($response);
		exit;
       	
	}
	
	
	/*  VIDEO DETAIL PAGE  */
	public function videodetails($videodetail_alias = NULL, $video_id = NULL)
	{
		
		if(is_null($video_id) || empty($video_id)) {
		
			$this->data['heading'] = 'Notice';
			$this->data['message'] = '<p>There\'s no video.</p>';
			
			$this->render('errors/html/error_404');
			
		} else {
						
			// GET VIDEO DETAILS FROM MANGOMOLO
			$mangoVideoDetails = $this->mangomolo->GetMangomoloVideoDetailsByID($video_id);	
			
			$this->data['video_details'] = $mangoVideoDetails;
		
			// LATEST ARTICLES
			$latest_articles = getNewAddedArticles('', $orderby = 'id', $ordering = 'DESC', $limit_start = '0', $limit_end = '4');
			$this->data['latest_articles'] = $latest_articles;
					
			// MOSTREAD ARTICLES FROM ARTICLE HELPER
       		$mostread_articles = getMostreadArticles('', 'hits', 'DESC', 0, 3);
       		$this->data['mostread_articles'] = $mostread_articles;
			
       		// MOSTREAD ARTICLES FROM ARTICLE HELPER
       		$mostread_articles = getMostreadArticles('', 'hits', 'DESC', 0, 3);
       		$this->data['mostread_articles'] = $mostread_articles;
       		
       		
       		// VIDEOLIST BY CATEGORY ID
       		$related_videos = $this->mangomolo->GetMangomoloVideoListByCatID($mangoVideoDetails->video->category_id, 1, 5);
       		$this->data['related_videos'] = $related_videos;
       		$this->data['related_videos_categoryid'] = $mangoVideoDetails->video->category_id;
			
			
			// BREADCRUMBS
			$this->breadcrumbs->add('الصفحة الرئيسية', base_url());
			$this->breadcrumbs->add('Yawmiyati TV', base_url('yawmiyati-tv/'));
			$this->breadcrumbs->add($mangoVideoDetails->video->title_ar, base_url('yawmiyati-tv/فيديو/'.$video_id));
			$this->data['breadcrumbs'] = $this->breadcrumbs->render();
							
			$this->data['page_title'] = $this->data['site_title'].' : '.$mangoVideoDetails->video->title_ar;
			
			
			
			
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
				</script>
				<script>
				  googletag.cmd.push(function() {
				    googletag.defineSlot('/23636148/Yawmiyati_Mobile_MPU', [300, 250], 'div-gpt-ad-1542802043237-0').addService(googletag.pubads());
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
		       	$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Mobile_MPU -->
				<div id='div-gpt-ad-1542802043237-0' style='height:250px; width:300px; margin: 0 auto;'>
				<script>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802043237-0'); });
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
				</script>
				<script>
				  googletag.cmd.push(function() {
				    googletag.defineSlot('/23636148/Yawmiyati_Desk_MPU_TOP', [300, 250], 'div-gpt-ad-1542803483809-0').addService(googletag.pubads());
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
	       		$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Desk_MPU_TOP -->
				<div id='div-gpt-ad-1542803483809-0' style='height:250px; width:300px; margin: 0 auto;'>
				<script>
				googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542803483809-0'); });
				</script>
				</div>";
	       		
	       	}
       	
       	
			
	       	$this->data['fb_og_type'] = 'Video';
	       	$this->data['fb_og_url'] = base_url('yawmiyati-tv/فيديو/'.$video_id);
	       	$this->data['fb_og_image'] = 'http://admango.cdn.mangomolo.com/analytics/'.$mangoVideoDetails->video->img;
	       	$this->data['fb_og_title'] = $this->data['site_title'].' : '.$mangoVideoDetails->video->title_ar;
	       	$this->data['fb_og_description'] = $this->data['site_description'];
	       	$this->data['fb_og_published_time'] = '';
			
			
			$this->render('videos/detail_view');
			
		}
		
	}
	
	
	
	
	// http://{{site url}}/videos/synchMangovideoToPscmsCURL
	public function synchMangovideoToPscmsCURL($page = 1, $limit = 50) {
		
		/*$url = 'http://admin.mangomolo.com/analytics/index.php/nand/VideosByUser?key=3f4899a8adbbdf36864d7f81ae218f76&user_id=106&p=1&limit=50';
		$result = file_get_contents($url);
		$results = json_decode($result);

		return $results;*/
		
		$url = 'http://admin.mangomolo.com/analytics/index.php/nand/VideosByUser?key=3f4899a8adbbdf36864d7f81ae218f76&user_id=106&p='.$page.'&limit='.$limit; //rss link for the twitter timeline
		
		$ch = curl_init();
		$timeout = 50000;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$results = curl_exec($ch);
		$mangovideos = json_decode($results);
		curl_close($ch);
		
		log_message("debug", count($mangovideos->results)." Records - Auto CURL Called for Mangomolo video to PSCMS System : ".date('Y-m-d H:i:s A'), false);
		
		if(!empty($mangovideos->results)) {
			foreach ($mangovideos->results AS $result) {
				if($this->checkVideoExist($result->id)) {
										
					$title = $result->title_ar;
					$category_id = $result->category_id;
					$video_details = json_encode($result);
					
					$sql = "Update videos set `title`='".addslashes($title)."', `category_id`='".addslashes($category_id)."', `video_details`='".addslashes($video_details)."' where mangovideo_id = '".$result->id."'";
					$val = $this->db->query($sql);
					
					//echo $result->title_ar.' - ('.$result->id.') Video updated successfully.'; 
					
				} else {

					$featured = 0;
					$mangovideo_id = $result->id;
					$category_id = $result->category_id;
					$author_id = '';
					$presenter_id = '';
					$title = $result->title_ar;
					$description = '';
					$video_fileData = '';
					$video_details = json_encode($result);
					//$created_date = $result->create_time;
					$created_date = date('Y-m-d H:i:s');
					$created_by = 1;
					
					$sql = "Insert into videos (`featured`, `mangovideo_id`, `category_id`, `author_id`, `presenter_id`, `title`, `description`, `video_file`, `video_details`, `created_date`, `created_by`) VALUES ('".addslashes($featured)."', '".addslashes($mangovideo_id)."', '".addslashes($category_id)."', '".addslashes($author_id)."', '".addslashes($presenter_id)."', '".addslashes($title)."', '".addslashes($description)."', '".addslashes($video_fileData)."', '".addslashes($video_details)."', '".addslashes($created_date)."', '".addslashes($created_by)."')";
		            $val = $this->db->query($sql);	            
		            $insert_id = $this->db->insert_id();
					
		            // echo $result->title_ar.' - ('.$result->id.') Video imported successfully.';
		            
				}
			}
			
			$page = $page + 1;
			$this->synchMangovideoToPscmsCURL($page, 50);
		}
		
		
	}
	
	
	public function checkVideoExist($mangovideo_id) {
		
		$where = ' WHERE mangovideo_id = '.$mangovideo_id;
  		$qry ='Select * from videos'.$where; // select data from db
       	
       	$video = $this->db->query($qry)->result_array();
       	
       	return count($video);
       	
	}
	
	
	
}
