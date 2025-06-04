<?php

namespace App\Controllers\administrator;

use CodeIgniter\Database\Config;

class Groups extends AdminController
{

	public $db;

	function __construct()
	{
		parent::__construct();
		//if(!$this->ion_auth->in_group('admin'))
		if (!$this->ionAuthLibrary->in_access('group_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Groups page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}
		$this->db = Config::connect();
		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Groups';
		$this->data['groups'] = $this->ionAuthModel->groups()->getResult();
		$this->render('administrator/groups/list_groups_view');
	}


	public function create()
	{
		if (!$this->ionAuthLibrary->in_access('group_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Create Group.');
			redirect('administrator/groups', 'refresh');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Create group';
		// $this->load->library('form_validation');
		// $this->form_validation->set_rules('group_name', 'Group name', 'trim|required|is_unique[groups.name]');
		// $this->form_validation->set_rules('group_description', 'Group description', 'trim|required');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'group_name' => [
				'label' => 'Group name',
				'rules' => 'required|is_unique[groups.name]'
			],
			'group_description' => [
				'label' => 'Group description',
				'rules' => 'required'
			]
		]);

		if ($this->request->getMethod() == "GET") {
			$this->render('administrator/groups/create_group_view');
		} else if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
			// $this->load->helper('form');
			// $this->render('administrator/groups/create_group_view');
		} else {
			$group_name = $this->request->getPost('group_name');
			$group_description = $this->request->getPost('group_description');
			$group_access = json_encode($this->request->getPost('group_access'));
			$user_access = json_encode($this->request->getPost('user_access'));
			$presenter_access = json_encode($this->request->getPost('presenter_access'));
			$subscriber_access = json_encode($this->request->getPost('subscriber_access'));
			$menu_access = json_encode($this->request->getPost('menu_access'));
			$category_access = json_encode($this->request->getPost('category_access'));
			$article_access = json_encode($this->request->getPost('article_access'));
			$tag_access = json_encode($this->request->getPost('tag_access'));
			$schedule_access = json_encode($this->request->getPost('schedule_access'));
			$album_access = json_encode($this->request->getPost('album_access'));
			$contact_access = json_encode($this->request->getPost('contact_access'));
			$video_access = json_encode($this->request->getPost('video_access'));
			$media_access = json_encode($this->request->getPost('media_access'));
			$teacher_access = json_encode($this->request->getPost('teacher_access'));

			$additional_data = array();
			$additional_data['group_access'] = $group_access;
			$additional_data['user_access'] = $user_access;
			$additional_data['presenter_access'] = $presenter_access;
			$additional_data['subscriber_access'] = $subscriber_access;
			$additional_data['menu_access'] = $menu_access;
			$additional_data['category_access'] = $category_access;
			$additional_data['article_access'] = $article_access;
			$additional_data['schedule_access'] = $schedule_access;
			$additional_data['album_access'] = $album_access;
			$additional_data['contact_access'] = $contact_access;
			$additional_data['video_access'] = $video_access;
			$additional_data['media_access'] = $media_access;
			$additional_data['teacher_access'] = $teacher_access;

			$this->ionAuthModel->create_group($group_name, $group_description, $additional_data);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', $this->ionAuthModel->messages());

			return redirect('administrator/groups', 'refresh');
		}
	}


	public function edit($group_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('group_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit Group.');
			return redirect('administrator/groups', 'refresh');
		}

		$group_id = $this->request->getPost('group_id') ? $this->request->getPost('group_id') : $group_id;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit group';
		// $this->load->library('form_validation');

		// $this->form_validation->set_rules('group_name', 'Group name', 'trim|required');
		// $this->form_validation->set_rules('group_description', 'Group description', 'trim|required');
		// $this->form_validation->set_rules('group_id', 'Group id', 'trim|integer|required');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'group_name' => [
				'label' => 'Group name',
				'rules' => 'required'
			],
			'group_description' => [
				'label' => 'Group description',
				'rules' => 'required'
			],
			'group_id' => [
				'label' => 'Group id',
				'rules' => 'integer|required'
			]
		]);

		$group = $this->ionAuthModel->group((int) $group_id)->getRow();

		if ($group) {
			$this->data['group'] = $group;
		} else {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'The group doesn\'t exist.');
			return redirect('administrator/groups', 'refresh');
		}
		// $this->load->helper('form');
		if ($this->request->getMethod() == "GET") {
			$this->render('administrator/groups/edit_group_view');
		} elseif ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} else {
			$group_name = $this->request->getPost('group_name');
			$group_description = $this->request->getPost('group_description');
			$group_access = json_encode($this->request->getPost('group_access'));
			$user_access = json_encode($this->request->getPost('user_access'));
			$presenter_access = json_encode($this->request->getPost('presenter_access'));
			$subscriber_access = json_encode($this->request->getPost('subscriber_access'));
			$menu_access = json_encode($this->request->getPost('menu_access'));
			$category_access = json_encode($this->request->getPost('category_access'));
			$article_access = json_encode($this->request->getPost('article_access'));
			$tag_access = json_encode($this->request->getPost('tag_access'));
			$schedule_access = json_encode($this->request->getPost('schedule_access'));
			$album_access = json_encode($this->request->getPost('album_access'));
			$contact_access = json_encode($this->request->getPost('contact_access'));
			$video_access = json_encode($this->request->getPost('video_access'));
			$media_access = json_encode($this->request->getPost('media_access'));
			$teacher_access = json_encode($this->request->getPost('teacher_access'));
			$group_id = $this->request->getPost('group_id');

			$additional_data = array();
			$additional_data['group_description'] = $group_description;
			$additional_data['group_access'] = $group_access;
			$additional_data['user_access'] = $user_access;
			$additional_data['presenter_access'] = $presenter_access;
			$additional_data['subscriber_access'] = $subscriber_access;
			$additional_data['menu_access'] = $menu_access;
			$additional_data['category_access'] = $category_access;
			$additional_data['article_access'] = $article_access;
			$additional_data['tag_access'] = $tag_access;
			$additional_data['schedule_access'] = $schedule_access;
			$additional_data['album_access'] = $album_access;
			$additional_data['contact_access'] = $contact_access;
			$additional_data['video_access'] = $video_access;
			$additional_data['media_access'] = $media_access;
			$additional_data['teacher_access'] = $teacher_access;

			$this->ionAuthModel->update_group($group_id, $group_name, $additional_data);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', $this->ionAuthModel->messages());

			return redirect('administrator/groups', 'refresh');
		}
	}


	public function delete($group_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('group_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Group.');
			redirect('administrator/groups', 'refresh');
		}

		if (is_null($group_id)) {

			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no group to delete');
		} else {

			$checkStatus = $this->checkGroupExistInUsers($group_id);
			if ($checkStatus == 0) {

				$this->ionAuthModel->delete_group($group_id);

				$message_type = 'success';
				$message = $this->ionAuthModel->messages();
			} else {

				$message_type = 'warning';
				$message = 'You cant delete this Group. Because this group is assinged in users.';
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect('administrator/groups', 'refresh');
	}


	public function checkGroupExistInUsers($group_id)
	{

		$qry = 'Select * from users_groups where group_id = ' . $group_id; // select data from db       
		$users_groups = $this->db->query($qry)->getResultArray();

		return count($users_groups);
	}
}
