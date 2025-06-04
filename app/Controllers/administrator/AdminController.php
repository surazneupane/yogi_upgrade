<?php
namespace App\Controllers\administrator;

use App\Controllers\CustomController;
use App\Libraries\IonAuth;
use App\Models\IonAuthModel;

class AdminController extends CustomController
{

    public $ionAuthLibrary;
    public $ionAuthModel;

    function __construct()
    {
        parent::__construct();

        $this->ionAuthLibrary = new IonAuth();
        $this->ionAuthModel = new IonAuthModel();

        // $this->load->library('ion_auth');

        $siteConfiguration = siteConfiguration();

        // if (!$this->ionAuthLibrary->logged_in()) {
        //     //redirect them to the login page
        //     return redirect('administrator/user/login', 'refresh');
        // }
        
        $this->data['current_user'] = $this->ionAuthModel->user()->getRow();
        $this->data['current_user_menu'] = '';
        if ($this->ionAuthLibrary->in_group('admin')) {
            //$this->data['current_user_menu'] = echo view('templates/_parts/user_menu_admin_view.php', NULL, TRUE);
        }
        $this->data['page_title'] = 'PSCMS Administrator - Dashboard';

        $this->data['siteConfiguration'] = $siteConfiguration;
    }


    protected function render($the_view = NULL, $template = 'admin_master')
    {
        parent::render($the_view, $template);
    }
}
