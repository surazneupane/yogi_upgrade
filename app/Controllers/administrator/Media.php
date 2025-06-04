<?php

namespace App\Controllers\administrator;



class Media extends AdminController
{


	public function __construct()
	{
		parent::__construct();

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
	}

	public function index()
	{

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Media Manager';

		$this->render('administrator/media_view');
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/administrator/dashboard.php */