<?php

namespace App\Controllers\administrator;

use App\Libraries\Slug;
use CodeIgniter\Database\Config;

class Teachers extends AdminController
{

	public $slugLibrary;
	public $db;

	function __construct()
	{
		parent::__construct();

		if (!$this->ionAuthLibrary->in_access('teacher_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Teachers page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];

		$config = array(
			'field' => 'alias',
			'title' => 'title',
			'table' => 'teachers',
			'id' => 'id',
		);

		$this->slugLibrary = new Slug($config);
		$this->db = Config::connect();
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Teachers';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		/*require_once APPPATH.'third_party/PHPExcel.php';
        $this->excel = new PHPExcel(); 
        $file_type	= PHPExcel_IOFactory::identify(FCPATH . 'assets/xls-data/teachers-list.xlsx');
	    $objReader	= PHPExcel_IOFactory::createReader($file_type);
	    $objPHPExcel = $objReader->load(FCPATH . 'assets/xls-data/teachers-list.xlsx');
	    $sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);*/

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$teacher_ids = $this->request->getPost('teacher_id');

		if ($task == 'publish') {
			$this->publish($teacher_ids);
		} elseif ($task == 'unpublish') {
			$this->unpublish($teacher_ids);
		} elseif ($task == 'delete') {
			$this->delete($teacher_ids);
		}

		if (!empty($filter)) {
			session()->set('teachers_filter', $filter);
		}

		$where = ' WHERE 1';
		$teachers_filter = session()->get('teachers_filter');

		/*if($teachers_filter['status'] != '') {
			$where .= ' AND status = '.$teachers_filter['status'];
		}*/

		//if(!$this->ion_auth->in_group('admin')) {
		//	$where .= ' AND created_by = '.$current_user->id;  			
		//}

		$qry = 'Select * from teachers' . $where; // select data from db

		$this->data['teachers'] = $this->db->query($qry)->getResultArray();
		$this->data['current_user'] = $current_user;
		$this->render('administrator/teachers/list_teachers_view');
	}


	public function fetchTeacherData()
	{

		$draw = $this->request->getGet('draw');
		$length = $this->request->getGet('length');
		$start = $this->request->getGet('start');
		$columns = $this->request->getGet('columns');
		$order = $this->request->getGet('order');
		$search = $this->request->getGet('search');

		if (isset($order)) {
			$orderfield = $columns[$order[0]['column']]['name'];
			$orderby = $order[0]['dir'];
		} else {
			$orderfield = 'id';
			$orderby = 'desc';
		}

		$result = array('data' => array());

		$qry = 'Select count(*) AS record_count from teachers'; // select data from db       	
		$data_count = $this->db->query($qry)->getResultArray();

		$where = ' WHERE 1';
		if ($search['value'] != '') {
			$where .= ' AND `title` LIKE "%' . $search['value'] . '%"';
		}

		$qry = 'Select * from `teachers`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby . ' LIMIT ' . $start . ', ' . $length; // select data from db 		
		$data = $this->db->query($qry)->getResultArray();

		$qry = 'Select count(*) AS fileter_record_count from `teachers`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby; // select data from db      
		$data_filter_count = $this->db->query($qry)->getResultArray();


		$result['draw'] = $draw;
		$result['recordsTotal'] = $data_count[0]['record_count'];
		$result['recordsFiltered'] = $data_filter_count[0]['fileter_record_count'];

		foreach ($data as $key => $value) {

			$checkbox = '<input type="checkbox" id="teacher_id' . $key . '" name="teacher_id[]" value="' . $value['id'] . '" />';

			$id = $value['id'];

			$status = '<div class="btn-group center-block">';
			$statusval = $value['status'];
			if ($statusval == 1) {
				$status .= anchor('administrator/teachers/unpublish/' . $value['id'], '<i class="fa fa-check"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unpublish Item'));
			} else {
				$status .= anchor('administrator/teachers/publish/' . $value['id'], '<i class="fa fa-times-circle"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Publish Item'));
			}
			$status .= '</div>';

			$title = anchor('administrator/teachers/edit/' . $value['id'], $value['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')) . ' <span class="small">(Alias : ' . $value['alias'] . ')</span>';

			$created_date = date('Y-m-d', strtotime($value['created_date']));

			$class = 'btn btn-success';
			if (!$this->ionAuthLibrary->in_access('teacher_access', 'update')) {
				$class .= ' disabled';
			}
			$buttons = anchor('administrator/teachers/edit/' . $value['id'], '<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'));
			if (!in_array($value['title'], array('admin'))) {

				$class = 'btn btn-danger btn-delete';
				if (!$this->ionAuthLibrary->in_access('teacher_access', 'delete')) {
					$class .= ' disabled';
				}
				//$buttons .= '&nbsp;<a href="javascript:void(0)" data-url="'.site_url('administrator/teachers/delete/'.$value['id']).'" class="'.$class.'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>';
				$onClick = " onclick= deleteTeacher('" . site_url('administrator/teachers/delete/' . $value['id']) . "')";
				$buttons .= '&nbsp;<a href="javascript:void(0)" ' . $onClick . ' class="' . $class . '" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>';
			}

