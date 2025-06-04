<?php

namespace App\Controllers\administrator;



class Videos extends Admin_Controller {


   function __construct()
	{
		parent::__construct();
		
		$siteConfiguration = siteConfiguration();
		
		// MANGOMOLO LIBRARY LOADED
		$this->load->library('mangomolo', array());
		
		$this->data['page_title'] = $siteConfiguration['site_title'];
		
		//if(!$this->ion_auth->in_group('admin'))
		if(!$this->ion_auth->in_access('video_access', 'access'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to visit the Videos page.');
			redirect('administrator','refresh');
		}
		
	}
  

	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'].' Administrator : Videos';
  		$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');
  		
  		$where = ' WHERE 1';
  		//if(!$this->ion_auth->in_group('admin')) {
	  	//	$where .= ' AND created_by = '.$current_user->id;  			
  		//}
		
       	$qry ='Select * from videos'.$where; // select data from db
       	
       	$this->data['videos'] = $this->db->query($qry)->result_array();
       	$this->data['current_user'] = $current_user;
  		$this->render('administrator/videos/list_videos_view');
	}
	
	
	
	public function fetchVideoData() {
		
		$draw = $this->input->get('draw');
		$length = $this->input->get('length');
		$start = $this->input->get('start');
		$columns = $this->input->get('columns');
		$order = $this->input->get('order');
		$search = $this->input->get('search');
		
		if(isset($order)) {
			$orderfield = $columns[$order[0]['column']]['name'];
			$orderby = $order[0]['dir'];
		} else {
			$orderfield = 'id';
			$orderby = 'desc';
		}
		
		$result = array('data' => array());

		$qry ='Select count(*) AS record_count from videos'; // select data from db       	
       	$data_count = $this->db->query($qry)->result_array();
       			
		$where = ' WHERE 1';
		if($search['value'] != '') {
			$where .= ' AND `title` LIKE "%'.$search['value'].'%"';
		}
		
		$qry = 'Select * from `videos`'.$where .' ORDER BY `'.$orderfield.'` '.$orderby.' LIMIT '.$start.', '.$length; // select data from db      
       	$data = $this->db->query($qry)->result_array();
       	
       	$qry = 'Select count(*) AS fileter_record_count from `videos`'.$where .' ORDER BY `'.$orderfield.'` '.$orderby; // select data from db      
       	$data_filter_count = $this->db->query($qry)->result_array();
       	
       	       	
       	$result['draw'] = $draw;
		$result['recordsTotal'] = $data_count[0]['record_count'];
		$result['recordsFiltered'] = $data_filter_count[0]['fileter_record_count'];
       	
       	foreach ($data AS $key => $value) {
       		
       		      		
       		$id = $value['id'];
       		
       		$featured = '<div class="btn-group center-block">';
       						$staus = $value['featured'];
							if($staus == 1) {
								$featured .= anchor('administrator/videos/unfeatured/'.$value['id'], '<i class="fa fa-star"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unfeatured Item'));
							} else {
								$featured .= anchor('administrator/videos/featured/'.$value['id'], '<i class="fa fa-star-o"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Featured Item'));
							}
			$featured .= '</div>';
			
			$title = anchor('administrator/videos/edit/'.$value['id'], $value['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'));
			
			$created_date = date('Y-m-d', strtotime($value['created_date']));
			
