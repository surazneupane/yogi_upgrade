<?php

namespace App\Controllers\administrator;



class Fileupload extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
		{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to visit the Categories page');
			redirect('administrator','refresh');
		}
    }

    function index()
    {
        error_reporting(E_ALL | E_STRICT);
        $this->load->library("UploadHandler");
    }
}