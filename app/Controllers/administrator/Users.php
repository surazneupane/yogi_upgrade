<?php

namespace App\Controllers\administrator;

use App\Libraries\MailChimp;
use CodeIgniter\Database\Config;

class Users extends AdminController
{

	public $db;
	public $mailChimpLibrary;

	function __construct()
	{
		parent::__construct();

		if (!$this->ionAuthLibrary->in_access('user_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Users page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}
		$this->db =  Config::connect();
		$this->mailChimpLibrary = new MailChimp();
		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
	}


	public function index($group_id = NULL)
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Users';
		$this->data['users'] = $this->ionAuthModel->users($group_id)->getResult();
		$this->render('administrator/users/list_users_view');
	}


	public function create()
	{

		if (!$this->ionAuthLibrary->in_access('user_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Create User.');
			redirect('administrator/users', 'refresh');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Create user';
		// $this->load->library('form_validation');
		// $this->form_validation->set_rules('first_name', 'First name', 'trim');
		// $this->form_validation->set_rules('last_name', 'Last name', 'trim');
		// $this->form_validation->set_rules('company', 'Company', 'trim');
		// $this->form_validation->set_rules('phone', 'Phone', 'trim');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');
		// $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
		// $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		// $this->form_validation->set_rules('password', 'Password', 'required');
		// $this->form_validation->set_rules('password_confirm', 'Password confirmation', 'required|matches[password]');
		// $this->form_validation->set_rules('groups[]', 'Groups', 'required|integer');
		$validation = \Config\Services::validation();
		$validation->setRules([
			'first_name' => [
				'label' => 'First name',
				'rules' => 'required'
			],
			'last_name' => [
				'label' => 'Last name',
				'rules' => 'required'
			],
			'company' => [
				'label' => 'Company',
				'rules' => 'required'
			],
			'phone' => [
				'label' => 'Phone',
				'rules' => 'required'
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
			],
			'username' => [
				'label' => 'Username',
				'rules' => 'required|is_unique[users.username]'
			],
			'email' => [
				'label' => 'Email',
				'rules' => 'required|valid_email|is_unique[users.email]'
			],
			'password' => [
				'label' => 'Password',
				'rules' => 'required'
			],
			'password_confirm' => [
				'label' => 'Password confirmation',
				'rules' => 'required|matches[password]'
			],
			'groups' => [
				'label' => 'Groups',
				'rules' => 'required'
			],
			'groups.*' => [
				'label' => 'Groups',
				'rules' => 'required|integer'
			]
		]);
		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()
				->withInput()
				->with('errors', $validation->getErrors());
			// $this->load->helper('form');
		} elseif ($this->request->getMethod() == "GET") {
			$this->data['groups'] = $this->ionAuthModel->groups()->getResult();
			$this->render('administrator/users/create_user_view');
		} else {
			$username = $this->request->getPost('username');
			$email = $this->request->getPost('email');
			$password = $this->request->getPost('password');
			$group_ids = $this->request->getPost('groups');
			$photo = $this->request->getPost('photo');

			$additional_data = array(
				'first_name' => $this->request->getPost('first_name'),
				'last_name' => $this->request->getPost('last_name'),
				'company' => $this->request->getPost('company'),
				'description' => $this->request->getPost('description'),
				'phone' => $this->request->getPost('phone'),
				'photo' => $this->request->getPost('photo'),
				'facebook_link' => $this->request->getPost('facebook_link'),
				'twitter_link' => $this->request->getPost('twitter_link'),
				'instagram_link' => $this->request->getPost('instagram_link'),
				'website_link' => $this->request->getPost('website_link')
			);
			$this->ionAuthModel->register($username, $password, $email, $additional_data, $group_ids, $photo);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', $this->ionAuthModel->messages());

			return redirect('administrator/users', 'refresh');
		}
	}


	public function edit($user_id = NULL)
	{
		if (!$this->ionAuthLibrary->in_access('user_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit User.');
			redirect('administrator/users', 'refresh');
		}

		$user_id = $this->request->getPost('user_id') ? $this->request->getPost('user_id') : $user_id;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit user';
		// $this->load->library('form_validation');

		// $this->form_validation->set_rules('first_name', 'First name', 'trim');
		// $this->form_validation->set_rules('last_name', 'Last name', 'trim');
		// $this->form_validation->set_rules('company', 'Company', 'trim');
		// $this->form_validation->set_rules('phone', 'Phone', 'trim');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');
		// $this->form_validation->set_rules('username', 'Username', 'trim|required');
		// $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		// $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
		// $this->form_validation->set_rules('password_confirm', 'Password confirmation', 'matches[password]');
		// $this->form_validation->set_rules('groups[]', 'Groups', 'required|integer');
		// $this->form_validation->set_rules('user_id', 'User ID', 'trim|integer|required');


		$validation = \Config\Services::validation();

		$validation->setRules([
			'first_name' => [
				'label' => 'First name',
				'rules' => 'required'
			],
			'last_name' => [
				'label' => 'Last name',
				'rules' => 'required'
			],
			'company' => [
				'label' => 'Company',
				'rules' => 'required'
			],
			'phone' => [
				'label' => 'Phone',
				'rules' => 'required'
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
			],
			'username' => [
				'label' => 'Username',
				'rules' => 'required'
			],
			'email' => [
				'label' => 'Email',
				'rules' => 'required|valid_email'
			],
			'password' => [
				'label' => 'Password',
				'rules' => 'permit_empty|min_length[6]' // Use permit_empty to allow skips on update
			],
			'password_confirm' => [
				'label' => 'Password confirmation',
				'rules' => 'permit_empty|matches[password]'
			],
			'groups' => [
				'label' => 'Groups',
				'rules' => 'required' // Integer validation for array will be done separately
			],
			'groups.*' => [
				'label' => 'Groups',
				'rules' => 'required|integer'
			],
			'user_id' => [
				'label' => 'User ID',
				'rules' => 'required|integer'
			]
		]);

		$user =  $this->ionAuthModel->user((int) $user_id)->getRow();
		
		if ($user) {
			$this->data['user'] = $user;
		} else {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'The user doesn\'t exist.');
			return redirect('administrator/users', 'refresh');
		}

		if ($this->request->getMethod() == "GET") {
			$this->data['groups'] = $this->ionAuthModel->groups()->getResult();
			$this->data['usergroups'] = array();
			if ($usergroups = $this->ionAuthModel->get_users_groups($user->id)->getResult()) {
				foreach ($usergroups as $group) {
					$this->data['usergroups'][] = $group->id;
				}
			}
			$this->render('administrator/users/edit_user_view');
		} else if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} else {
			$user_id = $this->request->getPost('user_id');
			$new_data = array(
				'username' => $this->request->getPost('username'),
				'email' => $this->request->getPost('email'),
				'first_name' => $this->request->getPost('first_name'),
				'last_name' => $this->request->getPost('last_name'),
				'company' => $this->request->getPost('company'),
				'description' => $this->request->getPost('description'),
				'phone' => $this->request->getPost('phone'),
				'photo' => $this->request->getPost('photo'),
				'facebook_link' => $this->request->getPost('facebook_link'),
				'twitter_link' => $this->request->getPost('twitter_link'),
				'instagram_link' => $this->request->getPost('instagram_link'),
				'website_link' => $this->request->getPost('website_link')
			);
			if (strlen($this->request->getPost('password')) >= 6) $new_data['password'] = $this->request->getPost('password');

			$this->ionAuthModel->updateCustom($user_id, $new_data);

			//Update the groups user belongs to
			$groups = $this->request->getPost('groups');
			if (isset($groups) && !empty($groups)) {
				$this->ionAuthModel->remove_from_group('', $user_id);
				foreach ($groups as $group) {
					$this->ionAuthModel->add_to_group($group, $user_id);
				}
			}

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', $this->ionAuthModel->messages());

			return redirect('administrator/users', 'refresh');
		}
	}


	public function delete($user_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('user_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete User.');
			redirect('administrator/users', 'refresh');
		}

		if (is_null($user_id)) {

			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no user to delete');
		} else {

			$checkStatus = $this->checkUserExistInArticles($user_id);
			if ($checkStatus == 0) {

				$this->ionAuthModel->delete_user($user_id);
				$message_type = 'success';
				$message = $this->ionAuthModel->messages();
			} else {

				$message_type = 'warning';
				$message = 'You cant delete this user(s). Because they are assinged in article(s).';
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		redirect('administrator/users', 'refresh');
	}


	public function checkUserExistInArticles($user_id)
	{

		$qry = 'Select * from articles where created_by = ' . $user_id; // select data from db       
		$articles = $this->db->query($qry)->getResultArray();

		return count($articles);
	}


	public function getUsersAjax()
	{

		$usergroup_id = $this->request->getPost("usergroup_id");

		$users = $this->ionAuthModel->users($usergroup_id)->result();

		$response = array();
		$response['data'] = $users;
		$response['csrf_test_name'] = csrf_hash();

		echo json_encode($response);
	}


	public function mailchimpsynch()
	{

		$list_id = 'XXXXXXX';

		$qry = 'Select * from users'; // select data from db
		$users = $this->db->query($qry)->getResultArray();

		foreach ($users as $user) {

			$result = $this->mailChimpLibrary->post("lists/$list_id/members", [
				'email_address' => $user['email'],
				'merge_fields'  => ['FNAME' => $user['first_name'], 'LNAME' => $user['last_name']],
				'status'        => 'subscribed',
			]);

			if ($result['status'] == 'subscribed') {

				$new_data = array(
					'mailchimp_user' => '1'
				);

				$this->ionAuthModel->updateCustom($user['id'], $new_data);
			}
		}

		$message_type = 'success';
		$message      = 'User(s) are synchronized successfully with MailChimp.';

		session()->setFlashdata('message_type', $message_type);
		session()->setFlashdata('message', $message);

		return redirect('administrator/users', 'refresh');
	}
}
