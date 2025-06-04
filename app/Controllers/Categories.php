<?php
namespace App\Controllers;

use App\Libraries\IonAuth;
use App\Models\IonAuthModel;
use Config\Services;

class Categories extends CustomController {

	public $ionAuthLibrary;
	public $ionAuthModel;

	function __construct()
	{
		parent::__construct();
		// $this->load->library('ion_auth');
		
		// $this->load->helper('categories');
		// $this->load->helper('articles');

		$this->ionAuthLibrary = new IonAuth();
		$this->ionAuthModel = new IonAuthModel();


		$siteConfiguration = siteConfiguration();
		
		if($siteConfiguration['site_caching']) {
       		Services::response()->setCache([
				'max-age' => $siteConfiguration['site_caching_time'] * 60,
			]);
		}
		
		$this->clang = session()->get("lang");
		
		$this->data['current_user'] = $this->ionAuthModel->user()->getRow();
		$this->data['current_user_menu'] = '';
		$this->data['site_title'] = $siteConfiguration['site_title'];
	}
	
		
	// CATEGORY MAIN PAGE
	public function index($category_alias)
	{
		
		$current_user = $this->ionAuthModel->user()->getRow();
  		// $this->load->helper('form');
		
		if($category_alias == '') {
			
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', '404 - Page not found.');
			$this->render('/');
			
		}
		
		$category_details = getCategoryDetailsByAlias($category_alias, $this->clang);
				
       	      	
       	// NEWLY ADDED ARTICLES
       	$newadded_articles = getNewAddedArticles($category_details['id'], 'published_date', 'DESC', 0, 12, $this->clang);
       	$newadded_category_article_link = '';
       	$newadded_articles = array_chunk($newadded_articles, 2);
       	
        $this->data['newadded_articles'] = $newadded_articles;
       	$this->data['newadded_category_article_link'] = $newadded_category_article_link;
       	
       	$this->data['main_category'] = $category_details;
       	//$this->data['sub_categories'] = $sub_categories;
       	$this->data['current_user'] = $current_user;
       	
       	if($category_details['meta_title'] == '') {
       		$page_title = $this->data['page_title'].' : '.$category_details['title'];	
       	} else {
       		$page_title = $category_details['meta_title'];
       	}
       		
       	$page_description = $category_details['meta_description'];
       	
       	$page_title = str_replace('"', '`', $page_title);
   		$page_title = str_replace("'", "'", $page_title);
   		
   		$page_description = str_replace('"', '`', $page_description);
   		$page_description = str_replace("'", "'", $page_description);
       	
       	$this->data['page_title'] = $page_title;
       	$this->data['page_description'] = $page_description;
       	
       	
       	
       	/********************************************/
       	//              ADS BLOCKS
       	/********************************************/
       	
       	
       	if ($this->request->getUserAgent()->isMobile()) {
       	
       		$this->data['before_head'] = "";
       		
	       	$this->data['billboard'] = "";
	       	$this->data['leaderboard'] = "";
	       	
       	} else {
       		
       		$this->data['before_head'] = "";
       		
       		$this->data['billboard'] = "";
       		$this->data['mpu'] = "";
       		$this->data['leaderboard'] = "";
       		
       	}
       	
       	

       	      	
       	
       	$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = $newadded_category_article_link;
       	if($category_details['image'] == '') {
       		$category_details['image'] = 'assets/site/images/yawmiyati-default.jpg';
       	}
       	$this->data['fb_og_image'] = base_url().$category_details['image'];
       	$this->data['fb_og_title'] = $page_title;
       	$this->data['fb_og_description'] = $page_description;
       	$this->data['fb_og_published_time'] = '';
       	
  		$this->render('categories/index_view');
		
	}
}
