<?php

namespace App\Controllers\administrator;

use CodeIgniter\Database\Config;

class Contacts extends AdminController
{

	public $db;

	function __construct()
	{
		parent::__construct();

		//if(!$this->ion_auth->in_group('admin'))
		if (!$this->ionAuthLibrary->in_access('contact_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Contacts page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];

		$this->db = Config::connect();
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Contacts';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		/*require_once APPPATH.'third_party/PHPExcel.php';
        $this->excel = new PHPExcel(); 
        $file_type	= PHPExcel_IOFactory::identify(FCPATH . 'assets/xls-data/contacts-list.xlsx');
	    $objReader	= PHPExcel_IOFactory::createReader($file_type);
	    $objPHPExcel = $objReader->load(FCPATH . 'assets/xls-data/contacts-list.xlsx');
	    $sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);*/

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$contact_ids = $this->request->getPost('contact_id');

		if ($task == 'publish') {
			// return $this->publish($contact_ids);
		} elseif ($task == 'unpublish') {
			// return $this->unpublish($contact_ids);
		} elseif ($task == 'delete') {
			return $this->delete($contact_ids);
		}

		if (!empty($filter)) {
			session()->set('contacts_filter', $filter);
		}

		$where = ' WHERE 1';
		$contacts_filter = session()->get('contacts_filter');

		/*if($contacts_filter['status'] != '') {
			$where .= ' AND status = '.$contacts_filter['status'];
		}*/

		//if(!$this->ion_auth->in_group('admin')) {
		//	$where .= ' AND created_by = '.$current_user->id;  			
		//}

		$qry = 'Select * from contacts' . $where; // select data from db

		$this->data['contacts'] = $this->db->query($qry)->getResultArray();
		$this->data['current_user'] = $current_user;
		$this->render('administrator/contacts/list_contacts_view');
	}


	public function fetchContactData()
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

		$qry = 'Select count(*) AS record_count from contacts'; // select data from db       	
		$data_count = $this->db->query($qry)->getResultArray();

		$where = ' WHERE 1';
		if ($search['value'] != '') {
			$where .= ' AND `title` LIKE "%' . $search['value'] . '%"';
		}

		$qry = 'Select * from `contacts`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby . ' LIMIT ' . $start . ', ' . $length; // select data from db      

		$data = $this->db->query($qry)->getResultArray();

		$result['draw'] = $draw;
		$result['recordsTotal'] = count($data);
		$result['recordsFiltered'] = $data_count[0]['record_count'];

		foreach ($data as $key => $value) {

			$checkbox = '<input type="checkbox" id="contact_id' . $key . '" name="contact_id[]" value="' . $value['id'] . '" />';

			$id = $value['id'];

			$full_name = $value['full_name'];
			$email_address = $value['email_address'];
			$message = '<p><b>' . $value['subject'] . '</b></p>' . $value['message'];

			$created_date = date('Y-m-d', strtotime($value['created_date']));

			$class = 'btn btn-danger btn-delete';
			if (!$this->ionAuthLibrary->in_access('contact_access', 'delete')) {
				$class .= ' disabled';
			}
			$onClick = " onclick= deleteContact('" . site_url('administrator/contacts/delete/' . $value['id']) . "')";
			$buttons = '&nbsp;<a href="javascript:void(0)" ' . $onClick . ' class="' . $class . '" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>';

			$result['data'][$key] = array(
				$checkbox,
				$id,
				$full_name,
				$email_address,
				$message,
				$created_date,
				$buttons
			);
		}

		echo json_encode($result);
	}


	public function delete($contact_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('contact_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Contact.');
			return redirect()->to('administrator/contacts');
		}

		if (is_null($contact_id) || empty($contact_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no contact to delete.');
		} else {

			if (!is_array($contact_id)) {
				$contact_id = array($contact_id);
			}

			foreach ($contact_id as $contact) {

				//$checkStatus = $this->checkContactExistInArticles($contact);
				//if($checkStatus == 0) {	

				$qry = 'delete from contacts where id = ' . $contact; // select data from db
				$this->data['contacts'] = $this->db->query($qry);

				$message_type = 'success';
				$message = 'Contact(s) deleted successfully.';

				//} else {

				//	$message_type = 'warning';
				//	$message = 'Some contact(s) could not deleted, Because they are assinged in article(s).';

				//}
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect()->to('administrator/contacts');
	}
}
