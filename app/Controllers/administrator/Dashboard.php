<?php

namespace App\Controllers\administrator;


use App\Libraries\IonAuth;
use CodeIgniter\Database\Config;

class Dashboard extends AdminController
{

	public $ionAuthLibrary;
	public $db;
	public function __construct()
	{
		parent::__construct();

		$this->ionAuthLibrary = new IonAuth();
		$this->db = Config::connect();
		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
	}

	public function index()
	{

		if ($this->ionAuthLibrary->in_group('Registered')) {
			session()->setFlashdata('message_type', 'info');
			session()->setFlashdata('message', 'You are not authorize person to see backend interface.');
			redirect('/', 'refresh');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Dashboard';

		$qry1 = 'Select count(*) AS total_user_count from users'; // select data from db
		$this->data['users'] = $this->db->query($qry1)->getResultArray();

		$qry1 = 'Select count(*) AS total_category_count from categories'; // select data from db
		$this->data['categories'] = $this->db->query($qry1)->getResultArray();

		$qry1 = 'Select count(*) AS total_article_count from articles'; // select data from db
		$this->data['articles'] = $this->db->query($qry1)->getResultArray();

		$qry1 = 'Select count(*) AS total_tag_count from tags'; // select data from db
		$this->data['tags'] = $this->db->query($qry1)->getResultArray();

		$qry1 = 'Select * from articles WHERE created_date >= DATE(NOW()) - INTERVAL 7 DAY ORDER BY id DESC LIMIT 0, 10'; // select data from db
		$this->data['latest_articles'] = $this->db->query($qry1)->getResultArray();

		$qry1 = 'Select * from articles WHERE created_date >= DATE(NOW()) - INTERVAL 7 DAY ORDER BY hits DESC LIMIT 0, 10'; // select data from db
		$this->data['mostread_articles'] = $this->db->query($qry1)->getResultArray();

		$this->render('administrator/dashboard_view');
	}


	public function clearcacheAjax()
	{

		$cache = \Config\Services::cache();
		$cache->clean();

		$response = array();
		$response['status'] = 1;
		$response['csrf_test_name'] = csrf_hash();

		echo json_encode($response);
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/administrator/dashboard.php */