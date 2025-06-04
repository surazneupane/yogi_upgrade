<?php

namespace App\Libraries;

use App\Models\IonAuthModel;
use CodeIgniter\CodeIgniter;
use Config\Services;
use Exception;

class IonAuth
{
	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * extra where
	 *
	 * @var array
	 **/
	public $_extra_where = array();

	/**
	 * extra set
	 *
	 * @var array
	 **/
	public $_extra_set = array();

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group;

	public $email;
	public $ionAuthModel;
	/**
	 * __construct
	 *
	 * @author Ben
	 */
	public function __construct()
	{
		// $this->config->load('ion_auth', TRUE);
		// $this->load->library(array('email'));
		$this->email = Services::email();
		// $this->lang->load('ion_auth');
		// $this->load->helper(array('cookie', 'language', 'url'));

		// $this->load->library('session');

		$this->ionAuthModel = new IonAuthModel();
		// $this->load->model('ion_auth_model');

		$this->_cache_user_in_group = &$this->ionAuthModel->_cache_user_in_group;

		$email_config = config('IonAuth')->email_config;

		if (config('IonAuth')->use_ci_email && isset($email_config) && is_array($email_config)) {
			$this->email->initialize($email_config);
		}

		$this->ionAuthModel->trigger_events('library_constructor');
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 * @param $method
	 * @param $arguments
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($method, $arguments)
	{
		if (!method_exists($this->ionAuthModel, $method)) {
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}
		if ($method == 'create_user') {
			return call_user_func_array(array($this, 'register'), $arguments);
		}
		if ($method == 'update_user') {
			return call_user_func_array(array($this, 'update'), $arguments);
		}
		return call_user_func_array(array($this->ionAuthModel, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return $this->$var;
	}


	/**
	 * forgotten password feature
	 *
	 * @param $identity
	 * @return mixed boolean / array
	 * @author Mathew
	 */
	public function forgotten_password($identity)    //changed $email to $identity
	{
		if ($this->ionAuthModel->forgotten_password($identity))   //changed
		{
			// Get user information
			$identifier = $this->ionAuthModel->identity_column; // use model identity column, so it can be overridden in a controller
			$user = $this->where($identifier, $identity)->where('active', 1)->users()->row();  // changed to get_user_by_identity from email

			if ($user) {
				$data = array(
					'identity'		=> $user->{config('IonAuth')->identity},
					'forgotten_password_code' => $user->forgotten_password_code
				);

				if (!config('IonAuth')->use_ci_email) {
					$this->set_message('forgot_password_successful');
					return $data;
				} else {
					$message = $this->load->view(config('IonAuth')->email_templates . config('IonAuth')->email_forgot_password, $data, true);

					$this->email->clear();
					$this->email->setFrom(config('IonAuth')->admin_email, config('IonAuth')->site_title);
					$this->email->setTo($user->email);
					$this->email->setSubject(config('IonAuth')->site_title . ' - ' . $this->lang->line('email_forgotten_password_subject'));
					$this->email->setSubject($message);

					if ($this->email->send()) {
						$this->set_message('forgot_password_successful');
						return TRUE;
					} else {
						$this->set_error('forgot_password_unsuccessful');
						return FALSE;
					}
				}
			} else {
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		} else {
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * forgotten_password_complete
	 *
	 * @param $code
	 * @author Mathew
	 * @return bool
	 */
	public function forgotten_password_complete($code)
	{
		$this->ionAuthModel->trigger_events('pre_password_change');

		$identity = config('IonAuth')->identity;
		$profile  = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!$profile) {
			$this->ionAuthModel->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$new_password = $this->ionAuthModel->forgotten_password_complete($code, $profile->salt);

		if ($new_password) {
			$data = array(
				'identity'     => $profile->{$identity},
				'new_password' => $new_password
			);
			if (!config('IonAuth')->use_ci_email) {
				$this->set_message('password_change_successful');
				$this->ionAuthModel->trigger_events(array('post_password_change', 'password_change_successful'));
				return $data;
			} else {
				$message = $this->load->view(config('IonAuth')->email_templates . config('IonAuth')->email_forgot_password_complete, $data, true);

				$this->email->clear();
				$this->email->setFrom(config('IonAuth')->admin_email, config('IonAuth')->site_title);
				$this->email->setTo($profile->email);
				$this->email->setSubject(config('IonAuth')->site_title . ' - ' . $this->lang->line('email_new_password_subject'));
				$this->email->setSubject($message);

				if ($this->email->send()) {
					$this->set_message('password_change_successful');
					$this->ionAuthModel->trigger_events(array('post_password_change', 'password_change_successful'));
					return TRUE;
				} else {
					$this->set_error('password_change_unsuccessful');
					$this->ionAuthModel->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
					return FALSE;
				}
			}
		}

		$this->ionAuthModel->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
		return FALSE;
	}

	/**
	 * forgotten_password_check
	 *
	 * @param $code
	 * @author Michael
	 * @return bool
	 */
	public function forgotten_password_check($code)
	{
		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!is_object($profile)) {
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		} else {
			if (config('IonAuth')->forgot_password_expiration > 0) {
				//Make sure it isn't expired
				$expiration = config('IonAuth')->forgot_password_expiration;
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->clear_forgotten_password_code($code);
					$this->set_error('password_change_unsuccessful');
					return FALSE;
				}
			}
			return $profile;
		}
	}

	/**
	 * register
	 *
	 * @param $identity
	 * @param $password
	 * @param $email
	 * @param array $additional_data
	 * @param array $group_ids
	 * @author Mathew
	 * @return bool
	 */
	public function register($identity, $password, $email, $additional_data = array(), $group_ids = array()) //need to test email activation
	{
		$this->ionAuthModel->trigger_events('pre_account_creation');

		$email_activation = config('IonAuth')->email_activation;

		$id = $this->ionAuthModel->register($identity, $password, $email, $additional_data, $group_ids);

		if (!$email_activation) {
			if ($id !== FALSE) {
				$this->set_message('account_creation_successful');
				$this->ionAuthModel->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
				return $id;
			} else {
				$this->set_error('account_creation_unsuccessful');
				$this->ionAuthModel->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}
		} else {
			if (!$id) {
				$this->set_error('account_creation_unsuccessful');
				return FALSE;
			}

			// deactivate so the user much follow the activation flow
			$deactivate = $this->ionAuthModel->deactivate($id);

			// the deactivate method call adds a message, here we need to clear that
			$this->ionAuthModel->clear_messages();


			if (!$deactivate) {
				$this->set_error('deactivate_unsuccessful');
				$this->ionAuthModel->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}

			$activation_code = $this->ionAuthModel->activation_code;
			$identity        = config('IonAuth')->identity;
			$user            = $this->ionAuthModel->user($id)->row();

			$data = array(
				'identity'   => $user->{$identity},
				'id'         => $user->id,
				'email'      => $email,
				'activation' => $activation_code,
			);
			if (!config('IonAuth')->use_ci_email) {
				$this->ionAuthModel->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
				$this->set_message('activation_email_successful');
				return $data;
			} else {
				$message = $this->load->view(config('IonAuth')->email_templates . config('IonAuth')->email_activate, $data, true);

				$this->email->clear();
				$this->email->setFrom(config('IonAuth')->admin_email, config('IonAuth')->site_title);
				$this->email->setTo($email);
				$this->email->setSubject(config('IonAuth')->site_title . ' - ' . $this->lang->line('email_activation_subject'));
				$this->email->setSubject($message);

				if ($this->email->send() == TRUE) {
					$this->ionAuthModel->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
					$this->set_message('activation_email_successful');
					return $id;
				}
			}

			$this->ionAuthModel->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
			$this->set_error('activation_email_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
		$this->ionAuthModel->trigger_events('logout');

		$identity = config('IonAuth')->identity;

		if (CodeIgniter::CI_VERSION == '2') {
			session()->remove(array($identity => '', 'id' => '', 'user_id' => ''));
		} else {
			session()->remove(array($identity, 'id', 'user_id'));
		}

		// delete the remember me cookies if they exist
		if (service('request')->getCookie(config('IonAuth')->identity_cookie_name)) {
			Services::response()->deleteCookie(config('IonAuth')->identity_cookie_name);
		}
		if (service('request')->getCookie(config('IonAuth')->remember_cookie_name)) {
			Services::response()->deleteCookie(config('IonAuth')->remember_cookie_name);
		}

		// Destroy the session
		session()->destroy();
		session()->start();
		session()->regenerate(true);
		// $this->ionAuthModel->set_message('logout_successful');
		return true;
		//Recreate the session
		// if (CodeIgniter::CI_VERSION == '2') {
		// 	$this->session->sess_create();
		// } else {
		// 	if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
		// 		session_start();
		// 	}
		// 	$this->session->sess_regenerate(TRUE);
		// }

		// $this->set_message('logout_successful');
		// return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$this->ionAuthModel->trigger_events('logged_in');

		$recheck = $this->ionAuthModel->recheck_session();

		//auto-login the user if they are remembered
		if (! $recheck && service('request')->getCookie(config('IonAuth')->identity_cookie_name) && service('request')->getCookie(config('IonAuth')->remember_cookie_name)) {
			$recheck = $this->ionAuthModel->login_remembered_user();
		}

		return $recheck;
	}

	/**
	 * logged_in
	 *
	 * @return integer
	 * @author jrmadsen67
	 **/
	public function get_user_id()
	{
		$user_id = session()->get('user_id');
		if (!empty($user_id)) {
			return $user_id;
		}
		return null;
	}


	/**
	 * is_admin
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function is_admin($id = false)
	{
		$this->ionAuthModel->trigger_events('is_admin');

		$admin_group = config('IonAuth')->admin_group;

		return $this->in_group($admin_group, $id);
	}

	/**
	 * in_group
	 *
	 * @param mixed group(s) to check
	 * @param bool user id
	 * @param bool check if all groups is present, or any of the groups
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function in_group($check_group, $id = false, $check_all = false)
	{
		$this->ionAuthModel->trigger_events('in_group');

		$id || $id = session()->get('user_id');

		if (!is_array($check_group)) {
			$check_group = array($check_group);
		}

		if (isset($this->_cache_user_in_group[$id])) {
			$groups_array = $this->_cache_user_in_group[$id];
		} else {
			$users_groups = $this->ionAuthModel->get_users_groups($id)->getResult();
			$groups_array = array();
			foreach ($users_groups as $group) {
				$groups_array[$group->id] = $group->name;
			}
			$this->_cache_user_in_group[$id] = $groups_array;
		}
		foreach ($check_group as $key => $value) {
			$groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

			/**
			 * if !all (default), in_array
			 * if all, !in_array
			 */
			if (in_array($value, $groups) xor $check_all) {
				/**
				 * if !all (default), true
				 * if all, false
				 */
				return !$check_all;
			}
		}

		/**
		 * if !all (default), false
		 * if all, true
		 */
		return $check_all;
	}


	/**
	 * in_access
	 * 
	 * @param mixed group(s) to check
	 * @param bool user id
	 * @param bool check if all groups is present, or any of the groups
	 *
	 * @return bool
	 * @author Panchsoft Technologies
	 **/
	public function in_access($check_section = NULL, $check_task = NULL)
	{

		$current_user_groups = $this->ionAuthModel->get_users_groups()->getResult();
		$access_data = (array) json_decode($current_user_groups[0]->$check_section ?? '{}');
		if (array_key_exists($check_task, $access_data)) {
			return true;
		} else {
			return false;
		}
	}
}
