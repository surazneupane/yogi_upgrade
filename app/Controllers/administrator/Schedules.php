<?php

namespace App\Controllers\administrator;

use CodeIgniter\Database\Config;

class Schedules extends AdminController
{

	public $db;

	function __construct()
	{
		parent::__construct();

		if (!$this->ionAuthLibrary->in_access('schedule_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Schedules page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
		$this->db = Config::connect();
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Schedules';
		$current_user = $this->ion_auth->user()->row();
		$this->load->helper('form');

		/*require_once APPPATH.'third_party/PHPExcel.php';
        $this->excel = new PHPExcel(); 
        $file_type	= PHPExcel_IOFactory::identify(FCPATH . 'assets/xls-data/schedules-list.xlsx');
	    $objReader	= PHPExcel_IOFactory::createReader($file_type);
	    $objPHPExcel = $objReader->load(FCPATH . 'assets/xls-data/schedules-list.xlsx');
	    $sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);*/

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$schedule_ids = $this->request->getPost('schedule_id');

		if ($task == 'publish') {
			$this->publish($schedule_ids);
		} elseif ($task == 'unpublish') {
			$this->unpublish($schedule_ids);
		} elseif ($task == 'delete') {
			$this->delete($schedule_ids);
		}

		if (!empty($filter)) {
			$this->session->set_userdata('schedules_filter', $filter);
		}

		$where = ' WHERE 1';
		$schedules_filter = $this->session->userdata('schedules_filter');

		/*if($schedules_filter['status'] != '') {
			$where .= ' AND status = '.$schedules_filter['status'];
		}*/

		//if(!$this->ion_auth->in_group('admin')) {
		//	$where .= ' AND created_by = '.$current_user->id;  			
		//}

		$qry = 'Select * from schedules' . $where; // select data from db

		$this->data['schedules'] = $this->db->query($qry)->result_array();
		$this->data['current_user'] = $current_user;
		$this->render('administrator/schedules/list_schedules_view');
	}


	public function fetchScheduleData()
	{

		$draw = $this->input->get('draw');
		$length = $this->input->get('length');
		$start = $this->input->get('start');
		$columns = $this->input->get('columns');
		$order = $this->input->get('order');
		$search = $this->input->get('search');

		if (isset($order)) {
			$orderfield = $columns[$order[0]['column']]['name'];
			$orderby = $order[0]['dir'];
		} else {
			$orderfield = 'id';
			$orderby = 'desc';
		}

		$result = array('data' => array());

		$qry = 'Select count(*) AS record_count from schedules'; // select data from db       	
		$data_count = $this->db->query($qry)->result_array();

		$where = ' WHERE 1';
		if ($search['value'] != '') {
			$where .= ' AND `schedule_date` LIKE "%' . $search['value'] . '%"';
		}

		$qry = 'Select * from `schedules`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby . ' LIMIT ' . $start . ', ' . $length; // select data from db 		
		$data = $this->db->query($qry)->result_array();

		$qry = 'Select count(*) AS fileter_record_count from `schedules`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby; // select data from db      
		$data_filter_count = $this->db->query($qry)->result_array();


		$result['draw'] = $draw;
		$result['recordsTotal'] = $data_count[0]['record_count'];
		$result['recordsFiltered'] = $data_filter_count[0]['fileter_record_count'];

		foreach ($data as $key => $value) {

			$checkbox = '<input type="checkbox" id="schedule_id' . $key . '" name="schedule_id[]" value="' . $value['id'] . '" />';

			$id = $value['id'];

			$status = '<div class="btn-group center-block">';
			$statusval = $value['status'];
			if ($statusval == 1) {
				$status .= anchor('administrator/schedules/unpublish/' . $value['id'], '<i class="fa fa-check"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unpublish Item'));
			} else {
				$status .= anchor('administrator/schedules/publish/' . $value['id'], '<i class="fa fa-times-circle"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Publish Item'));
			}
			$status .= anchor('administrator/schedules/copy/' . $value['id'], '<i class="fa fa-copy"></i>', array('class' => 'btn btn-sm btn-default', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Copy Item'));
			$status .= '</div>';

			$title = anchor('administrator/schedules/edit/' . $value['id'], $value['schedule_date'] . ' (' . date('l', strtotime($value['schedule_date'])) . ')', array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'));

			$created_date = date('Y-m-d', strtotime($value['created_date']));

			$class = 'btn btn-success';
			if (!$this->ion_auth->in_access('schedule_access', 'update')) {
				$class .= ' disabled';
			}
			$buttons = anchor('administrator/schedules/edit/' . $value['id'], '<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'));
			if (!in_array($value['schedule_date'], array('admin'))) {

				$class = 'btn btn-danger btn-delete';
				if (!$this->ion_auth->in_access('schedule_access', 'delete')) {
					$class .= ' disabled';
				}
				//$buttons .= '&nbsp;<a href="javascript:void(0)" data-url="'.site_url('administrator/schedules/delete/'.$value['id']).'" class="'.$class.'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>';
				$onClick = " onclick= deleteSchedule('" . site_url('administrator/schedules/delete/' . $value['id']) . "')";
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



	public function create()
	{

		if (!$this->ion_auth->in_access('schedule_access', 'add')) {
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message', 'You are not allowed to Create Schedule.');
			redirect('administrator/schedules', 'refresh');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Add Schedule';

		$task = $this->request->getPost('task');

		$this->load->library('form_validation');
		$this->load->helper("url");
		$this->form_validation->set_rules('schedule_date', 'Schedule Date', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->load->helper('form');
			$this->data['userlist'] = $this->userlist();
			$this->data['teacherlist'] = $this->teacherlist();
			$this->render('administrator/schedules/create_schedule_view');
		} else {
			$schedule_date = $this->request->getPost('schedule_date');
			$schedule_timing = json_encode($this->request->getPost('schedule_timing'));
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');

			$sql = "Insert into schedules (`schedule_date`, `schedule_timing`, `status`, `created_date`, `created_by`) VALUES ('" . addslashes($schedule_date) . "', '" . addslashes($schedule_timing) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "')";

			$val = $this->db->query($sql);

			$insert_id = $this->db->insert_id();

			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Schedule added successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/schedules/edit/' . $insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/schedules';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/schedules/create';
			} else {
				$redirect_url = 'administrator/schedules/edit/' . $insert_id;
			}

			redirect($redirect_url, 'refresh');
		}
	}


	/*public function edit($schedule_id = NULL, $task = 'save')
	{
		if(!$this->ion_auth->in_access('schedule_access', 'update'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to Edit Schedule.');
			redirect('administrator/schedules','refresh');
		}
		
		$schedule_id = $this->request->getPost('schedule_id') ? $this->request->getPost('schedule_id') : $schedule_id;
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'].' Administrator : Edit Schedule';
		$this->load->library('form_validation');
		$this->load->helper("url");
		$this->form_validation->set_rules('schedule_date','Schedule Date','trim|required');
		
		if($this->form_validation->run() === FALSE) {
			$this->load->helper('form');
			$qry ='Select * from schedules where id = '.$schedule_id ; // select data from db
       	 	$schedule = $this->db->query($qry)->result_array();
       	 	
       	 	$this->data['schedule'] = $schedule[0];
			$this->data['userlist'] = $this->userlist();
			$this->data['teacherlist'] = $this->teacherlist();
       	 	
			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['schedule']['created_by'])
			//{
			//	$this->session->set_flashdata('message_type', 'warning');
			//	$this->session->set_flashdata('message','You are not allowed to edit this Schedule.');
			//	redirect('administrator/schedules','refresh');
			//}
			$this->render('administrator/schedules/edit_schedule_view');	
					
		} else {
			
			$schedule_date = $this->request->getPost('schedule_date');
			$schedule_timing = json_encode($this->request->getPost('schedule_timing'));
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');
			
			$sql = "update schedules set `schedule_date`='".addslashes($schedule_date)."', `schedule_timing`='".addslashes($schedule_timing)."', `status`='".addslashes($status)."', `created_date`='".addslashes($created_date)."', `created_by`='".addslashes($created_by)."' where id= '".$schedule_id."'";
			
			$val = $this->db->query($sql);
			
			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Schedule updated successfully.');
			
			if($task == 'save') {
				$redirect_url = 'administrator/schedules/edit/'.$schedule_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/schedules';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/schedules/create';
			} else {
				$redirect_url = 'administrator/schedules/edit/'.$schedule_id;
			}
			
			redirect($redirect_url, 'refresh');
		}
	}*/
	public function edit($task = 'save')
	{
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Schedules';
		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('schedule_data', 'Schedule', 'required');
		// $this->form_validation->set_rules('pricing_data', 'Pricing', 'required');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'schedule_data' => [
				'label' => 'Schedule',
				'rules' => 'required'
			],
			'pricing_data' => [
				'label' => 'Pricing',
				'rules' => 'required'
			]
		]);


		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			// Validation failed
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		} else if ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$qry = 'SELECT * FROM schedules'; // select data from db
			$schedules = $this->db->query($qry)->getResultArray();

			$this->data['schedules'] = $schedules[0];

			if (!$this->ionAuthLibrary->in_group('admin')) {
				session()->setFlashdata('message_type', 'warning');
				session()->setFlashdata('message', 'You are not allowed to edit this schedules.');
				return redirect()->to('administrator/dashboard');
			}
			$this->render('administrator/schedules/edit_schedules_view');
		} else {

			$schedule_data = $this->request->getPost('schedule_data');
			$schedule_data_fr = $this->request->getPost('schedule_data_fr');
			$pricing_data = $this->request->getPost('pricing_data');
			$pricing_data_fr = $this->request->getPost('pricing_data_fr');

			$sql = "UPDATE schedules SET `schedule_data` = '" . addslashes($schedule_data) . "', `schedule_data_fr` = '" . addslashes($schedule_data_fr) . "', `pricing_data` = '" . addslashes($pricing_data) . "', `pricing_data_fr` = '" . addslashes($pricing_data_fr) . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Schedules saved successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/schedules/edit/';
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/dashboard';
			} else {
				$redirect_url = 'administrator/schedules/edit/';
			}

			return redirect()->to($redirect_url);
		}
	}


	public function unpublish($schedule_id = NULL)
	{

		if (!$this->ion_auth->in_access('schedule_access', 'update')) {
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message', 'You are not allowed to change status of Schedule.');
			redirect('administrator/schedules', 'refresh');
		}

		if (is_null($schedule_id) || empty($schedule_id)) {
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', 'There\'s no schedule to unpublish.');
		} else {

			if (is_array($schedule_id)) {
				$schedule_id = implode('","', $schedule_id);
				$qry = 'update schedules set `status` = 0 where id IN ( "' . $schedule_id . '")'; // select data from db
			} else {
				$qry = 'update schedules set `status` = 0 where id = ' . $schedule_id; // select data from db
			}
			$val = $this->db->query($qry);

			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Schedule unpublished successfully.');
		}
		redirect('administrator/schedules');
	}


	public function publish($schedule_id = NULL)
	{

		if (!$this->ion_auth->in_access('schedule_access', 'update')) {
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message', 'You are not allowed to change status of Schedule.');
			redirect('administrator/schedules', 'refresh');
		}

		if (is_null($schedule_id) || empty($schedule_id)) {
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', 'There\'s no schedule to publish.');
		} else {

			if (is_array($schedule_id)) {
				$schedule_id = implode('","', $schedule_id);
				$qry = 'update schedules set `status` = 1 where id IN ( "' . $schedule_id . '")'; // select data from db
			} else {
				$qry = 'update schedules set `status` = 1 where id = ' . $schedule_id; // select data from db
			}
			$val = $this->db->query($qry);

			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Schedule published successfully.');
		}
		redirect('administrator/schedules', 'refresh');
	}


	public function copy($schedule_id = NULL)
	{

		if (!$this->ion_auth->in_access('schedule_access', 'update')) {
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message', 'You are not allowed to change status of Schedule.');
			redirect('administrator/schedules', 'refresh');
		}

		if (is_null($schedule_id) || empty($schedule_id)) {
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', 'There\'s no schedule to publish.');
		} else {

			$qry = 'Select * from schedules where id = ' . $schedule_id; // select data from db
			$schedule = $this->db->query($qry)->result_array();

			$schedule_date = $schedule[0]['schedule_date'];
			$schedule_timing = $schedule[0]['schedule_timing'];
			$status = 0;
			$created_date = date('Y-m-d H:i:s');
			$created_by = $schedule[0]['created_by'];

			$sql = "Insert into schedules (`schedule_date`, `schedule_timing`, `status`, `created_date`, `created_by`) VALUES ('" . addslashes($schedule_date) . "', '" . addslashes($schedule_timing) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "')";

			$val = $this->db->query($sql);

			$insert_id = $this->db->insert_id();

			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Schedule copied successfully.');
		}
		redirect('administrator/schedules', 'refresh');
	}


	public function delete($schedule_id = NULL)
	{

		if (!$this->ion_auth->in_access('schedule_access', 'delete')) {
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message', 'You are not allowed to Delete Schedule.');
			redirect('administrator/schedules', 'refresh');
		}

		if (is_null($schedule_id) || empty($schedule_id)) {
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', 'There\'s no schedule to delete.');
		} else {

			if (!is_array($schedule_id)) {
				$schedule_id = array($schedule_id);
			}

			foreach ($schedule_id as $schedule) {


				$qry = 'delete from schedules where id = ' . $schedule; // select data from db
				$this->data['schedules'] = $this->db->query($qry);

				$message_type = 'success';
				$message = 'Schedule(s) deleted successfully.';
			}

			$this->session->set_flashdata('message_type', $message_type);
			$this->session->set_flashdata('message', $message);
		}
		redirect('administrator/schedules', 'refresh');
	}


	public function userlist()
	{

		$qry = 'Select * from users where active = 1'; // select data from db
		$users = $this->db->query($qry)->result_array();

		return $users;
	}



	public function teacherlist()
	{

		$qry = 'Select * from teachers where status = 1'; // select data from db
		$teachers = $this->db->query($qry)->result_array();

		return $teachers;
	}
}
