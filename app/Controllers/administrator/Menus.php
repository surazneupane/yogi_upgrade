<?php


namespace App\Controllers\administrator;

use App\Libraries\Slug;
use CodeIgniter\Database\Config;

class Menus extends AdminController
{

	public $slugLibrary;
	public $db;

	function __construct()
	{
		parent::__construct();

		if (!$this->ionAuthLibrary->in_access('menu_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Menus page');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];

		//if(!$this->ion_auth->in_group('admin'))


		$config = array(
			'field' => 'alias',
			'title' => 'title',
			'table' => 'menus',
			'id' => 'id',
		);

		$this->slugLibrary = new Slug($config);

		$this->db = Config::connect();
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Menus';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$menu_ids = $this->request->getPost('menu_id');
		$ordering_ids = $this->request->getPost('ordering_ids');
		$ordering = $this->request->getPost('ordering');

		if ($task == 'publish') {
			$this->publish($menu_ids);
		} elseif ($task == 'unpublish') {
			$this->unpublish($menu_ids);
		} elseif ($task == 'delete') {
			$this->delete($menu_ids);
		} elseif ($task == 'orderingsave') {
			$this->saveordering($ordering_ids, $ordering);
		}

		if (!empty($filter)) {
			session()->set('menus_filter', $filter);
		}

		$where = ' WHERE 1';
		$menus_filter = session()->get('menus_filter');

		if (isset($menus_filter['status']) && $menus_filter['status'] != '') {
			$where .= ' AND status = ' . $menus_filter['status'];
		}

		/*if(!$this->ion_auth->in_group('admin')) {
	  		$where .= ' AND created_by = '.$current_user->id;
  		}*/

		$menu_type = array('custom_link' => 'Custom Link', 'article' => 'Article', 'category' => 'Article Category', 'tag' => 'Article Tag', 'video_category' => 'Videos Category');

		$this->data['menus'] = $this->fetchMenuIndexTree('0', '', '', $current_user, $where);
		$this->data['menu_type'] = $menu_type;
		$this->data['current_user'] = $current_user;
		$this->render('administrator/menus/list_menus_view');
	}


