<?php

namespace App\Controllers\administrator;




class Albums extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		
		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
		
		//if(!$this->ion_auth->in_group('admin'))
		if(!$this->ion_auth->in_access('album_access', 'access'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to visit the Albums page.');
			redirect('administrator','refresh');
		}
		
		$config = array(
			'field' => 'alias',
			'title' => 'title',
			'table' => 'albums',
			'id' => 'id',
		);
		
		$this->load->library('slug', $config);
		
	}
  

	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'].' Administrator : Albums';
  		$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');
  		
  		$task = $this->input->post('task');
  		$filter = $this->input->post('filter');
  		  		
  		$album_ids = $this->input->post('album_id');
  		
  		if($task == 'publish') {
  			$this->publish($album_ids);
  		} elseif($task == 'unpublish') {
  			$this->unpublish($album_ids);
  		} elseif($task == 'delete') {
  			$this->delete($album_ids);
  		}
  		  		
  		if(!empty($filter)) {
  			$this->session->set_userdata('albums_filter', $filter);  			
  		}
  		
  		$where = ' WHERE 1';
  		$albums_filter = $this->session->userdata('albums_filter');
  		
		if($albums_filter['status'] != '') {
			$where .= ' AND status = '.$albums_filter['status'];
		}
  		
  		//if(!$this->ion_auth->in_group('admin')) {
	  	//	$where .= ' AND created_by = '.$current_user->id;  			
  		//}
		
       	$qry ='Select * from albums'.$where; // select data from db
       	
       	$this->data['albums'] = $this->db->query($qry)->result_array();
       	$this->data['current_user'] = $current_user;
  		$this->render('administrator/albums/list_albums_view');
	}
	
	
	public function create()
	{
		if(!$this->ion_auth->in_access('album_access', 'add'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to Create Album.');
			redirect('administrator/albums','refresh');
		}
		
		$this->data['page_title'] = $this->data['page_title'].' Administrator : Add Album';
				
		$task = $this->input->post('task');
		
		$this->load->library('form_validation');
		$this->load->helper("url");
		$this->form_validation->set_rules('title','Album Title','trim|required');
		$this->form_validation->set_rules('alias','Album Alias','trim|is_unique[albums.alias]');
		
		if($this->form_validation->run()===FALSE) {
			
			$this->load->helper('form');
			$this->data['userlist'] = $this->userlist();
			$this->render('administrator/albums/create_album_view');
			
		} else {
						
			$title = $this->input->post('title');
			$aliasTemp = $this->input->post('alias');
			if($aliasTemp == '') {
				$data = array(
					'title' => $title,
				);
			} else {
				$data = array(
					'title' => $aliasTemp,
				);
			}
			$alias = $this->slug->create_uri($data);
			$album_images = json_encode($this->input->post('album_images'));
			$status = $this->input->post('status');
			$created_date = $this->input->post('created_date');
			$created_by = $this->input->post('created_by');
			
			$sql = "Insert into albums (`title`, `alias`, `album_images`, `status`, `created_date`, `created_by`) VALUES ('".addslashes($title)."', '".addslashes($alias)."', '".addslashes($album_images)."', '".addslashes($status)."', '".addslashes($created_date)."', '".addslashes($created_by)."')";
			
            $val = $this->db->query($sql);
            
            $insert_id = $this->db->insert_id();
			
            $this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message','Album added successfully.');
			
			if($task == 'save') {
				$redirect_url = 'administrator/albums/edit/'.$insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/albums';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/albums/create';
			} else {
				$redirect_url = 'administrator/albums/edit/'.$insert_id;
			}
			
			redirect($redirect_url,'refresh');
			
		}
	}
	
	
	public function edit($album_id = NULL, $task = 'save')
	{
		
		if(!$this->ion_auth->in_access('album_access', 'update'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to Edit Album.');
			redirect('administrator/albums','refresh');
		}
		
		$album_id = $this->input->post('album_id') ? $this->input->post('album_id') : $album_id;
		$task = $this->input->post('task') ? $this->input->post('task') : $task;
		$this->data['page_title'] = $this->data['page_title'].' Administrator : Edit Album';
		$this->load->library('form_validation');
		$this->load->helper("url");
		$this->form_validation->set_rules('title','Album Title','trim|required');
		$this->form_validation->set_rules('alias','Album Alias','trim');
		
		if($this->form_validation->run() === FALSE) {
			
			$this->load->helper('form');
			$qry ='Select * from albums where id = '.$album_id ; // select data from db
       	 	$album = $this->db->query($qry)->result_array();
       	 	
       	 	$this->data['album'] = $album[0];
			$this->data['userlist'] = $this->userlist();
       	 	
			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['album']['created_by'])
			//{
			//	$this->session->set_flashdata('message_type', 'warning');
			//	$this->session->set_flashdata('message','You are not allowed to edit this Album.');
			//	redirect('administrator/albums','refresh');
			//}
			$this->render('administrator/albums/edit_album_view');	
					
		} else {
			
			$title = $this->input->post('title');
			$aliasTemp = $this->input->post('alias');
			if($aliasTemp == '') {
				$data = array(
					'title' => $title,
				);
			} else {
				$data = array(
					'title' => $aliasTemp,
				);
			}
						
			$alias = $this->slug->create_uri($data, $album_id);
			$album_images = json_encode($this->input->post('album_images'));
			$status = $this->input->post('status');
			$created_date = $this->input->post('created_date');
			$created_by = $this->input->post('created_by');
			
			$sql = "update albums set `title`='".addslashes($title)."', `alias`='".addslashes($alias)."', `album_images`='".addslashes($album_images)."', `status`='".addslashes($status)."', `created_date`='".addslashes($created_date)."', `created_by`='".addslashes($created_by)."' where id= '".$album_id."'";
			
			$val = $this->db->query($sql);
			
			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Album updated successfully.');
			
			if($task == 'save') {
				$redirect_url = 'administrator/albums/edit/'.$album_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/albums';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/albums/create';
			} else {
				$redirect_url = 'administrator/albums/edit/'.$album_id;
			}
			
			redirect($redirect_url, 'refresh');
			
		}
	}

	
	public function unpublish($album_id = NULL) {
		
		if(!$this->ion_auth->in_access('album_access', 'update'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to change status of Album.');
			redirect('administrator/albums','refresh');
		}
		
		if(is_null($album_id) || empty($album_id)) {
			
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message','There\'s no album to unpublish.');
			
		} else {
			
			if(is_array($album_id)) {
				$album_id = implode('","', $album_id);
	        	$qry ='update albums set `status` = 0 where id IN ( "'.$album_id.'")'; // select data from db
			} else {
				$qry ='update albums set `status` = 0 where id = '.$album_id; // select data from db
			}
       	 	$val = $this->db->query($qry);
        	
       	 	$this->session->set_flashdata('message_type', 'success');
       	 	$this->session->set_flashdata('message','Album unpublished successfully.');
       	 	
		}
		redirect('administrator/albums');
		
	}
	
	
	public function publish($album_id = NULL) {
		
		if(!$this->ion_auth->in_access('album_access', 'update'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to change status of Album.');
			redirect('administrator/albums','refresh');
		}
		
		if(is_null($album_id) || empty($album_id)) {
			
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message','There\'s no album to publish.');
			
		} else {
						
			if(is_array($album_id)) {
				$album_id = implode('","', $album_id);
        		$qry ='update albums set `status` = 1 where id IN ( "'.$album_id.'")'; // select data from db
			} else {
				$qry ='update albums set `status` = 1 where id = '.$album_id; // select data from db
			}
	       	$val = $this->db->query($qry);

	       	$this->session->set_flashdata('message_type', 'success');
       	 	$this->session->set_flashdata('message', 'Album published successfully.');
       	 	
		}
		redirect('administrator/albums','refresh');
		
	}
	
	
	public function delete($album_id = NULL)
	{
		if(!$this->ion_auth->in_access('album_access', 'delete'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to Delete Album.');
			redirect('administrator/albums','refresh');
		}
		
		if(is_null($album_id) || empty($album_id)) {
			
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message','There\'s no album to delete.');
			
		} else {
			
			if(!is_array($album_id)) {
				$album_id = array($album_id);
			}
			
			$album_id = implode('","', $album_id);
			$qry ='delete from albums where id IN ( "'.$album_id.'")'; // select data from db
				
			$this->data['albums'] = $this->db->query($qry);
			
			$message_type = 'success';
			$message = 'Album(s) deleted successfully.';
									
			$this->session->set_flashdata('message_type', $message_type);
       	 	$this->session->set_flashdata('message', $message);
       	 	
		}
		redirect('administrator/albums','refresh');
	}
	
		
	public function userlist() {
				
       	$qry ='Select * from users where active = 1'; // select data from db
       	$users = $this->db->query($qry)->result_array();
       	
       	return $users;
	}
	
	
}