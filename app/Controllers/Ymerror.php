<?php
namespace App\Controllers;

use App\Libraries\IonAuth;
use App\Models\IonAuthModel;
use CodeIgniter\Database\Config;
use Config\Services;

class Ymerror extends CustomController {

	public $ionAuthModel;
	public $ionAuthLibrary;

	function __construct()
	{
		parent::__construct();
		
		// $this->load->helper('articles');
		// $this->load->helper('categories');
		
		$siteConfiguration = siteConfiguration();
		
		if($siteConfiguration['site_caching']) {
			Services::response()->setCache([
				'max-age' => $siteConfiguration['site_caching_time'] * 60,
			]);
       		// $this->response->cache($siteConfiguration['site_caching_time']);
		}

		$this->ionAuthModel = new IonAuthModel();
		$this->ionAuthLibrary = new IonAuth();
		
		$this->data['current_user'] = $this->ionAuthModel->user()->getRow();
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
		
		$this->data['page_title'] = $this->data['site_title'].' : 404 Page not found';
		

		$old_url = urldecode(current_url());				
		$qry = "SELECT `category_ids`, `alias` FROM `articles` WHERE `old_url` = '".$old_url."'";
		$article = Config::connect()->query($qry)->getResultArray();
		if(!empty($article)) {
			$article_link = generateArticleLink($article[0]['category_ids'], $article[0]['alias']);		
			redirect($article_link);
		}

		$this->data['heading'] = 'Notice';
		$this->data['message'] = '<p>Page not found.</p>';
			
		
		$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = base_url();
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['site_title'].' - 404';
       	$this->data['fb_og_description'] = 'Page not found.';
       	$this->data['fb_og_published_time'] = '';
		
		$this->render('errors/html/error_404');
		
	}
	
	
}
