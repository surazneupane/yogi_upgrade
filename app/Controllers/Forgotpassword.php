<?php

namespace App\Controllers;

class Forgotpassword extends BaseController
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		
		$siteConfiguration = siteConfiguration();
		
		if($siteConfiguration['site_caching']) {
       		$this->output->cache($siteConfiguration['site_caching_time']);
		}
		
		$this->data['current_user'] = $this->ion_auth->user()->row();
		$this->data['current_user_menu'] = '';
		
	}
 
    public function index()
    {
    	
    	$this->data['page_title'] = 'PSCMS Administrator - Reset your password.';
    	
    	$forgotpassword_slug = urlencode('هل-نسيت-كلمة-المرور');
    	$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = base_url().'/'.urldecode($forgotpassword_slug);
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['site_title'].' : اعد ضبط كلمه السر';
       	$this->data['fb_og_description'] = $this->data['site_description'];
       	$this->data['fb_og_published_time'] = '';
    	
    	$this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email','trim|valid_email|required');
        
        if($this->form_validation->run()===FALSE) {
            $this->load->helper('form');
            $this->render('forgotpassword/index_view');
        } else {
            $email = $this->input->post('email');            
 
            $this->load->library('ion_auth');
            if($this->ion_auth->forgotten_password($email)) {
                $_SESSION['auth_message'] = 'Please check you registered email. Forgot password link sent.';
                $this->session->mark_as_flash('auth_message');
                redirect('');
            } else {
                $_SESSION['auth_message'] = $this->ion_auth->errors();
                $this->session->mark_as_flash('auth_message');
                redirect('login');
            }
        }
    }
    
    
    public function reset_password($code = NULL)
    {
    
    	$code = $this->input->post('code') ? $this->input->post('code') : $code;
    	
    	if($this->ion_auth->forgotten_password_complete($code)) {
            $_SESSION['auth_message'] = 'Please check you registered email. Forgot password link sent.';
            $this->session->mark_as_flash('auth_message');
            redirect('');
        } else {
            $_SESSION['auth_message'] = $this->ion_auth->errors();
            $this->session->mark_as_flash('auth_message');
            redirect('forgotpassword/reset_password');
        }
    	
    	
    }
    
    
}
