<?php

namespace App\Controllers\administrator;



class Backups extends Admin_Controller {


   /**
	 * Cpanel Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/administrator
	 *	- or -
	 * 		http://example.com/index.php/administrator/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/administrator/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
   
    public function __construct() {
        parent::__construct();
        
        $siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
        
    }

    public function index() {
    	
    	$this->data['page_title'] = $this->data['page_title'].' Administrator : Backups Manager';
    	
        $this->render('administrator/backups_view');
    }

    
    

}

/* End of file dashboard.php */
/* Location: ./application/controllers/administrator/dashboard.php */