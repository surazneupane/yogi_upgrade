<?php

namespace App\Controllers\administrator;

use App\Libraries\Slug;
use CodeIgniter\Database\Config;

class Presenters extends AdminController
{

	public $db;
	public $slugLibrary;

	function __construct()
	{
		parent::__construct();

		//if(!$this->ion_auth->in_group('admin'))
		if (!$this->ionAuthLibrary->in_access('presenter_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Presenters page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$this->db = Config::connect();
		$this->slugLibrary = new Slug([
			'field' => 'alias',
			'title' => 'name',
			'table' => 'presenters',
			'id' => 'id',
		]);
		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];




		// $this->load->library('slug', $config);
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Presenters';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$presenter_ids = $this->request->getPost('presenter_id');

		if ($task == 'publish') {
			return $this->publish($presenter_ids);
		} elseif ($task == 'unpublish') {
			return $this->unpublish($presenter_ids);
		} elseif ($task == 'delete') {
			return $this->delete($presenter_ids);
		}

		if (!empty($filter)) {
			session()->set('presenters_filter', $filter);
		}

		$where = ' WHERE 1';
		$presenters_filter = session()->get('presenters_filter');
		
		if (isset($presenters_filter['status']) && $presenters_filter['status'] != '') {
			$where .= ' AND status = ' . $presenters_filter['status'];
		}

		//if(!$this->ion_auth->in_group('admin')) {
		//	$where .= ' AND created_by = '.$current_user->id;  			
		//}

		$qry = 'Select * from presenters' . $where; // select data from db

		$this->data['presenters'] = $this->db->query($qry)->getResultArray();
		$this->data['current_user'] = $current_user;
		$this->render('administrator/presenters/list_presenters_view');
	}


	public function create()
	{

		if (!$this->ionAuthLibrary->in_access('presenter_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Add Presenter.');
			return redirect()->to('administrator/presenters');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Add Presenter';

		$task = $this->request->getPost('task');

		// $this->load->helper("url");
		// $this->load->library('form_validation');
		// $this->form_validation->set_rules('name', 'Presenter Name', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Presenter Alias', 'trim|is_unique[presenters.alias]');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'name' => [
				'label' => 'Presenter Name',
				'rules' => 'required'
			],
			'alias' => [
				'label' => 'Presenter Alias',
				'rules' => 'is_unique[presenters.alias]'
			],
			'facebook_link' => [
				'label' => 'Facebook URL',
				'rules' => 'permit_empty|valid_url'
			],
			'twitter_link' => [
				'label' => 'Twitter URL',
				'rules' => 'permit_empty|valid_url'
			],
			'instagram_link' => [
				'label' => 'Instagram URL',
				'rules' => 'permit_empty|valid_url'
			],
			'website_link' => [
				'label' => 'Website URL',
				'rules' => 'permit_empty|valid_url'
			]
		]);

		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()
				->withInput()
				->with('errors', $validation->getErrors());
		}

		if ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$this->data['userlist'] = $this->userlist();
			$this->render('administrator/presenters/create_presenter_view');
		} else {
			$name = $this->request->getPost('name');
			$aliasTemp = $this->request->getPost('alias');
			if (!$aliasTemp) {
				$data = array(
					'name' => $name,
				);
			} else {
				$data = array(
					'name' => $aliasTemp,
				);
			}
			$alias = $this->slugLibrary->create_uri($data);
			$description = $this->request->getPost('description');
			$image = $this->request->getPost('image');
			$facebook_link = $this->request->getPost('facebook_link');
			$twitter_link = $this->request->getPost('twitter_link');
			$instagram_link = $this->request->getPost('instagram_link');
			$website_link = $this->request->getPost('website_link');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');

			$sql = "Insert into presenters (`name`, `alias`, `description`, `image`, `facebook_link`, `twitter_link`, `instagram_link`, `website_link`, `status`, `created_date`, `created_by`) VALUES ('" . addslashes($name) . "', '" . addslashes($alias) . "', '" . addslashes($description) . "', '" . addslashes($image) . "', '" . addslashes($facebook_link) . "', '" . addslashes($twitter_link) . "', '" . addslashes($instagram_link) . "', '" . addslashes($website_link) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "')";

			$val = $this->db->query($sql);

			$insert_id = $this->db->insertID();

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Presenter added successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/presenters/edit/' . $insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/presenters';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/presenters/create';
			} else {
				$redirect_url = 'administrator/presenters/edit/' . $insert_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function edit($presenter_id = NULL, $task = 'save')
	{

		if (!$this->ionAuthLibrary->in_access('presenter_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit Presenter.');
			return redirect()->to('administrator/presenters');
		}

		$presenter_id = $this->request->getPost('presenter_id') ? $this->request->getPost('presenter_id') : $presenter_id;
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit Presenter';

		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('name', 'Presenter Name', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Presenter Alias', 'trim');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'name' => [
				'label' => 'Presenter Name',
				'rules' => 'required',
			],
			// 'alias' => [
			// 	'label' => 'Presenter Alias',
			// 	'rules' => '',
			// ],
			'facebook_link' => [
				'label' => 'Facebook URL',
				'rules' => 'permit_empty|valid_url',
			],
			'twitter_link' => [
				'label' => 'Twitter URL',
				'rules' => 'permit_empty|valid_url',
			],
			'instagram_link' => [
				'label' => 'Instagram URL',
				'rules' => 'permit_empty|valid_url',
			],
			'website_link' => [
				'label' => 'Website URL',
				'rules' => 'permit_empty|valid_url',
			]
		]);

		if ($this->request->getMethod() == "POST" && ! $validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		}

		if ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$qry = 'Select * from presenters where id = ' . $presenter_id; // select data from db
			$presenter = $this->db->query($qry)->getResultArray();

			$this->data['presenter'] = $presenter[0];
			$this->data['userlist'] = $this->userlist();

			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['presenter']['created_by'])
			//{
			//	session()->setFlashdata('message_type', 'warning');
			//	session()->setFlashdata('message','You are not allowed to edit this Presenter.');
			//	redirect()->to('administrator/presenters','refresh');
			//}

			$this->render('administrator/presenters/edit_presenter_view');
		} else {

			$name = $this->request->getPost('name');
			$aliasTemp = $this->request->getPost('alias');
			if (!$aliasTemp) {
				$data = array(
					'name' => $name,
				);
			} else {
				$data = array(
					'name' => $aliasTemp,
				);
			}

			$alias = $this->slugLibrary->create_uri($data, $presenter_id);
			$description = $this->request->getPost('description');
			$image = $this->request->getPost('image');
			$facebook_link = $this->request->getPost('facebook_link');
			$twitter_link = $this->request->getPost('twitter_link');
			$instagram_link = $this->request->getPost('instagram_link');
			$website_link = $this->request->getPost('website_link');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');

			$sql = "update presenters set `name`='" . addslashes($name) . "', `alias`='" . addslashes($alias) . "', `description`='" . addslashes($description) . "', `image`='" . addslashes($image) . "', `facebook_link`='" . addslashes($facebook_link) . "', `twitter_link`='" . addslashes($twitter_link) . "', `instagram_link`='" . addslashes($instagram_link) . "', `website_link`='" . addslashes($website_link) . "', `status`='" . addslashes($status) . "', `created_date`='" . addslashes($created_date) . "', `created_by`='" . addslashes($created_by) . "' where id= '" . $presenter_id . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Presenter updated successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/presenters/edit/' . $presenter_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/presenters';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/presenters/create';
			} else {
				$redirect_url = 'administrator/presenters/edit/' . $presenter_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function unpublish($presenter_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('presenter_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Presenter.');
			redirect()->to('administrator/presenters');
		}

		if (is_null($presenter_id) || empty($presenter_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no presenter to unpublish.');
		} else {

			if (is_array($presenter_id)) {
				$presenter_id = implode('","', $presenter_id);
				$qry = 'update presenters set `status` = 0 where id IN ( "' . $presenter_id . '")'; // select data from db
			} else {
				$qry = 'update presenters set `status` = 0 where id = ' . $presenter_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Presenter unpublished successfully.');
		}
		return redirect()->to('administrator/presenters');
	}


	public function publish($presenter_id = NULL)
	{
		
		if (!$this->ionAuthLibrary->in_access('presenter_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Presenter.');
			return redirect()->to('administrator/presenters');
		}

		if (is_null($presenter_id) || empty($presenter_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no presenter to publish.');
		} else {

			if (is_array($presenter_id)) {
				$presenter_id = implode('","', $presenter_id);
				$qry = 'update presenters set `status` = 1 where id IN ( "' . $presenter_id . '")'; // select data from db
			} else {
				$qry = 'update presenters set `status` = 1 where id = ' . $presenter_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Presenter published successfully.');
		}
		return redirect()->to('administrator/presenters');
	}


	public function delete($presenter_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('presenter_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Presenter.');
			return redirect()->to('administrator/presenters');
		}

		if (is_null($presenter_id) || empty($presenter_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no presenter to delete.');
		} else {

			if (!is_array($presenter_id)) {
				$presenter_id = array($presenter_id);
			}

			foreach ($presenter_id as $presenter) {

				$checkStatus = $this->checkPresenterExistInVideos($presenter);
				if ($checkStatus == 0) {

					$qry = 'delete from presenters where id = ' . $presenter; // select data from db
					$this->data['presenters'] = $this->db->query($qry);

					$message_type = 'success';
					$message = 'Presenter(s) deleted successfully.';
				} else {

					$message_type = 'warning';
					$message = 'Some presenter(s) could not deleted, Because they are assinged in videos(s).';
				}
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect()->to('administrator/presenters');
	}


	public function userlist()
	{

		$qry = 'Select * from users where active = 1'; // select data from db
		$users = $this->db->query($qry)->getResultArray();

		return $users;
	}


	public function checkPresenterExistInVideos($presenter_id)
	{

		$qry = 'Select * from videos where presenter_id = ' . $presenter_id; // select data from db       
		$videos = $this->db->query($qry)->getResultArray();

		return count($videos);
	}
}
