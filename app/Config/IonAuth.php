<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class IonAuth extends BaseConfig
{
    public $tables = [
        'users'          => 'users',
        'groups'         => 'groups',
        'users_groups'   => 'users_groups',
        'login_attempts' => 'login_attempts',
    ];

    public $join = [
        'users'  => 'user_id',
        'groups' => 'group_id',
    ];

    public $hash_method    = 'bcrypt';
    public $default_rounds = 8;
    public $random_rounds  = false;
    public $min_rounds     = 5;
    public $max_rounds     = 9;
    public $salt_prefix    = '$2y$';

    public $site_title      = "PSCMS";
    public $admin_email     = "gopi@panchsoft.com";
    public $default_group   = 'registered';
    public $admin_group     = 'admin';
    public $identity        = 'email';
    public $min_password_length = 8;
    public $max_password_length = 20;
    public $email_activation    = false;
    public $manual_activation   = false;
    public $remember_users      = true;
    public $user_expire         = 86500;
    public $user_extend_on_login = false;

    public $track_login_attempts       = true;
    public $track_login_ip_address     = true;
    public $maximum_login_attempts     = 3;
    public $lockout_time               = 600;
    public $forgot_password_expiration = 60;
    public $recheck_timer              = 0;

    public $remember_cookie_name = 'remember_code';
    public $identity_cookie_name = 'identity';

    public $use_ci_email = true;
    public $email_config = [
        'mailtype' => 'html',
    ];

    public $email_templates = 'emails/';
    public $email_activate = 'activate.tpl.php';
    public $email_forgot_password = 'forgot_password.tpl.php';
    public $email_forgot_password_complete = 'new_password.tpl.php';

    public $salt_length = 22;
    public $store_salt  = false;

    public $delimiters_source       = 'config';
    public $message_start_delimiter = '<p>';
    public $message_end_delimiter   = '</p>';
    public $error_start_delimiter   = '<p>';
    public $error_end_delimiter     = '</p>';

    public $permitted_uri_chars = 'a-z 0-9~%.:_\-';
}
