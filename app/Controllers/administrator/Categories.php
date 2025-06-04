<?php

namespace App\Controllers\administrator;

use App\Libraries\Slug;
use CodeIgniter\Database\Config;

class Categories extends AdminController
{

	public $slugLibrary;
	public $db;

	function __construct()
	{
		parent::__construct();

		//if(!$this->ion_auth->in_group('admin'))
		if (!$this->ionAuthLibrary->in_access('category_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Categories page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];



		$config = array(
			'field' => 'alias',
			'title' => 'title',
			'table' => 'categories',
			'id' => 'id',
		);

		$this->slugLibrary = new Slug($config);
		$this->db = Config::connect();
		// $this->load->library('slug', $config);
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Categories';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		$task = $this->request->getPost('task');
		$category_ids = $this->request->getPost('category_id');
		$ordering_ids = $this->request->getPost('ordering_ids');
		$ordering = $this->request->getPost('ordering');


		if ($task == 'publish') {
			$this->publish($category_ids);
		} elseif ($task == 'unpublish') {
			$this->unpublish($category_ids);
		} elseif ($task == 'delete') {
			$this->delete($category_ids);
		} elseif ($task == 'orderingsave') {
			$this->saveordering($ordering_ids, $ordering);
		}

		$categories = $this->fetchCategoryIndexTree('0', '-', '', $current_user);

		$this->data['categories'] = $categories;
		$this->data['current_user'] = $current_user;
		$this->render('administrator/categories/list_categories_view');
	}


	public function categories_modal($search_val = NULL)
	{

		$current_user = $this->ionAuthModel->user()->getRow();
		$filter['search_val'] = urldecode($search_val);
		session()->set('categories_modal_filter', $filter);

		$categories_modal_filter = session()->get('categories_modal_filter');

		$categories = $this->fetchCategoryIndexTree('0', '-', '', $current_user);

		$this->data['categorieslist_modal'] = $categories;

		$this->render('administrator/categories/list_categories_modal_view');
	}


	public function create()
	{

		if (!$this->ionAuthLibrary->in_access('category_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Add Category.');
			return redirect()->to('administrator/categories');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Create Category';

		$task = $this->request->getPost('task');

		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Category Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Category Alias', 'trim|is_unique[categories.alias]');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Category Title',
				'rules' => 'required'
			],
			'alias' => [
				'label' => 'Category Alias',
				'rules' => 'is_unique[categories.alias]'
			]
		]);


		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} elseif ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$this->data['categorieslist'] = $this->fetchCategoryTree('0', '-', '', '');
			$this->data['userlist'] = $this->userlist();
			$this->data['videos_categorieslist'] = '';
			$this->render('administrator/categories/create_category_view');
		} else {
			$parent_id = $this->request->getPost('parent_id');
			$title = $this->request->getPost('title');
			$title_fr = $this->request->getPost('title_fr');
			if ($title_fr == '') {
				$title_fr = $title;
			}
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
			$alias_frTemp = $this->request->getPost('alias_fr');
			if ($alias_frTemp == '') {
				$data = array(
					'title' => $title_fr,
				);
			} else {
				$data = array(
					'title' => $alias_frTemp,
				);
			}
			$alias_fr = $this->slugLibrary->create_uri($data);
			$color_style = $this->request->getPost('color_style');
			$description = $this->request->getPost('description');
			$description_fr = $this->request->getPost('description_fr');
			$image = $this->request->getPost('image');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');
			$meta_title = $this->request->getPost('meta_title');
			$meta_description = $this->request->getPost('meta_description');
			$category_slide = json_encode($this->request->getPost('category_slide'));

			$sql = "Insert into categories (`parent_id` ,`title` ,`title_fr`, `alias`, `alias_fr`, `color_style` ,`description` ,`description_fr` ,`image` ,`status`, `created_date`, `created_by`, `meta_title`, `meta_description`, `slider_data`) VALUES ('" . addslashes($parent_id) . "', '" . addslashes($title) . "', '" . addslashes($title_fr) . "', '" . addslashes($alias) . "', '" . addslashes($alias_fr) . "', '" . addslashes($color_style) . "', '" . addslashes($description) . "', '" . addslashes($description_fr) . "', '" . addslashes($image) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "', '" . addslashes($meta_title) . "', '" . addslashes($meta_description) . "', '" . addslashes($category_slide) . "')";
			$val = $this->db->query($sql);

			$insert_id = $this->db->insertID();

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Category created successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/categories/edit/' . $insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/categories';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/categories/create';
			} else {
				$redirect_url = 'administrator/categories/edit/' . $insert_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function edit($category_id = NULL, $task = 'save')
	{

		if (!$this->ionAuthLibrary->in_access('category_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit Category.');
			return redirect()->to('administrator/categories');
		}

		$category_id = $this->request->getPost('category_id') ? $this->request->getPost('category_id') : $category_id;
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit Category';
		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Category Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Category Alias', 'trim');


		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Category Title',
				'rules' => 'required'
			],
			// 'alias' => [
			// 	'label' => 'Category Alias',
			// 	'rules' => 'is_unique[categories.alias]'
			// ]
		]);


		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} else if ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$qry = 'Select * from categories where id = ' . $category_id; // select data from db
			$category = $this->db->query($qry)->getResultArray();
			$this->data['category'] = $category[0];

			$this->data['categorieslist'] = $this->fetchCategoryTree('0', '-', '', $category_id);
			$this->data['userlist'] = $this->userlist();
			$this->data['videos_categorieslist'] = '';

			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['category']['created_by'])
			//{
			//	session()->setFlashdata('message_type', 'warning');
			//	session()->setFlashdata('message','You are not allowed to edit this Category.');
			//	return redirect()->to('administrator/categories','refresh');
			//}
			$this->render('administrator/categories/edit_category_view');
		} else {

			$parent_id = $this->request->getPost('parent_id');
			$title = $this->request->getPost('title');
			$title_fr = $this->request->getPost('title_fr');
			if ($title_fr == '') {
				$title_fr = $title;
			}
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
			$alias = $this->slugLibrary->create_uri($data, $category_id);
			$alias_frTemp = $this->request->getPost('alias_fr');
			if ($alias_frTemp == '') {
				$data = array(
					'title' => $title_fr,
				);
			} else {
				$data = array(
					'title' => $alias_frTemp,
				);
			}
			$alias_fr = $this->slugLibrary->create_uri($data, $category_id);
			$color_style = $this->request->getPost('color_style');
			$description = $this->request->getPost('description');
			$description_fr = $this->request->getPost('description_fr');
			$image = $this->request->getPost('image');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');
			$meta_title = $this->request->getPost('meta_title');
			$meta_description = $this->request->getPost('meta_description');
			$category_slide = json_encode($this->request->getPost('category_slide'));

			$sql = "update categories set `parent_id` = '" . addslashes($parent_id) . "', `title`='" . addslashes($title) . "', `title_fr`='" . addslashes($title_fr) . "', `alias`='" . addslashes($alias) . "', `alias_fr`='" . addslashes($alias_fr) . "', `color_style`='" . addslashes($color_style) . "', `description`='" . addslashes($description) . "', `description_fr`='" . addslashes($description_fr) . "', `image`='" . addslashes($image) . "', `status`='" . addslashes($status) . "', `created_date`='" . addslashes($created_date) . "', `created_by`='" . addslashes($created_by) . "', `meta_title`='" . addslashes($meta_title) . "', `meta_description`='" . addslashes($meta_description) . "', `slider_data`='" . addslashes($category_slide) . "' WHERE `id`= '" . $category_id . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Category saved successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/categories/edit/' . $category_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/categories';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/categories/create';
			} else {
				$redirect_url = 'administrator/categories/edit/' . $category_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function unpublish($category_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('category_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Category.');
			return redirect()->to('administrator/categories');
		}

		if (is_null($category_id) || empty($category_id)) {

			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no category to unpublish.');
		} else {

			//$qry ='update categories set `status` = 0 where id = '.$category_id .' OR parent_id = '.$category_id; // select data from db
			if (is_array($category_id)) {
				$category_id = implode('","', $category_id);
				$qry = 'update categories set `status` = 0 where id IN ( "' . $category_id . '")'; // select data from db
			} else {
				$qry = 'update categories set `status` = 0 where id = ' . $category_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Category unpublished successfully.');
		}
		return redirect()->to('administrator/categories');
	}


	public function publish($category_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('category_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Category.');
			return redirect()->to('administrator/categories');
		}

		if (is_null($category_id) || empty($category_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no category to publish.');
		} else {

			//$parent_category_id = $this->getParentcategoryId($category_id);
			//$qry ="update categories set `status` = 1 where id = ".$category_id .' OR id = '.$parent_category_id ; // select data from db
			if (is_array($category_id)) {
				$category_id = implode('","', $category_id);
				$qry = 'update categories set `status` = 1 where id IN ( "' . $category_id . '")'; // select data from db
			} else {
				$qry = 'update categories set `status` = 1 where id = ' . $category_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Category published successfully.');
		}
		return redirect()->to('administrator/categories');
	}

	public function saveordering($ordering_ids = NULL, $ordering = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('category_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to ordering Category.');
			return redirect()->to('administrator/categories');
		}

		$i = 0;
		foreach ($ordering_ids as $ordering_id) {
			$qry = 'update categories set `ordering` = ' . $ordering[$i] . ' where id = ' . $ordering_id; // select data from db
			$val = $this->db->query($qry);
			$i++;
		}

		session()->setFlashdata('message_type', 'success');
		session()->setFlashdata('message', 'Category order saved successfully.');

		return redirect()->to('administrator/categories');
	}


	public function delete($category_id = NULL)
	{
		if (!$this->ionAuthLibrary->in_access('category_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Category.');
			return redirect()->to('administrator/categories');
		}

		if (is_null($category_id) || empty($category_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no category to delete.');
		} else {

			if (!is_array($category_id)) {
				$category_id = array($category_id);
			}

			foreach ($category_id as $cat_id) {
				$subCatagroyExist = $this->checksubCategoryExist($cat_id);
				$checkStatus = $this->checkCategoryExistInArticles($cat_id);
				if ($subCatagroyExist == 0 && $checkStatus == 0) {

					$qry = 'delete from categories where id = ' . $cat_id; // select data from db
					$this->data['categories'] = $this->db->query($qry);

					$message_type = 'success';
					$message = 'Category deleted successfully.';
				} else {

					$message_type = 'warning';
					$message = 'You cant delete parent category. if category have subcategory OR Some category could not deleted, Because they are assinged in article(s).';
				}
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect()->to('administrator/categories');
	}


	public function fetchCategoryTree($parent_id = 0, $spacing = '-', $category_tree_array = '', $exclude_id = '')
	{

		if (!is_array($category_tree_array))

			$category_tree_array = array();

		$qry = "SELECT `id`, `title`, `parent_id` FROM `categories` WHERE 1 AND status = 1 AND `parent_id` = $parent_id ORDER BY id DESC";
		$categorieslist = $this->db->query($qry)->getResultArray();

		if (!empty($categorieslist)) {
			foreach ($categorieslist as $categorylist) {
				if ($categorylist['id'] != $exclude_id) {
					$category_tree_array[] = array("id" => $categorylist['id'], "title" => $spacing . ' ' . $categorylist['title'], "parent_id" => $categorylist['parent_id']);
					//$category_tree_array = $this->fetchCategoryTree($categorylist['id'], $spacing . '-', $category_tree_array, $exclude_id);
				}
			}
		}

		return $category_tree_array;
	}

	public function fetchCategoryIndexTree($parent_id = 0, $spacing = '-', $category_tree_array = '', $current_user)
	{

		if (!is_array($category_tree_array))

			$category_tree_array = array();

		//if($this->ion_auth->in_group('admin')) {
		$qry = "SELECT * FROM `categories` WHERE 1 AND `parent_id` = $parent_id ORDER BY ordering ASC"; // select data from db
		//} else {
		//	$qry ="Select * FROM `categories` WHERE 1 AND `parent_id` = $parent_id AND created_by = $current_user->id ORDER BY ordering ASC"; // select data from db
		//}

		$categorieslist = $this->db->query($qry)->getResultArray();;

		if (!empty($categorieslist)) {
			foreach ($categorieslist as $categorylist) {
				$category_tree_array[] = array("id" => $categorylist['id'], "title" => $spacing . ' ' . $categorylist['title'], "parent_id" => $categorylist['parent_id'], "alias" => $categorylist['alias'], "status" => $categorylist['status'], "ordering" => $categorylist['ordering']);
				$category_tree_array = $this->fetchCategoryIndexTree($categorylist['id'], $spacing . '-', $category_tree_array, $current_user);
			}
		}

		return $category_tree_array;
	}


	public function userlist()
	{

		$qry = 'Select * from users where active = 1'; // select data from db
		$users = $this->db->query($qry)->getResultArray();

		return $users;
	}


	public function checksubCategoryExist($category_id)
	{

		$qry = 'Select * from categories where parent_id = ' . $category_id; // select data from db
		$categories = $this->db->query($qry)->getResultArray();

		return count($categories);
	}


	public function getParentcategoryId($category_id)
	{

		$qry = 'Select parent_id from categories where id = ' . $category_id; // select data from db
		$parentcategory = $this->db->query($qry)->getResultArray();

		return $parentcategory[0]['parent_id'];
	}


	public function checkCategoryExistInArticles($category_id)
	{

		$qry = 'Select * from articles where find_in_set (' . $category_id . ', category_ids) <> 0'; // select data from db       
		$articles = $this->db->query($qry)->getResultArray();

		return count($articles);
	}


	public function getsubcategories()
	{

		$new_csrf_test_name = csrf_hash();
		$main_category_id = $this->request->getPost('main_category_id');

		$qry = 'Select `id`, `title` from categories WHERE `parent_id` = ' . $main_category_id . ' AND `status` = 1 ORDER BY `ordering` ASC'; // select data from db
		$subcategories = $this->db->query($qry)->getResultArray();

		$response = array();
		$response['csrf_test_name'] = $new_csrf_test_name;
		$response['subcategories'] = $subcategories;

		echo json_encode($response);
	}
}