			$class = 'btn btn-success';
			if(!$this->ion_auth->in_access('video_access', 'update')) {  $class .= ' disabled'; } 
			$buttons = anchor('administrator/videos/edit/'.$value['id'],'<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'));
       		
			$result['data'][$key] = array(
				$id,
                $featured,
				$title,
                $created_date,
				$buttons
			);
			
       	}
       	
       	echo json_encode($result);
		
	}
	
	
	
	public function create()
	{
		if(!$this->ion_auth->in_access('video_access', 'add'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to Add Video.');
			redirect('administrator/videos','refresh');
		}
		
		$this->data['page_title'] = $this->data['page_title'].' Administrator : Add Video';
				
		$task = $this->input->post('task');
		
		$this->load->library('form_validation');
		$this->load->helper("url");
		$this->form_validation->set_rules('title','Video Title','trim|required');
		$this->form_validation->set_rules('category_id','Category','required');
		//$this->form_validation->set_rules('video_file','Video','required');
		
		if($this->form_validation->run() === FALSE) {
			
			$this->load->helper('form');
			$this->data['categorieslist'] = $this->mangomolo->GetMangomoloVideoCategoryList();
			$this->data['userlists'] = $this->userlist();
			$this->data['presenterlists'] = $this->presenterlist();
			$this->render('administrator/videos/create_video_view');
			
		} else {
			
			$featured = $this->input->post('featured');
			$title = $this->input->post('title');
			$description = $this->input->post('description');
			$category_id = $this->input->post('category_id');
			$author_id = $this->input->post('author_id');
			$presenter_id = $this->input->post('presenter_id');
			$video_file = $_FILES['video_file'];
			$created_date = $this->input->post('created_date');
			$created_by = $this->input->post('created_by');
			
			$cfile = curl_file_create($video_file['tmp_name'], 'video/mp4', 'mangovideo');
			
			$data = array();			
			$data['featured'] = $featured;
			$data['title'] = $title;
			$data['category_id'] = $category_id;
			$data['presenter_id'] = $presenter_id;
			$data['description'] = $description;
			$data['cfile'] = $cfile;
			
			$uploadedResponse = $this->mangomolo->UploadMangomoloVideo($data);
			$result = json_decode($uploadedResponse);
			$video_fileData = $uploadedResponse;
						
			if($result->error == 'false' || $result->error == '') {
			
				$sql = "Insert into videos (`featured`, `mangovideo_id`, `category_id`, `author_id`, `presenter_id`, `title`, `description`, `video_file`, `created_date`, `created_by`) VALUES ('".addslashes($featured)."', '".addslashes($result->video_id)."', '".addslashes($category_id)."', '".addslashes($author_id)."', '".addslashes($presenter_id)."', '".addslashes($title)."', '".addslashes($description)."', '".addslashes($video_fileData)."', '".addslashes($created_date)."', '".addslashes($created_by)."')";
				
	            $val = $this->db->query($sql);
	            
	            $insert_id = $this->db->insert_id();
	            
	            // SET FEATURED
				if($featured == 1) {
	        		$featuredResponse = $this->mangomolo->SetMangomoloVideoFeatured($result->video_id, 1);
				} else {
					$featuredResponse = $this->mangomolo->SetMangomoloVideoFeatured($result->video_id, 0);
				}
	            	            
	            $this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message','Video added successfully.');
				
				if($task == 'save') {
					$redirect_url = 'administrator/videos/edit/'.$insert_id;
				} elseif ($task == 'save_close') {
					$redirect_url = 'administrator/videos';
				} elseif ($task == 'save_new') {
					$redirect_url = 'administrator/videos/create';
				} else {
					$redirect_url = 'administrator/videos/edit/'.$insert_id;
				}
				
			} else {
				
				$redirect_url = 'administrator/videos/create';
				$this->session->set_flashdata('message_type', 'error');
				$this->session->set_flashdata('message',$result->error.' Please try again.');
				
			}
			
			redirect($redirect_url,'refresh');
		}
	}
	
	
	public function edit($video_id = NULL, $task = 'save')
	{
		if(!$this->ion_auth->in_access('video_access', 'update'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to Edit Video.');
			redirect('administrator/videos','refresh');
		}
		
		$video_id = $this->input->post('video_id') ? $this->input->post('video_id') : $video_id;
		$task = $this->input->post('task') ? $this->input->post('task') : $task;
		$this->data['page_title'] = $this->data['page_title'].' Administrator : Edit Video';
		$this->load->library('form_validation');
		$this->load->helper("url");
		$this->form_validation->set_rules('title','Video Title','trim|required');
				
		if($this->form_validation->run() === FALSE) {
			$this->load->helper('form');
			$qry ='Select * from videos where id = '.$video_id ; // select data from db
       	 	$video = $this->db->query($qry)->result_array();
       	 	$this->data['video'] = $video[0];
       	 	$this->data['mangomolovideo_details'] = $this->mangomolo->GetMangomoloVideoDetailsByID($video[0]['mangovideo_id']);
       	 	$this->data['categorieslist'] = $this->mangomolo->GetMangomoloVideoCategoryList();
			$this->data['userlists'] = $this->userlist();
			$this->data['presenterlists'] = $this->presenterlist();
       	 	
			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['video']['created_by'])
			//{
			//	$this->session->set_flashdata('message_type', 'warning');
			//	$this->session->set_flashdata('message','You are not allowed to edit this Video.');
			//	redirect('administrator/videos','refresh');
			//}
			$this->render('administrator/videos/edit_video_view');			
		} else {

			$mangovideo_id = $this->input->post('mangovideo_id');
			$featured = $this->input->post('featured');
			$title = $this->input->post('title');
			$description = $this->input->post('description');
			$author_id = $this->input->post('author_id');
			$presenter_id = $this->input->post('presenter_id');
			$created_date = $this->input->post('created_date');
			$created_by = $this->input->post('created_by');
			
			$sql = "update videos set `featured`='".addslashes($featured)."', `title`='".addslashes($title)."', `description`='".addslashes($description)."', `author_id`='".addslashes($author_id)."', `presenter_id`='".addslashes($presenter_id)."', `created_date`='".addslashes($created_date)."', `created_by`='".addslashes($created_by)."' where id= '".$video_id."'";
			
			$val = $this->db->query($sql);
			
			// SET FEATURED
			if($featured == 1) {
        		$featuredResponse = $this->mangomolo->SetMangomoloVideoFeatured($mangovideo_id, 1);
			} else {
				$featuredResponse = $this->mangomolo->SetMangomoloVideoFeatured($mangovideo_id, 0);
			}
        	
			
			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Video updated successfully.');
			
			if($task == 'save') {
				$redirect_url = 'administrator/videos/edit/'.$video_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/videos';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/videos/create';
			} else {
				$redirect_url = 'administrator/videos/edit/'.$video_id;
			}
			
			redirect($redirect_url, 'refresh');
		}
	}

	
	public function delete($video_id = NULL)
	{
		if(!$this->ion_auth->in_access('video_access', 'delete'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to Delete Video.');
			redirect('administrator/videos','refresh');
		}
		
		if(is_null($video_id) || empty($video_id)) {			
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message','There\'s no video to delete.');
		} else {
			
			if(is_array($video_id)) {
				$video_id = implode('","', $video_id);
				$qry ='delete from videos where id IN ( "'.$video_id.'")'; // select data from db
			} else {
				$qry ='delete from videos where id = '.$video_id ; // select data from db
			}
			
			$this->data['videos'] = $this->db->query($qry);
			
			$message_type = 'success';
    		$message = 'Video deleted successfully.';
			
			$this->session->set_flashdata('message_type', $message_type);
       	 	$this->session->set_flashdata('message', $message);
		}
		redirect('administrator/videos','refresh');
	}
	
			
	public function userlist() {
				
       	$qry ='Select * from users where active = 1'; // select data from db
       	$users = $this->db->query($qry)->result_array();
       	
       	return $users;
	}
	
			
	public function presenterlist() {
				
       	$qry ='Select * from presenters where status = 1'; // select data from db
       	$users = $this->db->query($qry)->result_array();
       	
       	return $users;
	}
    

	public function unfeatured($video_id = NULL) {
			
		if(!$this->ion_auth->in_access('video_access', 'update'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to update status of video.');
			redirect('administrator/videos','refresh');
		}
			
		if(is_null($video_id) || empty($video_id)) {
			
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message','There\'s no video to unfeatured.');
			
		} else {
			
			$qry ='Select * from videos where id = '.$video_id ; // select data from db
       	 	$video = $this->db->query($qry)->result_array();
       	 	
			if(is_array($video_id)) {
				$video_id = implode('","', $video_id);	        	
	        	$qry ='update videos set `featured` = "0" where id IN ( "'.$video_id.'")'; // select data from db
			} else {
				$qry ='update videos set `featured` = "0" where id = '.$video_id; // select data from db
			}
			
       	 	$val = $this->db->query($qry);
       	 	
       	 	// SET VIDEO UNFEATURED
       	 	$featuredResponse = $this->mangomolo->SetMangomoloVideoFeatured($video[0]['mangovideo_id'], 0);
       	 	        	
       	 	$this->session->set_flashdata('message_type', 'success');
       	 	$this->session->set_flashdata('message','Video unfeatured successfully.');
       	 	
		}
		redirect('administrator/videos');
		
	}
	
	
	public function featured($video_id = NULL) {
		
		if(!$this->ion_auth->in_access('video_access', 'update'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to update status of video.');
			redirect('administrator/videos','refresh');
		}
		
		if(is_null($video_id) || empty($video_id)) {
			
			$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message','There\'s no video to featured.');
			
		} else {
			
			$qry ='Select * from videos where id = '.$video_id ; // select data from db
       	 	$video = $this->db->query($qry)->result_array();
			
			if(is_array($video_id)) {
				$video_id = implode('","', $video_id);
        		$qry ='update videos set `featured` = "1" where id IN ( "'.$video_id.'")'; // select data from db
			} else {
				$qry ='update videos set `featured` = "1" where id = '.$video_id; // select data from db
			}
			
	       	$val = $this->db->query($qry);

	       	// SET VIDEO FEATURED
       	 	$featuredResponse = $this->mangomolo->SetMangomoloVideoFeatured($video[0]['mangovideo_id'], 1);
	       	
	       	$this->session->set_flashdata('message_type', 'success');
       	 	$this->session->set_flashdata('message', 'Video featured successfully.');
       	 	
		}
		redirect('administrator/videos','refresh');
		
	}
	
	
	
	public function importMamgoVideosAjax() {
		
		$page = $this->security->xss_clean($this->input->post("page"));
		$limit = $this->security->xss_clean($this->input->post("limit"));
		
		$mangovideos = $this->mangomolo->getVideosByUserMangomoloVideo($page, $limit);
		
		$message = array();
		if(!empty($mangovideos->results)) {
			foreach ($mangovideos->results AS $result) {
				if($this->checkVideoExist($result->id)) {
					
					$title = $result->title_ar;
					$category_id = $result->category_id;
					$video_details = json_encode($result);
					
					$sql = "Update videos set `title`='".addslashes($title)."', `category_id`='".addslashes($category_id)."', `video_details`='".addslashes($video_details)."' where mangovideo_id = '".$result->id."'";
					$val = $this->db->query($sql);
					
					$message[] = $result->title_ar.' - ('.$result->id.') Video updated successfully.'; 
					
				} else {

					$featured = 0;
					$mangovideo_id = $result->id;
					$category_id = $result->category_id;
					$author_id = '';
					$presenter_id = '';
					$title = $result->title_ar;
					$description = '';
					$video_fileData = '';
					$video_details = json_encode($result);
					//$created_date = $result->create_time;
					$created_date = date('Y-m-d H:i:s');
					$created_by = 1;
					
					$sql = "Insert into videos (`featured`, `mangovideo_id`, `category_id`, `author_id`, `presenter_id`, `title`, `description`, `video_file`, `video_details`, `created_date`, `created_by`) VALUES ('".addslashes($featured)."', '".addslashes($mangovideo_id)."', '".addslashes($category_id)."', '".addslashes($author_id)."', '".addslashes($presenter_id)."', '".addslashes($title)."', '".addslashes($description)."', '".addslashes($video_fileData)."', '".addslashes($video_details)."', '".addslashes($created_date)."', '".addslashes($created_by)."')";
		            $val = $this->db->query($sql);	            
		            $insert_id = $this->db->insert_id();
					
		            $message[] = $result->title_ar.' - ('.$result->id.') Video imported successfully.';
		            
				}
			}
		}
			
		$response = array();
		$response['notifications'] = $message;
		$response['page'] = $page + 1;
        $response['csrf_test_name'] = $this->security->get_csrf_hash();
        
        echo json_encode($response);
	}
	
	
	public function checkVideoExist($mangovideo_id) {
		
		$where = ' WHERE mangovideo_id = '.$mangovideo_id;
  		$qry ='Select * from videos'.$where; // select data from db
       	
       	$video = $this->db->query($qry)->result_array();
       	
       	return count($video);
       			
	}
	
	
}

/* End of file dashboard.php */
/* Location: ./application/controllers/administrator/dashboard.php */