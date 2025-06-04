<?php
namespace App\Controllers;



class Search extends BaseController {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper('articles');
		$siteConfiguration = siteConfiguration();
		
		/*if($siteConfiguration['site_caching']) {
       		$this->output->cache($siteConfiguration['site_caching_time']);
		}*/
		
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
	public function index()
	{
		$query = $this->input->post('query');
		$main_category_id = $this->input->post('main_category_id');
		$main_subcategory_ids = $this->input->post('main_subcategory_ids');
		if($main_subcategory_ids != '') {
			$main_subcategory_ids = implode(',', $main_subcategory_ids);
		}
		
		$results = array();
		if($query != '' || $main_category_id != 0) {
			
			
			$where = '';
			if($query != '') {
				$where .= ' `alias` = "%'.$query.'%" OR `title` LIKE "%'.$query.'%" OR `description` LIKE "%'.$query.'%" AND ';
			}
			if($main_category_id != 0) {
				$where .= ' FIND_IN_SET('.$main_category_id.', `category_ids`) AND ';
			}
			if($main_subcategory_ids != '') {
				$main_subcategory_idArr = explode(',', $main_subcategory_ids);
				$categoryCnt = count($main_subcategory_idArr);
				$i = 1;
				$where .= '(';
				foreach ($main_subcategory_idArr AS $main_subcategory_idAr) {
					if($i == $categoryCnt) {
						$where .= 'FIND_IN_SET('.$main_subcategory_idAr.', `category_ids`) ';
					} else {
						$where .= 'FIND_IN_SET('.$main_subcategory_idAr.', `category_ids`) OR ';
					}
					$i++;
				}
				$where .= ') AND ';
			}
			
			$qry = 'SELECT * FROM `articles` WHERE `status` = 1 AND `published_date` <= "'.date('Y-m-d H:i:s').'" AND '.$where.' 1 ORDER BY `id` DESC LIMIT 0, 6'; // select data from db
			
			$results['articles'] = $this->db->query($qry)->result_array();
			
		} else {
			$results['articles'] = '';
		}
		
		if($query != '' || $main_category_id != 0) {
		
			if($main_category_id != 0) {
				$category_details = getCategoryDetails($main_category_id);
				$mangomolo_category_id = '';
			} else {
				$mangomolo_category_id = '';
			}
						
			// SEARCH VIDEO
       		$searched_videos = '';
       		$results['videos'] = $searched_videos->results->videos;
			
		} else {
			$results['videos'] = '';
		}
		
		
				
		$categorieslist = $this->fetchMainCategoryTree('0', '-', '', '');
		$this->data['categorieslist'] = $this->fetchMainCategoryTree('0', '-', '', '');
		if($main_category_id == '') {			
			$this->data['sub_categorieslist'] = '';
		} else {
			$this->data['sub_categorieslist'] = $this->getsubcategories($main_category_id);
		}
		

		$this->data['query'] = $query;
		$this->data['main_category_id'] = $main_category_id;
		$this->data['main_subcategory_ids'] = $main_subcategory_ids;
		$this->data['results'] = $results;

		$this->data['page_title'] = $this->data['site_title'].' : بحث';
		
		
		
       	
       	
       	
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
       	$this->data['fb_og_url'] = base_url('بحث/');
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['site_title'].' : بحث';
       	$this->data['fb_og_description'] = $this->data['site_description'];
       	$this->data['fb_og_published_time'] = '';
       	
		
		$this->load->library('ion_auth');
		
		$this->render('search/index_view');
		
	}
	
	
	// SEARCHED ARTICLES LIST PAGE AJAX
	public function searchedarticlesAjax()
	{
		
		$query = $this->input->post("query");
		$main_category_id = $this->input->post("main_category_id");
		$main_subcategory_ids = $this->input->post("main_subcategory_ids");
		$limit_start = $this->input->post("limit_start");
		$limit_end = $this->input->post("limit_end");
	
       	$where = '';
		if($query != '') {
			$where .= ' `alias` = "%'.$query.'%" OR `title` LIKE "%'.$query.'%" OR `description` LIKE "%'.$query.'%" AND ';
		}
		if($main_category_id != 0) {
			$where .= ' FIND_IN_SET('.$main_category_id.', `category_ids`) AND ';
		}
		if($main_subcategory_ids != '') {
			$main_subcategory_idArr = explode(',', $main_subcategory_ids);
			$categoryCnt = count($main_subcategory_idArr);
			$i = 1;
			$where .= '(';
			foreach ($main_subcategory_idArr AS $main_subcategory_idAr) {
				if($i == $categoryCnt) {
					$where .= 'FIND_IN_SET('.$main_subcategory_idAr.', `category_ids`) ';
				} else {
					$where .= 'FIND_IN_SET('.$main_subcategory_idAr.', `category_ids`) OR ';
				}
				$i++;
			}
			$where .= ') AND ';
		}
		
		$qry = 'SELECT * FROM `articles` WHERE `status` = 1 AND `published_date` <= "'.date('Y-m-d H:i:s').'" AND '.$where.' 1 ORDER BY `id` DESC LIMIT '.$limit_start.', '.$limit_end; // select data from db
		
		$searched_articles = $this->db->query($qry)->result_array();
		
       	$data_html = '';
       	foreach ($searched_articles AS $searched_article) {
       		
       		// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_link = generateArticleLink($searched_article['category_ids'], $searched_article['alias']); 
			$category_ids = explode(',', $searched_article['category_ids']);
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
			$day = date('l', strtotime($searched_article['published_date']));
			$month = date('F', strtotime($searched_article['published_date']));
			$date = date('d', strtotime($searched_article['published_date']));
			$year = date('Y', strtotime($searched_article['published_date']));
			
			$data_html .= '<div class="col-md-4 content-block-col" data-mh="heightConsistancy">';
				$data_html .= '<div class="content-block">';
					$data_html .= '<div class="content-category" style="border-bottom: 1px solid '.$category_details['color_style'].';">';
						$data_html .= '<span style="background: '.$category_details['color_style'].';">'.$category_details['title'].'</span>';
					$data_html .= '</div>';
					$data_html .= '<div class="img-div">';
						$data_html .= '<a href="'.$article_link.'" title="'.$searched_article['title'].'">';
						
										if($searched_article['image'] == '') {
											$searched_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										} else {
											$searched_article['image'] = 'assets/media/'.$searched_article['image'];
										}
										if($searched_article['article_layout'] == 'text_mulitplemedia' || $searched_article['article_layout'] == 'text_mulitplemedia_6' || $searched_article['article_layout'] == 'fashion') {
											$media_images = json_decode($searched_article['media_images']);
											if($media_images[$searched_article['media_image_main']] == '') {
												$searched_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
											} else {
												$searched_article['image'] = 'assets/media/'.$media_images[$searched_article['media_image_main']];
											}
										}
										
										$file_FCPath = FCPATH.$searched_article['image'];
										if(!file_exists($file_FCPath)) {
											$searched_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
										}
										
							$data_html .= '<img title="'.$searched_article['title'].'" alt="'.$searched_article['title'].'" src="'.base_url().'assets/site/images/yawmiyati-default.jpg" data-src="'.base_url().$searched_article['image'].'" class="lazyload img-responsive center-block"/>';
						$data_html .= '</a>';
					$data_html .= '</div>';
					$data_html .= '<div class="content-div">';
						$data_html .= '<h4><a href="'.$article_link.'" title="'.$searched_article['title'].'" title="'.$searched_article['title'].'">'.$searched_article['title'].'</a></h4>';
						$data_html .= '<time>'.$arabic_week_days[$day].', '.$date.' '.$arabic_months[$month].' '.$year.'</time>';
					$data_html .= '</div>';
				$data_html .= '</div>';
			$data_html .= '</div>';
			
       	}
       	
       	
       	$response = array();
       	$response['csrf_test_name'] = $this->security->get_csrf_hash();
       	$response['query'] = $query;
       	$response['main_category_id'] = $main_category_id;
       	$response['main_subcategory_ids'] = $main_subcategory_ids;
       	$response['limit_start'] = $limit_start + $limit_end;
       	$response['data'] = $data_html;
       	
       	echo json_encode($response);
		exit;
		
	}
	
	
	// SEARCHED VIDEOS LIST PAGE AJAX
	public function searchedvideosAjax()
	{
		
		$query = $this->input->post("query");
		$main_category_id = $this->input->post("main_category_id");
		$limit_start = $this->input->post("limit_start");
		$limit_end = $this->input->post("limit_end");
		
       	// SEARCH VIDEO
   		$searched_videos = '';
   		$mangoSearchedVideos = '';
       	
       	$data_html = '';
       	
       	
       	$response = array();
       	$response['query'] = $query;
       	$response['main_category_id'] = $main_category_id;
       	$response['csrf_test_name'] = $this->security->get_csrf_hash();
       	$response['limit_start'] = $limit_start + 1;
       	$response['limit_end'] = 4;
       	$response['data'] = $data_html;
       	
       	echo json_encode($response);
		exit;
		
	}
	
	
	public function fetchMainCategoryTree($parent_id = 0, $spacing = '-', $category_tree_array = '', $exclude_id = '') {
 		
		if (!is_array($category_tree_array))
				
			$category_tree_array = array();
		
			$qry = "SELECT `id`, `title`, `parent_id` FROM `categories` WHERE 1 AND status = 1 AND `parent_id` = $parent_id ORDER BY id DESC";
			$categorieslist = $this->db->query($qry)->result_array();
			
			if (!empty($categorieslist)) {
				foreach ($categorieslist AS $categorylist) {
					if($categorylist['id'] != $exclude_id) {
						$category_tree_array[] = array("id" => $categorylist['id'], "title" => $spacing . ' '.$categorylist['title'], "parent_id" => $categorylist['parent_id']);					}
				}
			}
			
		return $category_tree_array;
	}
	
	
	public function getsubcategories($main_category_id) {
				
       	$qry ='Select `id`, `title` from categories WHERE `parent_id` = '.$main_category_id.' AND `status` = 1 ORDER BY `ordering` ASC'; // select data from db       	
       	$subcategories = $this->db->query($qry)->result_array();
       	
        return $subcategories;
	}
	
		
	public function getsubcategorieslist() {
		
		$new_csrf_test_name = $this->security->get_csrf_hash();
		$main_category_id = $this->input->post('main_category_id');
		
       	$qry ='Select `id`, `title` from categories WHERE `parent_id` = '.$main_category_id.' AND `status` = 1 ORDER BY `ordering` ASC'; // select data from db       	
       	$subcategories = $this->db->query($qry)->result_array();
       	
       	$response = array();
       	$response['csrf_test_name'] = $new_csrf_test_name;
       	$response['subcategories'] = $subcategories;
       	
       	echo json_encode($response);
	}
	
}
