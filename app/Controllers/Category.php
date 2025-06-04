<?php

namespace App\Controllers;

use App\Libraries\IonAuth;
use App\Models\IonAuthModel;

class Category extends CustomController
{


	public $ionAuthLibrary;
	public $ionAuthModel;

	function __construct()
	{
		parent::__construct();
		$this->ionAuthLibrary = new IonAuth();
		$this->ionAuthModel = new IonAuthModel();
		// $this->load->library('ion_auth');
		$this->data['current_user'] = $this->ionAuthModel->user()->getRow();
		$this->data['current_user_menu'] = '';
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
	public function article_display($a, $b, $c)
	{
		echo "main " . urldecode($a) . "<br>";
		echo "sub" . urldecode($b) . "<br>";
		echo "articl" . urldecode($c) . "<br>";
		$this->render('article');
	}
	public function sub_category_display($a, $b)
	{
		echo "main " . urldecode($a) . "<br>";
		echo "sub" . urldecode($b) . "<br>";
		//echo "articl".urldecode($c)."<br>";
		$this->render('article');
	}
	public function main_category_display($a)
	{
		echo "main " . urldecode($a) . "<br>";
		//echo "sub".urldecode($b)."<br>";
		//echo "articl".urldecode($c)."<br>";
		$this->render('article');
	}
}
