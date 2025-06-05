<?php

namespace App\Controllers;

use App\Libraries\IonAuth;
use CodeIgniter\Database\Config;
use Config\Services;

class CustomController extends BaseController
{

	protected $data = [];

	public $clang;

	public function __construct()
	{

		// if (session()->get('lang') == "es") {
		// 	$lang = "es";
		// 	Services::request()->setLocale($lang);
		// 	session()->set("lang", 'es');
		// } elseif (session()->get('lang') == "fr") {
		// 	$lang = "fr";
		// 	Services::request()->setLocale($lang);
		// 	session()->set("lang", 'fr');
		// } elseif (session()->get('lang') == "en") {
		// 	$lang = "english";
		// 	Services::request()->setLocale($lang);
		// 	session()->set("lang", 'en');
		// } else {
		// 	$lang = "french";
		// 	Services::request()->setLocale($lang);
		// 	session()->set("lang", 'fr');
		// }

		$this->clang = $lang = session()->get('lang') ?? 'fr';
		session()->set('lang',$lang);
		Services::request()->setLocale($lang);


		// $this->lang->load('information', $lang);

		// $this->load->helper('form');
		// $this->load->helper('menu');
		// $this->load->helper('articles');
		// $this->load->helper('categories');
		// $this->load->library('ion_auth');

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
		$this->data['page_description'] = $siteConfiguration['site_description'];
		$this->data['before_head'] = '';
		$this->data['before_body'] = '';
	}


	protected function render($the_view = NULL, $template = 'master')
	{

		$this->clang = session()->get("lang");
		$this->data['clang'] = $this->clang;
		$siteConfiguration = siteConfiguration();

		if ($template == 'json' || $this->request->isAJAX()) {
			header('Content-Type: application/json');
			echo json_encode($this->data);
		} else {
			if ($siteConfiguration['site_offline'] == 1 && $template == 'master') {
				$this->data['site_title'] = $siteConfiguration['site_title'];
				$this->data['site_description'] = $siteConfiguration['site_description'];
				$this->data['site_offlinemessage'] = $siteConfiguration['site_offlinemessage'];
				$this->data['site_offlineimage'] = $siteConfiguration['site_offlineimage'];
				echo view('templates/offline_view', $this->data);
			} else {

				if ($template == 'master') {
					if ($this->clang == 'fr') {
						$qry = "SELECT *, title_fr AS title, menu_category_alias_fr AS menu_category_alias, menu_article_alias_fr AS menu_article_alias, menu_custom_link_fr AS menu_custom_link FROM `menus` WHERE status = 1 ORDER BY Ordering ASC";
					} else {
						$qry = "SELECT * FROM `menus` WHERE status = 1 ORDER BY Ordering ASC";
					}

					$menuslist = Config::connect()->query($qry)->getResultArray();
					$this->data['mainmenu_tree_array'] = $this->ordered_menu(0, $menuslist);
				}

				$this->data['the_view_content'] = (is_null($the_view)) ? '' : view($the_view, $this->data);
				echo view('templates/' . $template . '_view', $this->data);
			}
		}
	}


	public function ordered_menu($parent_id = '', $menu_tree_array = [])
	{
		$temp_array = array();
		foreach ($menu_tree_array as $element) {
			if ($element['parent_id'] == $parent_id) {
				$submenu = $this->ordered_menu($element['id'], $menu_tree_array);
				if (!empty($submenu)) {
					$element['submenu'] = $submenu;
				}
				$temp_array[] = $element;
			}
		}
		return $temp_array;
	}
}


class PublicController extends CustomController
{

	function __construct()
	{
		parent::__construct();
	}
}
