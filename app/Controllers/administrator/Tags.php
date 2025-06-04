<?php


namespace App\Controllers\administrator;

use App\Libraries\Slug;
use CodeIgniter\Database\Config;

class Tags extends AdminController
{

	public $db;
	public $slugLibrary;

	function __construct()
	{
		parent::__construct();

		//if(!$this->ion_auth->in_group('admin'))
		if (!$this->ionAuthLibrary->in_access('tag_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Tags page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}


		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];


		$config = array(
			'field' => 'alias',
			'title' => 'title',
			'table' => 'tags',
			'id' => 'id',
		);

		$this->slugLibrary = new Slug($config);
		$this->db = Config::connect();

		// $this->load->library('slug', $config);
	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Tags';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		/*require_once APPPATH.'third_party/PHPExcel.php';
        $this->excel = new PHPExcel(); 
        $file_type	= PHPExcel_IOFactory::identify(FCPATH . 'assets/xls-data/tags-list.xlsx');
	    $objReader	= PHPExcel_IOFactory::createReader($file_type);
	    $objPHPExcel = $objReader->load(FCPATH . 'assets/xls-data/tags-list.xlsx');
	    $sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);*/

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$tag_ids = $this->request->getPost('tag_id');

		if ($task == 'publish') {
			return $this->publish($tag_ids);
		} elseif ($task == 'unpublish') {
			return $this->unpublish($tag_ids);
		} elseif ($task == 'delete') {
			return $this->delete($tag_ids);
		}

		if (!empty($filter)) {
			session()->set('tags_filter', $filter);
		}

		$where = ' WHERE 1';
		$tags_filter = session()->get('tags_filter');

		/*if($tags_filter['status'] != '') {
			$where .= ' AND status = '.$tags_filter['status'];
		}*/

		//if(!$this->ion_auth->in_group('admin')) {
		//	$where .= ' AND created_by = '.$current_user->id;  			
		//}

		$qry = 'Select * from tags' . $where; // select data from db

		$this->data['tags'] = $this->db->query($qry)->getResultArray();
		$this->data['current_user'] = $current_user;
		$this->render('administrator/tags/list_tags_view');
	}


	public function fetchTagData()
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

		$qry = 'Select count(*) AS record_count from tags'; // select data from db       	
		$data_count = $this->db->query($qry)->getResultArray();

		$where = ' WHERE 1';
		if ($search['value'] != '') {
			$where .= ' AND `title` LIKE "%' . $search['value'] . '%"';
		}

		$qry = 'Select * from `tags`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby . ' LIMIT ' . $start . ', ' . $length; // select data from db 		
		$data = $this->db->query($qry)->getResultArray();

		$qry = 'Select count(*) AS fileter_record_count from `tags`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby; // select data from db      
		$data_filter_count = $this->db->query($qry)->getResultArray();


		$result['draw'] = $draw;
		$result['recordsTotal'] = $data_count[0]['record_count'];
		$result['recordsFiltered'] = $data_filter_count[0]['fileter_record_count'];

