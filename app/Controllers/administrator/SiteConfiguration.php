<?php

namespace App\Controllers\administrator;

use CodeIgniter\Database\Config;

class SiteConfiguration extends AdminController
{
	public $db;

	function __construct()
	{
		parent::__construct();

		if (!$this->ionAuthLibrary->in_group('admin')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Site Configuration page.');
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
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Site Configuration';
		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('site_title', 'Site Title', 'trim|required');
		// $this->form_validation->set_rules('site_caching_time', 'Site Cache Time', 'trim|required|numeric');


		$validation = \Config\Services::validation();

		$validation->setRules([
			'site_title' => [
				'label' => 'Site Title',
				'rules' => 'required'
			],
			'site_caching_time' => [
				'label' => 'Site Cache Time',
				'rules' => 'required|numeric'
			]
		]);


		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		}

		if ($this->request->getMethod() == "GET") {

			// $this->load->helper('form');
			$qry = 'SELECT * FROM site_configuration'; // select data from db
			$site_configurations = $this->db->query($qry)->getResultArray();

			$this->data['site_configuration'] = $site_configurations[0];

			if (!$this->ionAuthLibrary->in_group('admin')) {
				session()->setFlashdata('message_type', 'warning');
				session()->setFlashdata('message', 'You are not allowed to edit this Site Configurations.');
				return redirect('administrator/dashboard');
			}

			$this->render('administrator/site_configurations/edit_site_configuration_view');
		} else {

			$site_title = $this->request->getPost('site_title');
			$site_title_fr = $this->request->getPost('site_title_fr');
			$site_description = $this->request->getPost('site_description');
			$site_description_fr = $this->request->getPost('site_description_fr');
			$site_offline = $this->request->getPost('site_offline');
			$site_offlinemessage = $this->request->getPost('site_offlinemessage');
			$site_offlineimage = $this->request->getPost('site_offlineimage');
			$from_name = $this->request->getPost('from_name');
			$from_email = $this->request->getPost('from_email');
			$site_caching = $this->request->getPost('site_caching');
			$site_caching_time = $this->request->getPost('site_caching_time');

			$sql = "UPDATE site_configuration SET `site_title`='" . addslashes($site_title) . "', `site_title_fr`='" . addslashes($site_title_fr) . "', `site_description`='" . addslashes($site_description) . "', `site_description_fr`='" . addslashes($site_description_fr) . "', `site_offline`='" . addslashes($site_offline) . "', `site_offlinemessage`='" . addslashes($site_offlinemessage) . "', `site_offlineimage`='" . addslashes($site_offlineimage) . "', `from_name`='" . addslashes($from_name) . "', `from_email`='" . addslashes($from_email) . "', `site_caching`='" . addslashes($site_caching) . "', `site_caching_time`='" . addslashes($site_caching_time) . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Site Configuration saved successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/site_configurations/edit/';
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/dashboard';
			} else {
				$redirect_url = 'administrator/site_configurations/edit/';
			}

			return redirect()->to($redirect_url);
		}
	}
}
