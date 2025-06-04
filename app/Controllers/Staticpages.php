<?php

namespace App\Controllers;

use App\Libraries\Breadcrumbs;
use App\Libraries\IonAuth;
use App\Models\IonAuthModel;
use CodeIgniter\Database\Config;
use Config\Services;

class Staticpages extends CustomController
{

	public $ionAuthLibrary;
	public $breadcrumLibrary;
	public $ionAuthModel;
	public $email;
	public $siteConfiguration;
	public $db;

	function __construct()
	{
		parent::__construct();
		// $this->load->library('ion_auth');
		// $this->load->library('Breadcrumbs');
		// $this->load->library('email');

		$this->ionAuthLibrary = new IonAuth();
		$this->breadcrumLibrary = new Breadcrumbs();
		$this->ionAuthModel = new IonAuthModel();
		$this->email = Services::email();
		$this->db = Config::connect();

		session()->keepFlashdata('message');

		$this->clang = session()->set("lang");

		$this->siteConfiguration = $siteConfiguration = siteConfiguration();

		$this->data['current_user'] = $this->ionAuthModel->user()->getRow();
		$this->data['current_user_menu'] = '';
		$this->data['site_title'] = $siteConfiguration['site_title'];
		$this->data['site_description'] = $siteConfiguration['site_description'];
	}



	// PRIVACY POLICY 
	public function privacy_policy()
	{

		if ($this->siteConfiguration['site_caching']) {
			Services::response()->setCache([
				'max-age' => $this->siteConfiguration['site_caching_time'] * 60,
			]);
		}

		$this->data['page_title'] = $this->data['page_title'] . ' : سياسة الخصوصيةّ';

		// BREADCRUMBS
		$this->breadcrumLibrary->add('الصفحة الرئيسية', base_url());
		$this->breadcrumLibrary->add('سياسة الخصوصيةّ', base_url('الفئة/سياسة-الخصوصيةّ/'));

		$login_slug = urlencode('سياسة-الخصوصيةّ');





		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
		} else {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
		}





		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url() . '/' . urldecode($login_slug);
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'] . ' : سياسة الخصوصيةّ';
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->data['breadcrumbs'] = $this->breadcrumLibrary->render();

