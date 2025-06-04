<?php

namespace App\Controllers\administrator;

use App\Libraries\IonAuth;

class User extends AdminController
{


	function __construct()
	{
		parent::__construct();


		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];

		// $this->load->library('ion_auth');
		$this->ionAuthLibrary = new IonAuth();
		$this->data['current_user'] = $this->ionAuthModel->user()->getRow();
		$this->data['current_user_menu'] = '';
	}


	public function index() {}


	public function login()
	{
		if ($this->ionAuthLibrary->logged_in()) {

			if ($this->ionAuthLibrary->in_group('Registered')) {
				session()->setFlashdata('message_type', 'info');
				session()->setFlashdata('message', 'You are not authorize person to see backend interface.');
				return redirect()->to('/');
			}

			return redirect()->to('administrator/dashboard');
		}
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator - Login';
		if ($this->request->getPost()) {
			//here we will verify the inputs;
			// $this->load->library('form_validation');
			// $this->form_validation->set_rules('identity', 'Identity', 'required');
			// $this->form_validation->set_rules('password', 'Password', 'required');
			// $this->form_validation->set_rules('remember', 'Remember me', 'integer');
			// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
			// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
			// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
			// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');
			$validationRules = [
				'identity'        => 'required',
				'password'        => 'required',
				'remember'        => 'permit_empty|integer',
				'facebook_link'   => 'permit_empty|valid_url',
				'twitter_link'    => 'permit_empty|valid_url',
				'instagram_link'  => 'permit_empty|valid_url',
				'website_link'    => 'permit_empty|valid_url',
			];
			if ($this->validate($validationRules)) {
				$remember = (bool) $this->request->getPost('remember');
				if ($this->ionAuthModel->login($this->request->getPost('identity'), $this->request->getPost('password'), $remember)) {
					if ($this->ionAuthLibrary->in_group('Registered')) {
						$this->ionAuthLibrary->logout();
						session()->setFlashdata('message', 'You are not authorize person to see backend interface.');
						return redirect()->to('administrator/user/login');
					} else {
						return redirect()->to('administrator');
					}
				} else {
					session()->setFlashdata('message', $this->ionAuthModel->errorsCustom());
					return  redirect()->to('administrator/user/login');
				}
			}
		}
		// $this->load->helper('form');
		$this->render('administrator/login_view', 'admin_master');
	}


	public function logout()
	{

		$this->ionAuthLibrary->logout();
		return redirect()->to('administrator/user/login');
	}

	public function profile()
	{
		if (!$this->ionAuthLibrary->logged_in()) {
			return redirect()->to('administrator');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator - User Profile';
		$user = $this->ionAuthModel->user()->getRow();
		$this->data['user'] = $user;
		$this->data['current_user_menu'] = '';

		if ($this->ionAuthLibrary->in_group('admin')) {
			$this->data['current_user_menu'] = view('templates/_parts/user_menu_admin_view.php', []);
		}

		// $this->load->library('form_validation');
		// $this->form_validation->set_rules('first_name', 'First name', 'trim');
		// $this->form_validation->set_rules('last_name', 'Last name', 'trim');
		// $this->form_validation->set_rules('company', 'Company', 'trim');
		// $this->form_validation->set_rules('phone', 'Phone', 'trim');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');

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
			]
		]);

		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		}


		if ($this->request->getMethod() == "GET") {
			$this->render('administrator/user/profile_view', 'admin_master');
		} else {
			$new_data = array(
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
			$this->ionAuthModel->updateCustom($user->id, $new_data);

			session()->setFlashdata('message', $this->ionAuthModel->messages());
			return redirect()->to('administrator/user/profile');
		}
	}
}
