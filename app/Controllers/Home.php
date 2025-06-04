<?php

namespace App\Controllers;

use App\Models\HomeModel;
use App\Models\IonAuthModel;

class Home extends CustomController
{

	public $homeModel;
	public $ionAuthLibrary;
	// public $clang;
	// public $data = [];
	function __construct()
	{
		parent::__construct();
		$this->homeModel = new HomeModel();
		$this->ionAuthLibrary = new IonAuthModel();
		// $this->load->helper('categories');
		// $this->load->helper('articles');
		$siteConfiguration = siteConfiguration();
		$this->clang = session()->get("lang");
		$this->data['current_user'] = $this->ionAuthLibrary->user()->getRow();
		$this->data['current_user_menu'] = '';
		$this->data['site_title'] = $siteConfiguration['site_title'];
		$this->data['site_description'] = $siteConfiguration['site_description'];
	}

	public function index()
	{
		
		$this->data['page_title'] = $this->data['site_title'] . ' : Home';

		// HOME PAGE CONFIGURATION FROM GLOBAL HELPER
		$home_configurations = homeConfiguration($this->clang);
		$section_1_data = $home_configurations['section_1_data'];
		$section_2_data = $home_configurations['section_2_data'];
		$section_3_data = $home_configurations['section_3_data'];
		$slider_data = json_decode($home_configurations['slider_data']);

		$filtered_slider_data = array();

		if (!empty($slider_data)) {
			foreach ($slider_data as $slide) {
				$currentdate = date('Y-m-d H:i:s');
				if (strtotime($currentdate) >= strtotime($slide->on_date) && strtotime($currentdate) <= strtotime($slide->off_date)) {
					$filtered_slider_data[] = $slide;
				}
			}
		}

		//$slider_data = array_chunk($filtered_slider_data, 3);
		$slider_data = $filtered_slider_data;


		// ALL CATEGORIES 
		/*$qry ='SELECT `id`, `title`, `alias`, `color_style`, `description`, `image` FROM `categories` WHERE `parent_id` = 0 AND `status` = "1"'; // select data from db
       	$parent_categories_data = $this->db->query($qry)->result_array();
       	$parent_categories_data = array_chunk($parent_categories_data, 3);*/


		// FEATURED ARTICLES FROM ARTICLE HELPER
		//$featured_articles = getFeaturedArticles('', 'id', 'DESC', 0, 4);
		$latest_articles = getNewAddedArticles(2, 'published_date', 'DESC', 0, 4, $this->clang);


		// MOSTREAD ARTICLES FROM ARTICLE HELPER
		$mostread_articles = getMostreadArticles('', 'hits', 'DESC', 0, 3);


		// RECENT VIDEOLIST BY SHOW ID / CATEGORY ID
		$recent_videos = '';


		// MOST VIEWED VIDEOLIST BY CATEGORY ID
		$mostviewed_videos = '';
		$most_viewed_video_category_title = '';



		// NEWLY ADDED ARTICLES
		//$newadded_articles = getNewAddedArticles($new_articles_category_id, 'id', 'DESC', 0, 3);
		//$newadded_category_article_link = generateCategoryLink($new_articles_category_id);
		$newadded_articles = getNewAddedArticles('', 'published_date', 'DESC', 4, 6);

		$this->data['slider_data'] = $slider_data;
		//$this->data['featured_articles'] = $featured_articles;
		$this->data['latest_articles'] = $latest_articles;
		$this->data['mostread_articles'] = $mostread_articles;
		$this->data['section_1_data'] = $section_1_data;
		$this->data['section_2_data'] = $section_2_data;
		$this->data['section_3_data'] = $section_3_data;
		$this->data['newadded_articles'] = $newadded_articles;
		//$this->data['newadded_category_article_link'] = $newadded_category_article_link;




		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
			$this->data['mpu'] = "";
		} else {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
			$this->data['mpu'] = "";
			$this->data['leaderboard'] = "";
		}




		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url();
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'];
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->render('home');
	}
}