	public function create()
	{
		if (!$this->ionAuthLibrary->in_access('menu_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Add Menu.');
			return redirect()->to('administrator/menus');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Create Menu';

		$task = $this->request->getPost('task');

		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Menu Title', 'trim|required');
		// $this->form_validation->set_rules('title_fr', 'FR Menu Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Menu Alias', 'trim|is_unique[articles.alias]');
		// $this->form_validation->set_rules('menu_type', 'Menu Type', 'required');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Menu Title',
				'rules' => 'required'
			],
			'title_fr' => [
				'label' => 'FR Menu Title',
				'rules' => 'required'
			],
			'alias' => [
				'label' => 'Menu Alias',
				'rules' => 'is_unique[articles.alias]'
			],
			'menu_type' => [
				'label' => 'Menu Type',
				'rules' => 'required'
			]
		]);


		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} elseif ($this->request->getMethod() == "GET") {
			$this->data['menuslist'] = $this->fetchMenuTree('0', '-', '', '');
			$this->data['categorieslist'] = $this->fetchCategoryTree('0', '-', '', '');
			$mangoVideoCategories = [];
			$this->data['video_categorieslist'] = $mangoVideoCategories;
			$this->data['articleslist'] = $this->articleslist();
			$this->data['tagslist'] = $this->tagslist();
			$this->data['userlist'] = $this->userlist();
			$this->render('administrator/menus/create_menu_view');
		} else {
			$parent_id = $this->request->getPost('parent_id');
			$title = $this->request->getPost('title');
			$title_fr = $this->request->getPost('title_fr');
			$aliasTemp = $this->request->getPost('alias');
			if ($aliasTemp == '') {
				$data = array(
					'title' => $title,
				);
			} else {
				$data = array(
					'title' => $aliasTemp,
				);
			}
			$alias = $this->slugLibrary->create_uri($data);
			$menu_type = $this->request->getPost('menu_type');
			$menu_category_alias = $this->request->getPost('menu_category_alias');
			$menu_category_alias_fr = $this->getCategoryFrAlias($menu_category_alias);
			$menu_article_alias = $this->request->getPost('menu_article_alias');
			$menu_article_alias_fr = $this->getArticleFrAlias($menu_article_alias);
			$menu_tag_alias = $this->request->getPost('menu_tag_alias');
			$menu_custom_link = $this->request->getPost('menu_custom_link');
			$menu_custom_link_fr = $this->request->getPost('menu_custom_link_fr');
			if ($menu_custom_link_fr == '') {
				$menu_custom_link_fr = $menu_custom_link;
			}
			$menu_video_category_id = $this->request->getPost('menu_video_category_id');
			$menu_class = $this->request->getPost('menu_class');
			$menu_target = $this->request->getPost('menu_target');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');

			$sql = "Insert into menus (`parent_id`, `title`, `title_fr`, `alias`, `menu_type`, `menu_category_alias`, `menu_category_alias_fr`, `menu_article_alias`, `menu_article_alias_fr`, `menu_tag_alias`, `menu_custom_link`, `menu_custom_link_fr`, `menu_video_category_id`, `menu_class`, `menu_target`, `status`, `created_date`, `created_by`) VALUES ('" . addslashes($parent_id) . "', '" . addslashes($title) . "', '" . addslashes($title_fr) . "', '" . addslashes($alias) . "', '" . addslashes($menu_type) . "', '" . addslashes($menu_category_alias) . "', '" . addslashes($menu_category_alias_fr) . "', '" . addslashes($menu_article_alias) . "', '" . addslashes($menu_article_alias_fr) . "', '" . addslashes($menu_tag_alias) . "', '" . addslashes($menu_custom_link) . "', '" . addslashes($menu_custom_link_fr) . "', '" . addslashes($menu_video_category_id) . "', '" . addslashes($menu_class) . "', '" . addslashes($menu_target) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "')";

			$val = $this->db->query($sql);

			$insert_id = $this->db->insertID();

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Menu created successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/menus/edit/' . $insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/menus';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/menus/create';
			} else {
				$redirect_url = 'administrator/menus/edit/' . $insert_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function edit($menu_id = NULL, $task = 'save')
	{

		if (!$this->ionAuthLibrary->in_access('menu_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit Menu.');
			return redirect()->to('administrator/menus');
		}

		$menu_id = $this->request->getPost('menu_id') ? $this->request->getPost('menu_id') : $menu_id;
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit Menu';
		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Menu Title', 'trim|required');
		// $this->form_validation->set_rules('title_fr', 'FR Menu Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Menu Alias', 'trim');
		// $this->form_validation->set_rules('menu_type', 'Menu Type', 'required');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Menu Title',
				'rules' => 'required'
			],
			'title_fr' => [
				'label' => 'FR Menu Title',
				'rules' => 'required'
			],
			// 'alias' => [
			// 	'label' => 'Menu Alias',
			// 	'rules' => 'is_unique[articles.alias]'
			// ],
			'menu_type' => [
				'label' => 'Menu Type',
				'rules' => 'required'
			]
		]);

		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} else if ($this->request->getMethod() == "GET") {

			// $this->load->helper('form');
			$qry = 'Select * from menus where id = ' . $menu_id; // select data from db
			$menu = $this->db->query($qry)->getResultArray();

			if (!empty($menu)) {
				$this->data['menu'] = $menu[0];
				$this->data['menuslist'] = $this->fetchMenuTree('0', '-', '', $menu_id);
				$this->data['categorieslist'] = $this->fetchCategoryTree('0', '-', '', '');

				$mangoVideoCategories = [];
				$this->data['video_categorieslist'] = $mangoVideoCategories;

				$this->data['articleslist'] = $this->articleslist();
				$this->data['tagslist'] = $this->tagslist();
				$this->data['userlist'] = $this->userlist();

				//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['menu']['created_by'])
				//{
				//	session()->setFlashdata('message_type', 'warning');
				//	session()->setFlashdata('message','You are not allowed to edit this Menu.');
				//	return redirect()->to('administrator/categories','refresh');
				//}
				$this->render('administrator/menus/edit_menu_view');
			} else {
				session()->setFlashdata('message_type', 'danger');
				session()->setFlashdata('message', 'Sorry. There\'s no record.');
				$redirect_url = 'administrator/menus';
				return redirect()->to($redirect_url);
			}
		} else {
			$parent_id = $this->request->getPost('parent_id');
			$title = $this->request->getPost('title');
			$title_fr = $this->request->getPost('title_fr');
			$aliasTemp = $this->request->getPost('alias');
			if ($aliasTemp == '') {
				$data = array(
					'title' => $title,
				);
			} else {
				$data = array(
					'title' => $aliasTemp,
				);
			}
			$alias = $this->slugLibrary->create_uri($data, $menu_id);
			$menu_type = $this->request->getPost('menu_type');
			$menu_category_alias = $this->request->getPost('menu_category_alias');
			$menu_category_alias_fr = $this->getCategoryFrAlias($menu_category_alias);
			$menu_article_alias = $this->request->getPost('menu_article_alias');
			$menu_article_alias_fr = $this->getArticleFrAlias($menu_article_alias);
			$menu_tag_alias = $this->request->getPost('menu_tag_alias');
			$menu_custom_link = $this->request->getPost('menu_custom_link');
			$menu_custom_link_fr = $this->request->getPost('menu_custom_link_fr');
			if ($menu_custom_link_fr == '') {
				$menu_custom_link_fr = $menu_custom_link;
			}
			$menu_video_category_id = $this->request->getPost('menu_video_category_id');
			$menu_class = $this->request->getPost('menu_class');
			$menu_target = $this->request->getPost('menu_target');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');

			$sql = "update menus set `parent_id`='" . addslashes($parent_id) . "', `title`='" . addslashes($title) . "', `title_fr`='" . addslashes($title_fr) . "', `alias`='" . addslashes($alias) . "', `menu_type`='" . addslashes($menu_type) . "', `menu_category_alias`='" . addslashes($menu_category_alias) . "', `menu_category_alias_fr`='" . addslashes($menu_category_alias_fr) . "', `menu_article_alias`='" . addslashes($menu_article_alias) . "', `menu_article_alias_fr`='" . addslashes($menu_article_alias_fr) . "', `menu_tag_alias`='" . addslashes($menu_tag_alias) . "', `menu_custom_link`='" . addslashes($menu_custom_link) . "', `menu_custom_link_fr`='" . addslashes($menu_custom_link_fr) . "', `menu_video_category_id`='" . addslashes($menu_video_category_id) . "', `menu_class`='" . addslashes($menu_class) . "', `menu_target`='" . addslashes($menu_target) . "', `status`='" . addslashes($status) . "', `created_date`='" . addslashes($created_date) . "', `created_by`='" . addslashes($created_by) . "' where id= '" . $menu_id . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Menu saved successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/menus/edit/' . $menu_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/menus';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/menus/create';
			} else {
				$redirect_url = 'administrator/menus/edit/' . $menu_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function unpublish($menu_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('menu_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Menu.');
			return redirect()->to('administrator/menus');
		}

		if (is_null($menu_id) || empty($menu_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no menu to unpublish.');
		} else {

			if (is_array($menu_id)) {
				$menu_id = implode('","', $menu_id);
				$qry = 'update menus set `status` = 0 where id IN ( "' . $menu_id . '")'; // select data from db
			} else {
				$qry = 'update menus set `status` = 0 where id = ' . $menu_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Menu unpublished successfully.');
		}
		return redirect()->to('administrator/menus');
	}


	public function publish($menu_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('menu_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Menu.');
			return redirect()->to('administrator/menus');
		}

		if (is_null($menu_id) || empty($menu_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no menu to publish.');
		} else {

			if (is_array($menu_id)) {
				$menu_id = implode('","', $menu_id);
				$qry = 'update menus set `status` = 1 where id IN ( "' . $menu_id . '")'; // select data from db
			} else {
				$qry = 'update menus set `status` = 1 where id = ' . $menu_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Menu published successfully.');
		}
		return redirect()->to('administrator/menus');
	}


	public function saveordering($ordering_ids = NULL, $ordering = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('menu_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to ordering Menu.');
			return redirect()->to('administrator/menus');
		}

		$i = 0;
		foreach ($ordering_ids as $ordering_id) {
			$qry = 'update menus set `ordering` = ' . $ordering[$i] . ' where id = ' . $ordering_id; // select data from db		
			$val = $this->db->query($qry);
			$i++;
		}

		session()->setFlashdata('message_type', 'success');
		session()->setFlashdata('message', 'Menu order saved successfully.');

		return redirect()->to('administrator/menus');
	}


	public function delete($menu_id = NULL)
	{
		if (!$this->ionAuthLibrary->in_access('menu_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Menu.');
			return redirect()->to('administrator/menus');
		}

		if (is_null($menu_id) || empty($menu_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no menu to delete.');
		} else {

			if (is_array($menu_id)) {
				$menu_id = implode('","', $menu_id);
				$qry = 'delete from menus where id IN ( "' . $menu_id . '")'; // select data from db
			} else {
				$qry = 'delete from menus where id = ' . $menu_id; // select data from db
			}

			$this->data['menus'] = $this->db->query($qry);

			$message_type = 'success';
			$message = 'Menu deleted successfully.';

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect()->to('administrator/menus');
	}


	public function fetchMenuTree($parent_id = 0, $spacing = '-', $menu_tree_array = '', $exclude_id = '')
	{

		if (!is_array($menu_tree_array))

			$menu_tree_array = array();

		$qry = "SELECT `id`, `title`, `parent_id` FROM `menus` WHERE 1 AND status = 1 AND `parent_id` = $parent_id ORDER BY ordering ASC";
		$menuslist = $this->db->query($qry)->getResultArray();

		if (!empty($menuslist)) {
			foreach ($menuslist as $menulist) {
				if ($menulist['id'] != $exclude_id) {
					$menu_tree_array[] = array("id" => $menulist['id'], "title" => $spacing . ' ' . $menulist['title'], "parent_id" => $menulist['parent_id']);
					$menu_tree_array = $this->fetchMenuTree($menulist['id'], $spacing . '-', $menu_tree_array, $exclude_id);
				}
			}
		}

		return $menu_tree_array;
	}

	public function fetchMenuIndexTree($parent_id = 0, $spacing = '', $menu_tree_array = '', $current_user, $where)
	{

		if (!is_array($menu_tree_array))

			$menu_tree_array = array();

		//if($this->ion_auth->in_group('admin')) {
		$qry = "SELECT * FROM `menus` " . $where . " AND `parent_id` = $parent_id ORDER BY ordering ASC"; // select data from db
		//} else {
		//	$qry ="Select * FROM `menus` ".$where." AND `parent_id` = $parent_id AND created_by = $current_user->id ORDER BY ordering ASC"; // select data from db
		//}

		$menuslist = $this->db->query($qry)->getResultArray();;

		if (!empty($menuslist)) {
			foreach ($menuslist as $menulist) {
				$menu_tree_array[] = array("id" => $menulist['id'], "title" => $spacing . ' ' . $menulist['title'], "parent_id" => $menulist['parent_id'], "alias" => $menulist['alias'], "menu_type" => $menulist['menu_type'], "status" => $menulist['status'], "created_date" => $menulist['created_date'], "ordering" => $menulist['ordering']);
				$menu_tree_array = $this->fetchMenuIndexTree($menulist['id'], $spacing . '-', $menu_tree_array, $current_user, $where);
			}
		}

		return $menu_tree_array;
	}

	public function fetchCategoryTree($parent_id = 0, $spacing = '-', $category_tree_array = '', $exclude_id = '')
	{

		if (!is_array($category_tree_array))

			$category_tree_array = array();

		$qry = "SELECT `id`, `alias`, `title`, `parent_id` FROM `categories` WHERE 1 AND status = 1 AND `parent_id` = $parent_id ORDER BY id DESC";
		$categorieslist = $this->db->query($qry)->getResultArray();;

		if (!empty($categorieslist)) {
			foreach ($categorieslist as $categorylist) {
				if ($categorylist['id'] != $exclude_id) {
					$category_tree_array[] = array("id" => $categorylist['id'], "alias" => $categorylist['alias'], "title" => $spacing . ' ' . $categorylist['title'], "parent_id" => $categorylist['parent_id']);
					$category_tree_array = $this->fetchCategoryTree($categorylist['id'], $spacing . '-', $category_tree_array, $exclude_id);
				}
			}
		}

		return $category_tree_array;
	}


	public function articleslist()
	{

		$qry = 'Select * from articles where status = 1 Order By id desc'; // select data from db
		$articlesArr = $this->db->query($qry)->getResultArray();

		$articles = array();
		$i = 0;
		foreach ($articlesArr as $articlesAr) {
			$qry = 'Select `title` from categories where id IN (' . $articlesAr['category_ids'] . ')'; // select data from db
			$categories = $this->db->query($qry)->getResultArray();
			$categoryArr = array();
			foreach ($categories as $category) {
				$categoryArr[] = $category['title'];
			}
			$categoryStr = implode(', ', $categoryArr);
			$articles[$i]['id'] = $articlesAr['id'];
			$articles[$i]['alias'] = $articlesAr['alias'];
			$articles[$i]['title'] = $articlesAr['title'];
			$articles[$i]['categories'] = $categoryStr;
			$i++;
		}

		return $articles;
	}


	public function tagslist()
	{

		$qry = 'Select * from tags where status = 1 Order By id desc'; // select data from db
		$tags = $this->db->query($qry)->getResultArray();

		return $tags;
	}

	public function userlist()
	{

		$qry = 'Select * from users where active = 1'; // select data from db
		$users = $this->db->query($qry)->getResultArray();

		return $users;
	}


	public function getArticleFrAlias($alias)
	{

		$qry = 'Select alias_fr from articles where alias = "' . $alias . '"'; // select data from db
		$articles = $this->db->query($qry)->getResultArray();

		return $articles[0]['alias_fr'];
	}


	public function getCategoryFrAlias($alias)
	{

		$qry = 'Select alias_fr from categories where alias = "' . $alias . '"'; // select data from db
		$categories = $this->db->query($qry)->getResultArray();

		return $categories[0]['alias_fr'];
	}
}
