<?php


namespace App\Controllers\administrator;

use CodeIgniter\Database\Config;

class Subscribers extends AdminController
{

	public $db;

	function __construct()
	{
		parent::__construct();

		if (!$this->ionAuthLibrary->in_access('subscriber_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the subscribers page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$this->db = Config::connect();

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Subscribers';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$subscriber_ids = $this->request->getPost('subscriber_id');

		if ($task == 'publish') {
			return $this->publish($subscriber_ids);
		} elseif ($task == 'unpublish') {
			return $this->unpublish($subscriber_ids);
		} elseif ($task == 'delete') {
			return $this->delete($subscriber_ids);
		}

		if (!empty($filter)) {
			session()->set('subscribers_filter', $filter);
		}

		$where = ' WHERE 1';
		$subscribers_filter = session()->get('subscribers_filter');

		if (isset($subscribers_filter['status']) && $subscribers_filter['status'] != '') {
			$where .= ' AND status = ' . $subscribers_filter['status'];
		}

		$qry = 'Select * from subscribers' . $where; // select data from db
		
		$this->data['subscribers'] = $this->db->query($qry)->getResultArray();
		$this->data['current_user'] = $current_user;
		$this->render('administrator/subscribers/list_subscribers_view');
	}


	public function unpublish($subscriber_id = NULL)
	{

		if (is_null($subscriber_id) || empty($subscriber_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no subscriber to unpublish.');
		} else {

			if (is_array($subscriber_id)) {
				$subscriber_id = implode('","', $subscriber_id);
				$qry = 'update subscribers set `status` = 0 where id IN ( "' . $subscriber_id . '")'; // select data from db
			} else {
				$qry = 'update subscribers set `status` = 0 where id = ' . $subscriber_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Subscriber unpublished successfully.');
		}
		redirect()->to('administrator/subscribers');
	}


	public function publish($subscriber_id = NULL)
	{

		if (is_null($subscriber_id) || empty($subscriber_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no subscriber to publish.');
		} else {

			if (is_array($subscriber_id)) {
				$subscriber_id = implode('","', $subscriber_id);
				$qry = 'update subscribers set `status` = 1 where id IN ( "' . $subscriber_id . '")'; // select data from db
			} else {
				$qry = 'update subscribers set `status` = 1 where id = ' . $subscriber_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Subscriber published successfully.');
		}
		redirect()->to('administrator/subscribers');
	}


	public function delete($subscriber_id = NULL)
	{
		if (!$this->ionAuthLibrary->in_access('subscriber_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Subscriber.');
			redirect()->to('administrator/subscribers');
		}

		if (is_null($subscriber_id) || empty($subscriber_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no subscriber to delete.');
		} else {

			if (!is_array($subscriber_id)) {
				$subscriber_id = array($subscriber_id);
			}

			foreach ($subscriber_id as $subscriber) {

				$qry = 'delete from subscribers where id = ' . $subscriber; // select data from db
				$this->data['subscribers'] = $this->db->query($qry);

				$message_type = 'success';
				$message = 'Subscriber(s) deleted successfully.';
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		redirect()->to('administrator/subscribers');
	}
}