		foreach ($data as $key => $value) {

			$checkbox = '<input type="checkbox" id="tag_id' . $key . '" name="tag_id[]" value="' . $value['id'] . '" />';

			$id = $value['id'];

			$status = '<div class="btn-group center-block">';
			$statusval = $value['status'];
			if ($statusval == 1) {
				$status .= anchor('administrator/tags/unpublish/' . $value['id'], '<i class="fa fa-check"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unpublish Item'));
			} else {
				$status .= anchor('administrator/tags/publish/' . $value['id'], '<i class="fa fa-times-circle"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Publish Item'));
			}
			$status .= '</div>';

			$title = anchor('administrator/tags/edit/' . $value['id'], $value['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')) . ' <span class="small">(Alias : ' . $value['alias'] . ')</span>';

			$created_date = date('Y-m-d', strtotime($value['created_date']));

			$class = 'btn btn-success';
			if (!$this->ionAuthLibrary->in_access('tag_access', 'update')) {
				$class .= ' disabled';
			}
			$buttons = anchor('administrator/tags/edit/' . $value['id'], '<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'));
			if (!in_array($value['title'], array('admin'))) {

				$class = 'btn btn-danger btn-delete';
				if (!$this->ionAuthLibrary->in_access('tag_access', 'delete')) {
					$class .= ' disabled';
				}
				//$buttons .= '&nbsp;<a href="javascript:void(0)" data-url="'.site_url('administrator/tags/delete/'.$value['id']).'" class="'.$class.'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>';
				$onClick = " onclick= deleteTag('" . site_url('administrator/tags/delete/' . $value['id']) . "')";
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


	public function tags_modal($search_val = NULL)
	{

		$filter = array();
		$filter['search_val'] = urldecode($search_val);
		session()->set('tags_modal_filter', $filter);

		$filter = $this->request->getPost('filter');

		if (!empty($filter)) {
			session()->set('tags_modal_filter', $filter);
		}

		$where = ' WHERE `status` = 1';
		$tags_modal_filter = session()->get('tags_modal_filter');

		if (!empty($tags_modal_filter['search_val'])) {
			$where .= ' AND `title` LIKE ("%' . $tags_modal_filter['search_val'] . '%") OR `description` LIKE ("%' . $tags_modal_filter['search_val'] . '%")';
		}

		$qry = 'SELECT `id`, `title`, `alias` FROM `tags` ' . $where . ' ORDER BY `id` DESC'; // select data from db       	
		$this->data['tagslist_modal'] = $this->db->query($qry)->getResultArray();
		$this->render('administrator/tags/list_tags_modal_view');
	}


	public function create()
	{

		if (!$this->ionAuthLibrary->in_access('tag_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Create Tag.');
			return redirect()->to('administrator/tags');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Add Tag';

		$task = $this->request->getPost('task');

		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Tag Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Tag Alias', 'trim|is_unique[tags.alias]');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Tag Title',
				'rules' => 'required'
			],
			'alias' => [
				'label' => 'Tag Alias',
				'rules' => 'is_unique[tags.alias]'
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
			// $this->load->helper('form');
			$this->data['userlist'] = $this->userlist();
			$this->render('administrator/tags/create_tag_view');
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

			$sql = "Insert into tags (`title`, `alias`, `description`, `image`, `facebook_link`, `twitter_link`, `instagram_link`, `website_link`, `status`, `created_date`, `created_by`, `meta_title`, `meta_description`) VALUES ('" . addslashes($title) . "', '" . addslashes($alias) . "', '" . addslashes($description) . "', '" . addslashes($image) . "', '" . addslashes($facebook_link) . "', '" . addslashes($twitter_link) . "', '" . addslashes($instagram_link) . "', '" . addslashes($website_link) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "', '" . addslashes($meta_title) . "', '" . addslashes($meta_description) . "')";

			$val = $this->db->query($sql);

			$insert_id = $this->db->insertID();

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Tag added successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/tags/edit/' . $insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/tags';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/tags/create';
			} else {
				$redirect_url = 'administrator/tags/edit/' . $insert_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function edit($tag_id = NULL, $task = 'save')
	{
		if (!$this->ionAuthLibrary->in_access('tag_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit Tag.');
			return redirect()->to('administrator/tags');
		}

		$tag_id = $this->request->getPost('tag_id') ? $this->request->getPost('tag_id') : $tag_id;
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit Tag';
		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Tag Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Tag Alias', 'trim');
		// $this->form_validation->set_rules('facebook_link', 'Facebook URL', 'trim|valid_url');
		// $this->form_validation->set_rules('twitter_link', 'Twitter URL', 'trim|valid_url');
		// $this->form_validation->set_rules('instagram_link', 'Instagram URL', 'trim|valid_url');
		// $this->form_validation->set_rules('website_link', 'Website URL', 'trim|valid_url');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Tag Title',
				'rules' => 'required'
			],
			// 'alias' => [
			// 	'label' => 'Tag Alias',
			// 	'rules' => 'is_unique[tags.alias]'
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
		}

		if ($this->request->getMethod() == "GET") {
			// $this->load->helper('form');
			$qry = 'Select * from tags where id = ' . $tag_id; // select data from db
			$tag = $this->db->query($qry)->getResultArray();

			$this->data['tag'] = $tag[0];
			$this->data['userlist'] = $this->userlist();

			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['tag']['created_by'])
			//{
			//	session()->setFlashdata('message_type', 'warning');
			//	session()->setFlashdata('message','You are not allowed to edit this Tag.');
			//	return redirect()->to('administrator/tags','refresh');
			//}
			$this->render('administrator/tags/edit_tag_view');
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

			$alias = $this->slugLibrary->create_uri($data, $tag_id);
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

			$sql = "update tags set `title`='" . addslashes($title) . "', `alias`='" . addslashes($alias) . "', `description`='" . addslashes($description) . "', `image`='" . addslashes($image) . "', `facebook_link`='" . addslashes($facebook_link) . "', `twitter_link`='" . addslashes($twitter_link) . "', `instagram_link`='" . addslashes($instagram_link) . "', `website_link`='" . addslashes($website_link) . "', `status`='" . addslashes($status) . "', `created_date`='" . addslashes($created_date) . "', `created_by`='" . addslashes($created_by) . "', `meta_title`='" . addslashes($meta_title) . "', `meta_description`='" . addslashes($meta_description) . "' where id= '" . $tag_id . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Tag updated successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/tags/edit/' . $tag_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/tags';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/tags/create';
			} else {
				$redirect_url = 'administrator/tags/edit/' . $tag_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function unpublish($tag_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('tag_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Tag.');
			return redirect()->to('administrator/tags');
		}

		if (is_null($tag_id) || empty($tag_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no tag to unpublish.');
		} else {

			if (is_array($tag_id)) {
				$tag_id = implode('","', $tag_id);
				$qry = 'update tags set `status` = 0 where id IN ( "' . $tag_id . '")'; // select data from db
			} else {
				$qry = 'update tags set `status` = 0 where id = ' . $tag_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Tag unpublished successfully.');
		}
		return redirect()->to('administrator/tags');
	}


	public function publish($tag_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('tag_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Tag.');
			return redirect()->to('administrator/tags');
		}

		if (is_null($tag_id) || empty($tag_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no tag to publish.');
		} else {

			if (is_array($tag_id)) {
				$tag_id = implode('","', $tag_id);
				$qry = 'update tags set `status` = 1 where id IN ( "' . $tag_id . '")'; // select data from db
			} else {
				$qry = 'update tags set `status` = 1 where id = ' . $tag_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Tag published successfully.');
		}
		return redirect()->to('administrator/tags');
	}


	public function delete($tag_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('tag_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Tag.');
			return redirect()->to('administrator/tags');
		}

		if (is_null($tag_id) || empty($tag_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no tag to delete.');
		} else {

			if (!is_array($tag_id)) {
				$tag_id = array($tag_id);
			}

			foreach ($tag_id as $tag) {

				$checkStatus = $this->checkTagExistInArticles($tag);
				if ($checkStatus == 0) {

					$qry = 'delete from tags where id = ' . $tag; // select data from db
					$this->data['tags'] = $this->db->query($qry);

					$message_type = 'success';
					$message = 'Tag(s) deleted successfully.';
				} else {

					$message_type = 'warning';
					$message = 'Some tag(s) could not deleted, Because they are assinged in article(s).';
				}
			}

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect()->to('administrator/tags');
	}


	public function userlist()
	{

		$qry = 'Select * from users where active = 1'; // select data from db
		$users = $this->db->query($qry)->getResultArray();

		return $users;
	}


	public function checkTagExistInArticles($tag_id)
	{

		$qry = 'Select * from articles where find_in_set (' . $tag_id . ', tag_ids) <> 0'; // select data from db       
		$articles = $this->db->query($qry)->getResultArray();

		return count($articles);
	}
}