			$result['data'][$key] = array(
				$checkbox,
				$id,
				$status,
				$title,
				$created_date,
				$buttons
			);
		}

		echo json_encode($result);
	}


	public function teachers_modal($search_val = NULL)
	{

		$filter = array();
		$filter['search_val'] = urldecode($search_val);
		session()->set('teachers_modal_filter', $filter);

		$filter = $this->request->getPost('filter');

		if (!empty($filter)) {
			session()->set('teachers_modal_filter', $filter);
		}

		$where = ' WHERE `status` = 1';
		$teachers_modal_filter = session()->get('teachers_modal_filter');

		if (!empty($teachers_modal_filter['search_val'])) {
			$where .= ' AND `title` LIKE ("%' . $teachers_modal_filter['search_val'] . '%") OR `description` LIKE ("%' . $teachers_modal_filter['search_val'] . '%")';
		}

		$qry = 'SELECT `id`, `title`, `alias` FROM `teachers` ' . $where . ' ORDER BY `id` DESC'; // select data from db       	
		$this->data['teacherslist_modal'] = $this->db->query($qry)->getResultArray();
		$this->render('administrator/teachers/list_teachers_modal_view');
	}


	public function create()
	{

		if (!$this->ionAuthLibrary->in_access('teacher_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Create Teacher.');
			return redirect()->to('administrator/teachers');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Add Teacher';

		$task = $this->request->getPost('task');

		// $this->load->library('form_validation');
		// $this->load->helper("url");
		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Teacher Title',
				'rules' => 'required'
			],
			'alias' => [
				'label' => 'Teacher Alias',
				'rules' => 'is_unique[teachers.alias]'
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

		if ($this->request->getMethod() == "POST"  && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} elseif ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$this->data['userlist'] = $this->userlist();
			$this->render('administrator/teachers/create_teacher_view');
		} else {
			$title = $this->request->getPost('title');
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
			$description = $this->request->getPost('description');
			$image = $this->request->getPost('image');
			$facebook_link = $this->request->getPost('facebook_link');
			$twitter_link = $this->request->getPost('twitter_link');
			$instagram_link = $this->request->getPost('instagram_link');
			$website_link = $this->request->getPost('website_link');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');
			$meta_title = $this->request->getPost('meta_title');
			$meta_description = $this->request->getPost('meta_description');

			$sql = "Insert into teachers (`title`, `alias`, `description`, `image`, `facebook_link`, `twitter_link`, `instagram_link`, `website_link`, `status`, `created_date`, `created_by`, `meta_title`, `meta_description`) VALUES ('" . addslashes($title) . "', '" . addslashes($alias) . "', '" . addslashes($description) . "', '" . addslashes($image) . "', '" . addslashes($facebook_link) . "', '" . addslashes($twitter_link) . "', '" . addslashes($instagram_link) . "', '" . addslashes($website_link) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "', '" . addslashes($meta_title) . "', '" . addslashes($meta_description) . "')";

			$val = $this->db->query($sql);

			$insert_id = $this->db->insertID();

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Teacher added successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/teachers/edit/' . $insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/teachers';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/teachers/create';
			} else {
				$redirect_url = 'administrator/teachers/edit/' . $insert_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function edit($teacher_id = NULL, $task = 'save')
	{
		if (!$this->ionAuthLibrary->in_access('teacher_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit Teacher.');
			return redirect()->to('administrator/teachers');
		}

		$teacher_id = $this->request->getPost('teacher_id') ? $this->request->getPost('teacher_id') : $teacher_id;
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit Teacher';
		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Teacher Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Teacher Alias', 'trim');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Teacher Title',
				'rules' => 'required'
			],
			// 'alias' => [
			// 	'label' => 'Teacher Alias',
			// 	'rules' => 'trim'
			// ],
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
		} else if ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$qry = 'Select * from teachers where id = ' . $teacher_id; // select data from db
			$teacher = $this->db->query($qry)->getResultArray();

			$this->data['teacher'] = $teacher[0];
			$this->data['userlist'] = $this->userlist();

			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['teacher']['created_by'])
			//{
			//	session()->setFlashdata('message_type', 'warning');
			//	session()->setFlashdata('message','You are not allowed to edit this Teacher.');
			//	return redirect()->to('administrator/teachers','refresh');
			//}
			$this->render('administrator/teachers/edit_teacher_view');
		} else {

			$title = $this->request->getPost('title');
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

			$alias = $this->slugLibrary->create_uri($data, $teacher_id);
			$description = $this->request->getPost('description');
			$image = $this->request->getPost('image');
			$facebook_link = $this->request->getPost('facebook_link');
			$twitter_link = $this->request->getPost('twitter_link');
			$instagram_link = $this->request->getPost('instagram_link');
			$website_link = $this->request->getPost('website_link');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');
			$meta_title = $this->request->getPost('meta_title');
			$meta_description = $this->request->getPost('meta_description');

			$sql = "update teachers set `title`='" . addslashes($title) . "', `alias`='" . addslashes($alias) . "', `description`='" . addslashes($description) . "', `image`='" . addslashes($image) . "', `facebook_link`='" . addslashes($facebook_link) . "', `twitter_link`='" . addslashes($twitter_link) . "', `instagram_link`='" . addslashes($instagram_link) . "', `website_link`='" . addslashes($website_link) . "', `status`='" . addslashes($status) . "', `created_date`='" . addslashes($created_date) . "', `created_by`='" . addslashes($created_by) . "', `meta_title`='" . addslashes($meta_title) . "', `meta_description`='" . addslashes($meta_description) . "' where id= '" . $teacher_id . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Teacher updated successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/teachers/edit/' . $teacher_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/teachers';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/teachers/create';
			} else {
				$redirect_url = 'administrator/teachers/edit/' . $teacher_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function unpublish($teacher_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('teacher_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Teacher.');
			return redirect()->to('administrator/teachers');
		}

		if (is_null($teacher_id) || empty($teacher_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no teacher to unpublish.');
		} else {

			if (is_array($teacher_id)) {
				$teacher_id = implode('","', $teacher_id);
				$qry = 'update teachers set `status` = 0 where id IN ( "' . $teacher_id . '")'; // select data from db
			} else {
				$qry = 'update teachers set `status` = 0 where id = ' . $teacher_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Teacher unpublished successfully.');
		}
		return redirect()->to('administrator/teachers');
	}


	public function publish($teacher_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('teacher_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Teacher.');
			return redirect()->to('administrator/teachers');
		}

		if (is_null($teacher_id) || empty($teacher_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no teacher to publish.');
		} else {

			if (is_array($teacher_id)) {
				$teacher_id = implode('","', $teacher_id);
				$qry = 'update teachers set `status` = 1 where id IN ( "' . $teacher_id . '")'; // select data from db
			} else {
				$qry = 'update teachers set `status` = 1 where id = ' . $teacher_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Teacher published successfully.');
		}
		return redirect()->to('administrator/teachers');
	}


	public function delete($teacher_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('teacher_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Teacher.');
			return redirect()->to('administrator/teachers');
		}

		if (is_null($teacher_id) || empty($teacher_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no teacher to delete.');
		} else {

			if (!is_array($teacher_id)) {
				$teacher_id = array($teacher_id);
			}

			foreach ($teacher_id as $teacher) {


				$qry = 'delete from teachers where id = ' . $teacher; // select data from db
				$this->data['teachers'] = $this->db->query($qry);

				$message_type = 'success';
				$message = 'Teacher(s) deleted successfully.';
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect()->to('administrator/teachers');
	}


	public function userlist()
	{

		$qry = 'Select * from users where active = 1'; // select data from db
		$users = $this->db->query($qry)->getResultArray();

		return $users;
	}
}