		$this->render('staticpages/privacy_policy_view');
	}



	// TERMS AND CONDITIONS
	public function terms_conditions()
	{

		// $siteConfiguration = siteConfiguration();
		if ($this->siteConfiguration['site_caching']) {
			Services::response()->setCache([
				'max-age' => $this->siteConfiguration['site_caching_time'] * 60,
			]);
		}

		$this->data['page_title'] = $this->data['page_title'] . ' : الأحكام والشروط';

		// BREADCRUMBS
		$this->breadcrumLibrary->add('الصفحة الرئيسية', base_url());
		$this->breadcrumLibrary->add('الأحكام والشروط', base_url('الفئة/الأحكام-والشروط'));

		$login_slug = urlencode('الأحكام-والشروط');


		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
		} else {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
		}




		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url() . '/' . urldecode($login_slug);
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'] . ' : الأحكام والشروط';
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->data['breadcrumbs'] = $this->breadcrumLibrary->render();


		$this->render('staticpages/terms_conditions_view');
	}



	// CONTACT US
	public function contact()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' : Contact';

		// BREADCRUMBS
		$this->breadcrumLibrary->add('Home', base_url());
		$this->breadcrumLibrary->add('Contact', base_url('home/contact'));

		$contact_slug = urlencode('contact');





		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "";
			$this->data['billboard'] = "";
		} else {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
		}




		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url() . '/' . urldecode($contact_slug);
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'];
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->data['breadcrumbs'] = $this->breadcrumLibrary->render();

		if ($this->request->getMethod() == "POST") {
			// Set validation rules
			$rules = [
				'full_name' => [
					'label'  => 'Full name',
					'rules'  => 'required',
					'errors' => [
						'required' => 'The Full name is required.'
					]
				],
				'email_address' => [
					'label'  => 'Email Address',
					'rules'  => 'valid_email|required',
					'errors' => [
						'required'    => 'The Email Address is required.',
						'valid_email' => 'The Email Address is invalid.'
					]
				],
				'subject' => [
					'label'  => 'Subject',
					'rules'  => 'required',
					'errors' => [
						'required' => 'The Subject is required.'
					]
				],
				'message' => [
					'label'  => 'Message',
					'rules'  => 'required',
					'errors' => [
						'required' => 'The Message is required.'
					]
				],
				'g-recaptcha-response' => [
					'label'  => 'Captcha',
					'rules'  => 'required',
					'errors' => [
						'required' => 'The Captcha is required.'
					]
				]
			];
			if (!$this->validate($rules)) {
				session()->setFlashdata('validationErrors',$this->validator->getErrors());
				return redirect()->back()->withInput();
			}

			$siteConfiguration = siteConfiguration();
			$from_email = $siteConfiguration['from_email'];
			$site_title = $siteConfiguration['site_title'];

			$full_name = $this->request->getPost('full_name');
			$email_address = $this->request->getPost("email_address");
			$subject = $this->request->getPost('subject');
			$message = $this->request->getPost('message');

			$captcha = $this->request->getPost('g-recaptcha-response');

			if ($captcha != '') {

				$contactInfo = array();
				$contactInfo['full_name'] = $full_name;
				$contactInfo['email_address'] = $email_address;
				$contactInfo['subject'] = $subject;
				$contactInfo['message'] = $message;
				$contactInfo['created_date'] = date('Y-m-d H:i:s');

				$this->db->transBegin();
				$this->db->table('contacts')->insert($contactInfo);

				// $insert_id = $this->db->insert_id();

				$this->db->transCommit();

				// EMAIL SEND
				$subject = $site_title . ' - ' . $subject;
				$email_body  = '<!DOCTYPE html>';
				$email_body .= '<html>';
				$email_body .= '<head>';
				$email_body .= '<meta charset="utf-8" />';
				$email_body .= '<title>' . $subject . '</title>';
				$email_body .= '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
				$email_body .= '</head>';
				$email_body .= '<body>';
				$email_body .= '<p>Full Name : ' . $full_name . '</p>';
				$email_body .= '<p>Email Address : ' . $email_address . '</p>';
				$email_body .= '<p>Message : ' . $message . '</p>';
				$email_body .= '</body>';
				$email_body .= '</html>';

				$this->email->setMailType("html");
				$this->email->setTo($from_email);
				$this->email->setFrom($email_address);
				$this->email->setSubject($subject);
				$this->email->setMessage($email_body);
				$this->email->send();


				session()->setFlashdata('message_type', 'success');
				session()->setFlashdata('message', lang('InformationLang.CONTACT_THANKYOU_MESSAGE'));
			} else {

				session()->setFlashdata('message_type', 'danger');
				session()->setFlashdata('message', lang('The Captcha is required.'));
			}
			$contact_slug = urlencode('contact');
			return redirect($contact_slug, 'refresh');
		} else {
			$this->render('staticpages/contact_us_view');
		}
		// $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

		// $this->form_validation->set_rules('full_name', 'Full name', 'trim|required', array('required' => 'The Full name is required.'));
		// $this->form_validation->set_rules('email_address', 'Email Address', 'trim|valid_email|required', array('required' => 'The Email Address is required.', 'valid_email' => 'The Email Address is invalid.'));
		// $this->form_validation->set_rules('subject', 'Subject', 'trim|required', array('required' => 'The Subject is required.'));
		// $this->form_validation->set_rules('message', 'Message', 'trim|required', array('required' => 'The Message is required.'));
		// $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|required', array('required' => 'The Captcha is required.'));
		// var_dump($this->validate($rules));die;
		// if (!$this->validate($rules)) {
		// 	$this->data['validationErrors'] = $this->validator->getErrors();
		// } else {

		// 	$siteConfiguration = siteConfiguration();
		// 	$from_email = $siteConfiguration['from_email'];
		// 	$site_title = $siteConfiguration['site_title'];

		// 	$full_name = $this->request->getPost('full_name');
		// 	$email_address = $this->request->getPost("email_address");
		// 	$subject = $this->request->getPost('subject');
		// 	$message = $this->request->getPost('message');

		// 	$captcha = $this->request->getPost('g-recaptcha-response');

		// 	if ($captcha != '') {

		// 		$contactInfo = array();
		// 		$contactInfo['full_name'] = $full_name;
		// 		$contactInfo['email_address'] = $email_address;
		// 		$contactInfo['subject'] = $subject;
		// 		$contactInfo['message'] = $message;
		// 		$contactInfo['created_date'] = date('Y-m-d H:i:s');

		// 		$this->db->transBegin();
		// 		$this->db->table('contacts')->insert($contactInfo);

		// 		// $insert_id = $this->db->insert_id();

		// 		$this->db->transCommit();

		// 		// EMAIL SEND
		// 		$subject = $site_title . ' - ' . $subject;
		// 		$email_body  = '<!DOCTYPE html>';
		// 		$email_body .= '<html>';
		// 		$email_body .= '<head>';
		// 		$email_body .= '<meta charset="utf-8" />';
		// 		$email_body .= '<title>' . $subject . '</title>';
		// 		$email_body .= '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
		// 		$email_body .= '</head>';
		// 		$email_body .= '<body>';
		// 		$email_body .= '<p>Full Name : ' . $full_name . '</p>';
		// 		$email_body .= '<p>Email Address : ' . $email_address . '</p>';
		// 		$email_body .= '<p>Message : ' . $message . '</p>';
		// 		$email_body .= '</body>';
		// 		$email_body .= '</html>';

		// 		$this->email->setMailType("html");
		// 		$this->email->setTo($from_email);
		// 		$this->email->setFrom($email_address);
		// 		$this->email->setSubject($subject);
		// 		$this->email->setMessage($email_body);
		// 		$this->email->send();


		// 		session()->setFlashdata('message_type', 'success');
		// 		session()->setFlashdata('message', lang('InformationLang.CONTACT_THANKYOU_MESSAGE'));
		// 	} else {

		// 		session()->setFlashdata('message_type', 'danger');
		// 		session()->setFlashdata('message', lang('The Captcha is required.'));
		// 	}
		// 	$contact_slug = urlencode('contact');
		// 	redirect($contact_slug, 'refresh');
		// }
	}


	// SCHEDULE
	public function schedule()
	{

		// $siteConfiguration = siteConfiguration();
		if ($this->siteConfiguration['site_caching']) {
			Services::response()->setCache([
				'max-age' => $this->siteConfiguration['site_caching_time'] * 60,
			]);
		}

		$this->data['page_title'] = $this->data['page_title'] . ' : Schedule';

		// BREADCRUMBS
		$this->breadcrumLibrary->add('Schedule', base_url());
		$this->breadcrumLibrary->add('Schedule', base_url('home/schedule'));

		$schedule_slug = urlencode('schedule');


		/*//$qry = 'SELECT * FROM `schedules` WHERE MONTH(schedule_date) = MONTH(CURRENT_DATE()) AND YEAR(schedule_date) = YEAR(CURRENT_DATE()) AND `status` = 1'; // select data from db
		//$qry = 'SELECT * FROM `schedules` WHERE `status` = 1'; // select data from db
		//$schedules = $this->db->query($qry)->result_array();
		
		//$first_day_this_month = date('Y-m-01 00:00:00'); // hard-coded '01' for first day
		$first_day_this_month = date("Y-m-d 00:00:00", strtotime('monday this week -1 day')); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t 12:59:59', strtotime("+1 months"));
		$last_day_this_month = date('Y-m-d 12:59:59', strtotime('next saturday', strtotime($last_day_this_month)));
		
		$start = new DateTime($first_day_this_month);		
	    $end = new DateTime($last_day_this_month);	    
	    $interval = new DateInterval('P1D');
	    $dateRange = new DatePeriod($start, $interval, $end);
	
	    $weekNumber = 1;
	    $weeks = array();
	    foreach ($dateRange as $date) {
	        $weeks[$weekNumber][] = $date->format('Y-m-d');
	        if ($date->format('w') == 6) {
	            $weekNumber++;
	        }
	    }
		
	    $schedules = array();
	    $i = 0;
	    foreach ($weeks AS $week) {
	    	foreach ($week AS $w) {
	    		
	    		//if(in_array($w, array_column($schedules, 'schedule_date'))) { // search value in the array
	    		//	$arraged_array[$i][$w] = '';
	    		//} else {
	    		//	$arraged_array[$i][$w] = '';
	    		//}
	    		$qry = 'SELECT * FROM `schedules` WHERE `status` = 1 AND `schedule_date` = "'.$w.'"'; // select data from db
				$schedulesA = $this->db->query($qry)->result_array();
				if(!empty($schedulesA)) { 
	    			$schedules[$i][$w] = $schedulesA[0];
				} else {
					$schedules[$i][$w] = '';
				}
	    		
	    	}
	    $i++;
	    }*/

		if ($this->clang == 'fr') {
			$qry = 'SELECT schedule_data_fr AS schedule_data, pricing_data_fr AS pricing_data FROM `schedules`'; // select data from db
		} else {
			$qry = 'SELECT schedule_data, pricing_data FROM `schedules`'; // select data from db
		}
		$schedules = $this->db->query($qry)->getResultArray();


		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
		} else {

			$this->data['before_head'] = "";

			$this->data['billboard'] = "";
		}




		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url() . '/' . urldecode($schedule_slug);
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'] . ' : Schedule';
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->data['schedules'] = $schedules;
		$this->data['breadcrumbs'] = $this->breadcrumLibrary->render();

		$this->render('staticpages/schedule_view');
	}
}
