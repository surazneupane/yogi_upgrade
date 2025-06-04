<?php
namespace App\Controllers;



class User extends BaseController
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		
		$siteConfiguration = siteConfiguration();
		
		$this->session->keep_flashdata('message');
		
		/*if($siteConfiguration['site_caching']) {
       		$this->output->cache($siteConfiguration['site_caching_time']);
		}*/
		
		$this->data['current_user'] = $this->ion_auth->user()->row();
		$this->data['current_user_menu'] = '';
		$this->data['site_title'] = $siteConfiguration['site_title'];
		$this->data['site_description'] = $siteConfiguration['site_description'];
	}
 
	
	// REGISTER 
    public function register()
    {
    	if($this->ion_auth->logged_in()) {
			$profile_slug = urlencode('الملف-الشخصي');
			redirect($profile_slug, 'refresh');
		}    	
    	$this->data['page_title'] = $this->data['site_title'].' : تسجيل';
    	
    	$register_slug = urlencode('تسجيل');
    	$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = base_url().'/'.urldecode($register_slug);
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['site_title'].' : تسجيل';
       	$this->data['fb_og_description'] = $this->data['site_description'];
       	$this->data['fb_og_published_time'] = '';
    	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        $this->form_validation->set_rules('first_name', 'First name','trim|required');
        $this->form_validation->set_rules('last_name', 'Last name','trim|required');
        $this->form_validation->set_rules('username','Username','trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('email','Email','trim|valid_email|required');
        $this->form_validation->set_rules('password','Password','trim|min_length[8]|max_length[20]|required');
        $this->form_validation->set_rules('confirm_password','Confirm password','trim|matches[password]|required');
        $this->form_validation->set_rules('phone','Phone','trim|required');
 
        if($this->form_validation->run() === FALSE) {
        	
            $this->load->helper('form');
            $this->render('user/register_view');
            
        } else {
        	
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
 
            $additional_data = array (
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone
            );
 
            $this->load->library('ion_auth');
            if($this->ion_auth->register($username,$password,$email,$additional_data)) {
            	
                //$_SESSION['auth_message'] = 'The account has been created. You may now login.';
                //$this->session->mark_as_flash('auth_message');
                //redirect('administrator/user/login');
            	$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', 'The account has been created. You may now login.');
                $login_slug = urlencode('تسجيل-الدخول');
                redirect($login_slug, 'refresh');
                
            } else {
            	
                //$_SESSION['auth_message'] = $this->ion_auth->errors();
                //$this->session->mark_as_flash('auth_message');
                //redirect('register');
                $this->session->set_flashdata('message_type', 'danger');
				$this->session->set_flashdata('message', $this->ion_auth->errors());
                $register_slug = urlencode('تسجيل');
                redirect($register_slug, 'refresh');
                
            }
            
        }
    }
    
    
    // LOGIN 
	public function login()
	{
		
		if($this->ion_auth->logged_in()) {	
			$profile_slug = urlencode('الملف-الشخصي');
			redirect($profile_slug, 'refresh');
		}
		$this->data['page_title'] = $this->data['page_title'].' : تسجيل الدخول';
		
		$login_slug = urlencode('تسجيل-الدخول');
    	$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = base_url().'/'.urldecode($login_slug);
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['page_title'].' : تسجيل الدخول';
       	$this->data['fb_og_description'] = $this->data['site_description'];
       	$this->data['fb_og_published_time'] = '';
		
		if($this->input->post())
		{
			//here we will verify the inputs;
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			$this->form_validation->set_rules('login_identity', 'Email', 'required');
			$this->form_validation->set_rules('login_password', 'Password', 'required');
			$this->form_validation->set_rules('remember','Remember me','integer');
			if($this->form_validation->run() === TRUE)
			{
				$remember = (bool) $this->input->post('remember');
				if ($this->ion_auth->login($this->input->post('login_identity'), $this->input->post('login_password'), $remember))
				{
					$profile_slug = urlencode('الملف-الشخصي');
					redirect($profile_slug, 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message_type', 'danger');
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					$login_slug = urlencode('تسجيل-الدخول');
					redirect($login_slug, 'refresh');
				}
			}
		}
		$this->load->helper('form');
		$this->render('user/register_view');
		
	}
	
	
	// PROFILE 
	public function profile()
	{
		if(!$this->ion_auth->logged_in()) {	
			$login_slug = urlencode('تسجيل-الدخول');
            redirect($login_slug, 'refresh');
		}
		
		$this->data['page_title'] = $this->data['page_title'].': الملف الشخصي';
		$user = $this->ion_auth->user()->row();
		$this->data['user'] = $user;
		$this->data['current_user_menu'] = '';
		
		
		$profile_slug = urlencode('الملف-الشخصي');
    	$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = base_url().'/'.($profile_slug);
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['page_title'].': الملف الشخصي';
       	$this->data['fb_og_description'] = $this->data['site_description'];
       	$this->data['fb_og_published_time'] = '';
		
				
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		$this->form_validation->set_rules('first_name','First name','trim');
		$this->form_validation->set_rules('last_name','Last name','trim');
		$this->form_validation->set_rules('phone','Phone','trim');
		
		if($this->form_validation->run()===FALSE) {
			$this->render('user/profile_view');
		} else {
			$new_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone' => $this->input->post('phone')
			);			
			if(strlen($this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
			$this->ion_auth->update($user->id, $new_data);
			
			$this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect('user/profile','refresh');
		}
	}
	
	
	// LOGOUT 
	public function logout()
	{
		
		$this->ion_auth->logout();
  		redirect('/', 'refresh');
		
	}
	
	
	// FORGOT PASSWORD
	public function forgotpassword()
    {
    	
    	$this->data['page_title'] = $this->data['site_title'].' : اعد ضبط كلمه السر';
    	
    	$forgotpassword_slug = urlencode('هل-نسيت-كلمة-المرور');
    	$this->data['fb_og_type'] = 'Website';
       	$this->data['fb_og_url'] = base_url().'/'.urldecode($forgotpassword_slug);
       	$this->data['fb_og_image'] = base_url().'assets/site/images/yawmiyati-default.jpg';
       	$this->data['fb_og_title'] = $this->data['site_title'].' : اعد ضبط كلمه السر';
       	$this->data['fb_og_description'] = $this->data['site_description'];
       	$this->data['fb_og_published_time'] = '';
    	
    	$this->load->library('form_validation');
    	$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        $this->form_validation->set_rules('email','Email','trim|valid_email|required');
        
        if($this->form_validation->run()===FALSE) {
            $this->load->helper('form');
            $this->render('user/forgotpassword_view');
        } else {
            $email = $this->input->post('email');            
 
            $this->load->library('ion_auth');
            if($this->ion_auth->forgotten_password($email)) {
                // $_SESSION['auth_message'] = 'Please check you registered email. Forgot password link sent.';
                // $this->session->mark_as_flash('auth_message');
                $this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', 'Please check you registered email. Forgot password link sent.');
                redirect('');
                
            } else {
            	
                //$_SESSION['auth_message'] = $this->ion_auth->errors();
                //$this->session->mark_as_flash('auth_message');                
                $this->session->set_flashdata('message_type', 'danger');
				$this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('تسجيل-الدخول');
                
            }
        }
    }
    
    
    // RESET PASSWORD
    public function reset_password($code = NULL)
    {
    
    	$code = $this->input->post('code') ? $this->input->post('code') : $code;
    	
    	if($this->ion_auth->forgotten_password_complete($code)) {
            //$_SESSION['auth_message'] = 'Please check you registered email. Forgot password link sent.';
            //$this->session->mark_as_flash('auth_message');
            $this->session->set_flashdata('message_type', 'success');
			$this->session->set_flashdata('message', 'Please check you registered email. Forgot password link sent.');
            redirect('');
        } else {
            //$_SESSION['auth_message'] = $this->ion_auth->errors();
            //$this->session->mark_as_flash('auth_message');
            $this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect('user/reset_password');
        }
    	
    	
    }
    
    
    /******************/
    /* FACEBOOK LOGIN */
    /******************/
    /**
	 * Callback to register with facebook account
	 */
	public function fb_register_callback()
	{
	    if ($this->facebook->fb_callback()) {
	    	
	        $register_slug = urlencode('تسجيل');
	        redirect($register_slug);
	        
	    } else {
	        
	    	$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', $_REQUEST['error_code'].' - '.$_REQUEST['error_message']);
	        $register_slug = urlencode('تسجيل');
	        redirect($register_slug);
	    	
	    }
	}
	
    /**
	 * Callback to log with facebook account
	 */
	public function fb_login_callback()
	{
	    if ($this->facebook->fb_callback()) {
	    	
	    	$login_slug = urlencode('تسجيل-الدخول');
	        redirect($login_slug);
	        
	    } else {
	    	
	    	$this->session->set_flashdata('message_type', 'danger');
			$this->session->set_flashdata('message', $_REQUEST['error_code'].' - '.$_REQUEST['error_message']);
	        $login_slug = urlencode('تسجيل-الدخول');
	        redirect($login_slug);
	    }
	}
	
	/**
	 * Callback to disconnect the facebook acount
	 */
	public function disconnectFacebookAccount_callback()
	{
	    $this->facebook->disconnectFacebookAccount();
	    $register_slug = urlencode('تسجيل');
	    redirect($register_slug);
	}
	
	/**
	 * Callback to connect the user's facebook acount
	 */
	public function connectFacebookAccount_callback()
	{
	    $this->facebook->connectFacebookAccount();
	    $register_slug = urlencode('تسجيل');
	    redirect($register_slug);
	}
	
	// log the user out ion-auth
	public function FBlogout()
	{
	    $this->data['title'] = 'Logout';
	    
	    // log the user out
	    $logout = $this->ion_auth->logout();
	    
	    // redirect them to the login page
	    $this->session->set_flashdata('message', $this->ion_auth->messages());
	    $login_slug = urlencode('تسجيل-الدخول');
	    redirect($login_slug);
	}
    
    
}
