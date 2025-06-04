<?php


namespace App\Controllers\administrator;

use CodeIgniter\Database\Config;

class HomeConfiguration extends AdminController
{

	public $db;
	function __construct()
	{
		parent::__construct();

		if (!$this->ionAuthLibrary->in_group('admin')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Home Configuration page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$siteConfiguration = siteConfiguration();

		$this->data['page_title'] = $siteConfiguration['site_title'];

		$this->db = Config::connect();
	}

	public function edit($task = 'save')
	{
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Home Configuration';
		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('section_1_data', 'Section 1', 'required');
		// $this->form_validation->set_rules('section_2_data', 'Section 2', 'required');
		// $this->form_validation->set_rules('section_3_data', 'Section 3', 'required');

		$validation = \Config\Services::validation();
		$validation->setRules([
			'section_1_data' => [
				'label' => 'Section 1',
				'rules' => 'required'
			],
			'section_2_data' => [
				'label' => 'Section 2',
				'rules' => 'required'
			],
			'section_3_data' => [
				'label' => 'Section 3',
				'rules' => 'required'
			]
		]);

		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		}

		if ($this->request->getMethod() == "GET") {

			// $this->load->helper('form');
			$qry = 'SELECT * FROM home_configuration'; // select data from db
			$home_configurations = $this->db->query($qry)->getResultArray();

			$this->data['videos_categorieslist'] = '';
			$this->data['home_configuration'] = $home_configurations[0];

			$this->data['articles_categorieslist'] = $this->fetchCategoryTree('0', '-', '', '');


			if (!$this->ionAuthLibrary->in_group('admin')) {
				session()->setFlashdata('message_type', 'warning');
				session()->setFlashdata('message', 'You are not allowed to edit this Site Configurations.');
				return redirect()->to('administrator/dashboard');
			}
			$this->render('administrator/home_configurations/edit_home_configuration_view');
		} else {

			$section_1_data = $this->request->getPost('section_1_data');
			$section_1_data_fr = $this->request->getPost('section_1_data_fr');
			$section_2_data = $this->request->getPost('section_2_data');
			$section_2_data_fr = $this->request->getPost('section_2_data_fr');
			$section_3_data = $this->request->getPost('section_3_data');
			$section_3_data_fr = $this->request->getPost('section_3_data_fr');

			$slider_data = json_encode($this->request->getPost('homepage_slide'));

			$sql = "UPDATE home_configuration SET `section_1_data` = '" . addslashes($section_1_data) . "', `section_1_data_fr` = '" . addslashes($section_1_data_fr) . "', `section_2_data` = '" . addslashes($section_2_data) . "', `section_2_data_fr` = '" . addslashes($section_2_data_fr) . "', `section_3_data` = '" . addslashes($section_3_data) . "', `section_3_data_fr` = '" . addslashes($section_3_data_fr) . "', `slider_data` = '" . addslashes($slider_data) . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Home Configuration saved successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/home_configurations/edit/';
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/dashboard';
			} else {
				$redirect_url = 'administrator/home_configurations/edit/';
			}

			return redirect()->to($redirect_url);
		}
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
					$category_tree_array = $this->fetchCategoryTree($categorylist['id'], $spacing . '-', $category_tree_array, $exclude_id);
				}
			}
		}

		return $category_tree_array;
	}
}
