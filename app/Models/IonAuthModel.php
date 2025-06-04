<?php

namespace App\Models;

use App\Libraries\Bcrypt;
use CodeIgniter\Database\Config;
use CodeIgniter\Model;
use Config\Services;
use ReflectionClass;
use stdClass;

class IonAuthModel extends Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	public $tables = array();

	/**
	 * activation code
	 *
	 * @var string
	 **/
	public $activation_code;

	/**
	 * forgotten password key
	 *
	 * @var string
	 **/
	public $forgotten_password_code;

	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;

	/**
	 * Where
	 *
	 * @var array
	 **/
	public $_ion_where = array();

	/**
	 * Select
	 *
	 * @var array
	 **/
	public $_ion_select = array();

	/**
	 * Like
	 *
	 * @var array
	 **/
	public $_ion_like = array();

	/**
	 * Limit
	 *
	 * @var string
	 **/
	public $_ion_limit = NULL;

	/**
	 * Offset
	 *
	 * @var string
	 **/
	public $_ion_offset = NULL;

	/**
	 * Order By
	 *
	 * @var string
	 **/
	public $_ion_order_by = NULL;

	/**
	 * Order
	 *
	 * @var string
	 **/
	public $_ion_order = NULL;

	/**
	 * Hooks
	 *
	 * @var object
	 **/
	protected $_ion_hooks;

	/**
	 * Response
	 *
	 * @var string
	 **/
	protected $response = NULL;

	/**
	 * message (uses lang file)
	 *
	 * @var string
	 **/
	protected $messages;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 **/
	protected $errors;

	/**
	 * error start delimiter
	 *
	 * @var string
	 **/
	protected $error_start_delimiter;

	/**
	 * error end delimiter
	 *
	 * @var string
	 **/
	protected $error_end_delimiter;

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group = array();

	public $identity_column;
	public $store_salt;
	public $salt_length;
	public $join;
	public $hash_method;
	public $default_rounds;
	public $random_rounds;
	public $min_rounds;
	public $max_rounds;
	public $message_end_delimiter;
	public $message_start_delimiter;

	/**
	 * caching of groups
	 *
	 * @var array
	 **/
	protected $_cache_groups = array();

	public $db;
	public $bcryptLibrary;

	protected $protectFields = false;

	public function __construct()
	{
		parent::__construct();
		$this->db = Config::connect();

		// initialize db tables data
		$this->tables  = config('IonAuth')->tables;

		//initialize data
		$this->identity_column = config('IonAuth')->identity;
		$this->store_salt      = config('IonAuth')->store_salt;
		$this->salt_length     = config('IonAuth')->salt_length;
		$this->join			   = config('IonAuth')->join;


		// initialize hash method options (Bcrypt)
		$this->hash_method = config('IonAuth')->hash_method;
		$this->default_rounds = config('IonAuth')->default_rounds;
		$this->random_rounds = config('IonAuth')->random_rounds;
		$this->min_rounds = config('IonAuth')->min_rounds;
		$this->max_rounds = config('IonAuth')->max_rounds;

		// initialize messages and error
		$this->messages    = array();
		$this->errors      = array();
		$delimiters_source = config('IonAuth')->delimiters_source;

		// load the error delimeters either from the config file or use what's been supplied to form validation
		if ($delimiters_source === 'form_validation') {
			// load in delimiters from form_validation
			// to keep this simple we'll load the value using reflection since these properties are protected
			$this->load->library('form_validation');
			$form_validation_class = new ReflectionClass("CI_Form_validation");

			$error_prefix = $form_validation_class->getProperty("_error_prefix");
			$error_prefix->setAccessible(TRUE);
			$this->error_start_delimiter = $error_prefix->getValue($this->form_validation);
			$this->message_start_delimiter = $this->error_start_delimiter;

			$error_suffix = $form_validation_class->getProperty("_error_suffix");
			$error_suffix->setAccessible(TRUE);
			$this->error_end_delimiter = $error_suffix->getValue($this->form_validation);
			$this->message_end_delimiter = $this->error_end_delimiter;
		} else {
			// use delimiters from config
			$this->message_start_delimiter = config('IonAuth')->message_start_delimiter;
			$this->message_end_delimiter   = config('IonAuth')->message_end_delimiter;
			$this->error_start_delimiter   = config('IonAuth')->error_start_delimiter;
			$this->error_end_delimiter     = config('IonAuth')->error_end_delimiter;
		}


		// initialize our hooks object
		$this->_ion_hooks = new stdClass;

		// load the bcrypt class if needed
		if ($this->hash_method == 'bcrypt') {
			if ($this->random_rounds) {
				$rand = rand($this->min_rounds, $this->max_rounds);
				$params = array('rounds' => $rand);
			} else {
				$params = array('rounds' => $this->default_rounds);
			}

			$params['salt_prefix'] =  config('IonAuth')->salt_prefix;
			$this->bcryptLibrary = new Bcrypt($params);
		}

		$this->trigger_events('model_constructor');
	}

	/**
	 * Misc functions
	 *
	 * Hash password : Hashes the password to be stored in the database.
	 * Hash password db : This function takes a password and validates it
	 * against an entry in the users table.
	 * Salt : Generates a random salt value.
	 *
	 * @author Mathew
	 */

	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password($password, $salt = false, $use_sha1_override = FALSE)
	{
		if (empty($password)) {
			return FALSE;
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			return $this->bcryptLibrary->hash($password);
		}


		if ($this->store_salt && $salt) {
			return  sha1($password . $salt);
		} else {
			$salt = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}

	/**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password_db($id, $password, $use_sha1_override = FALSE)
	{
		if (empty($id) || empty($password)) {
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->table($this->tables['users'])->select('password, salt')
			->where('id', $id)
			->limit(1)
			->orderBy('id', 'desc')
			->get();

		$hash_password_db = $query->getRow();

		if ($query->getNumRows() !== 1) {
			return FALSE;
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt') {
			if ($this->bcryptLibrary->verify($password, $hash_password_db->password)) {
				return TRUE;
			}

			return FALSE;
		}

		// sha1
		if ($this->store_salt) {
			$db_password = sha1($password . $hash_password_db->salt);
		} else {
			$salt = substr($hash_password_db->password, 0, $this->salt_length);

			$db_password =  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}

		if ($db_password == $hash_password_db->password) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}

	/**
	 * Generates a random salt value.
	 *
	 * Salt generation code taken from https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
	 *
	 * @return void
	 * @author Anthony Ferrera
	 **/
	public function salt()
	{

		$raw_salt_len = 16;

		$buffer = '';
		$buffer_valid = false;

		if (function_exists('random_bytes')) {
			$buffer = random_bytes($raw_salt_len);
			if ($buffer) {
				$buffer_valid = true;
			}
		}

		if (!$buffer_valid && function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
			$buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
			if ($buffer) {
				$buffer_valid = true;
			}
		}

		if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
			$buffer = openssl_random_pseudo_bytes($raw_salt_len);
			if ($buffer) {
				$buffer_valid = true;
			}
		}

		if (!$buffer_valid && @is_readable('/dev/urandom')) {
			$f = fopen('/dev/urandom', 'r');
			$read = strlen($buffer);
			while ($read < $raw_salt_len) {
				$buffer .= fread($f, $raw_salt_len - $read);
				$read = strlen($buffer);
			}
			fclose($f);
			if ($read >= $raw_salt_len) {
				$buffer_valid = true;
			}
		}

		if (!$buffer_valid || strlen($buffer) < $raw_salt_len) {
			$bl = strlen($buffer);
			for ($i = 0; $i < $raw_salt_len; $i++) {
				if ($i < $bl) {
					$buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
				} else {
					$buffer .= chr(mt_rand(0, 255));
				}
			}
		}

		$salt = $buffer;

		// encode string with the Base64 variant used by crypt
		$base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
		$bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$base64_string   = base64_encode($salt);
		$salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);

		$salt = substr($salt, 0, $this->salt_length);


		return $salt;
	}

	/**
	 * Activation functions
	 *
	 * Activate : Validates and removes activation code.
	 * Deactivate : Updates a users row with an activation code.
	 *
	 * @author Mathew
	 */

	/**
	 * activate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function activate($id, $code = false)
	{
		$this->trigger_events('pre_activate');

		if ($code !== FALSE) {
			$query = $this->db->table($this->tables['users'])->select($this->identity_column)
				->where('activation_code', $code)
				->where('id', $id)
				->limit(1)
				->orderBy('id', 'desc')
				->get();

			$result = $query->getRow();

			if ($query->getNumRows() !== 1) {
				$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
				$this->set_error('activate_unsuccessful');
				return FALSE;
			}

			$data = array(
				'activation_code' => NULL,
				'active'          => 1
			);

			$this->trigger_events('extra_where');
			$this->db->table($this->tables['users'])->update($data, array('id' => $id));
		} else {
			$data = array(
				'activation_code' => NULL,
				'active'          => 1
			);


			$this->trigger_events('extra_where');
			$this->db->table($this->tables['users'])->update($data, array('id' => $id));
		}


		$return = $this->db->affectedRows() == 1;
		if ($return) {
			$this->trigger_events(array('post_activate', 'post_activate_successful'));
			$this->set_message('activate_successful');
		} else {
			$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
			$this->set_error('activate_unsuccessful');
		}


		return $return;
	}


	/**
	 * Deactivate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($id = NULL)
	{
		$this->trigger_events('deactivate');

		if (!isset($id)) {
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}

		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;

		$data = array(
			'activation_code' => $activation_code,
			'active'          => 0
		);

		$this->trigger_events('extra_where');
		$this->db->table($this->tables['users'])->update($data, array('id' => $id));

		$return = $this->db->affectedRows() == 1;
		if ($return)
			$this->set_message('deactivate_successful');
		else
			$this->set_error('deactivate_unsuccessful');

		return $return;
	}

	public function clear_forgotten_password_code($code)
	{

		if (empty($code)) {
			return FALSE;
		}

		$query = $this->db->table($this->tables['users'])->where('forgotten_password_code', $code);

		if ($query->countAllResults() > 0) {
			$data = array(
				'forgotten_password_code' => NULL,
				'forgotten_password_time' => NULL
			);

			$this->db->table($this->tables['users'])->update($data, array('forgotten_password_code' => $code));

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * reset password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function reset_password($identity, $new)
	{
		$this->trigger_events('pre_change_password');

		if (!$this->identity_check($identity)) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->table($this->tables['users'])->select('id, password, salt')
			->where($this->identity_column, $identity)
			->limit(1)
			->orderBy('id', 'desc')
			->get();

		if ($query->getNumRows() !== 1) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result = $query->getRow();

		$new = $this->hash_password($new, $result->salt);

		// store the new password and reset the remember code so all remembered instances have to re-login
		// also clear the forgotten password code
		$data = array(
			'password' => $new,
			'remember_code' => NULL,
			'forgotten_password_code' => NULL,
			'forgotten_password_time' => NULL,
		);

		$this->trigger_events('extra_where');
		$this->db->table($this->tables['users'])->update($data, array($this->identity_column => $identity));

		$return = $this->db->affectedRows() == 1;
		if ($return) {
			$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
			$this->set_message('password_change_successful');
		} else {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
		}

		return $return;
	}

	/**
	 * change password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function change_password($identity, $old, $new)
	{
		$this->trigger_events('pre_change_password');

		$this->trigger_events('extra_where');

		$query = $this->db->table($this->tables['users'])->select('id, password, salt')
			->where($this->identity_column, $identity)
			->limit(1)
			->orderBy('id', 'desc')
			->get();

		if ($query->getNumRows() !== 1) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$user = $query->getRow();

		$old_password_matches = $this->hash_password_db($user->id, $old);

		if ($old_password_matches === TRUE) {
			// store the new password and reset the remember code so all remembered instances have to re-login
			$hashed_new_password  = $this->hash_password($new, $user->salt);
			$data = array(
				'password' => $hashed_new_password,
				'remember_code' => NULL,
			);

			$this->trigger_events('extra_where');

			$successfully_changed_password_in_db = $this->db->table($this->tables['users'])->update($data, array($this->identity_column => $identity));
			if ($successfully_changed_password_in_db) {
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			} else {
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}

			return $successfully_changed_password_in_db;
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}


	/**
	 * Checks username
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function username_check($username = '')
	{
		$this->trigger_events('username_check');

		if (empty($username)) {
			return FALSE;
		}

		$this->trigger_events('extra_where');

		return $this->db->table($this->tables['users'])->where('username', $username)
			->groupBy("id")
			->orderBy("id", "ASC")
			->limit(1)
			->countAllResults() > 0;
	}


	/**
	 * Checks email
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function email_check($email = '')
	{
		$this->trigger_events('email_check');

		if (empty($email)) {
			return FALSE;
		}

		$this->trigger_events('extra_where');

		return $this->db->table($this->table['users'])->where('email', $email)
			->groupBy("id")
			->orderBy("id", "ASC")
			->limit(1)
			->countAllResults() > 0;
	}

	/**
	 * Identity check
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function identity_check($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity)) {
			return FALSE;
		}

		return $this->db->table($this->tables['users'])->where($this->identity_column, $identity)
			->countAllResults() > 0;
	}

	/**
	 * Insert a forgotten password key.
	 *
	 * @return bool
	 * @author Mathew
	 * @updated Ryan
	 * @updated 52aa456eef8b60ad6754b31fbdcc77bb
	 **/
	public function forgotten_password($identity)
	{
		if (empty($identity)) {
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
			return FALSE;
		}

		// All some more randomness
		$activation_code_part = "";
		if (function_exists("openssl_random_pseudo_bytes")) {
			$activation_code_part = openssl_random_pseudo_bytes(128);
		}

		for ($i = 0; $i < 1024; $i++) {
			$activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
		}

		$key = $this->hash_code($activation_code_part . $identity);

		// If enable query strings is set, then we need to replace any unsafe characters so that the code can still work
		if ($key != '' && config('IonAuth')->permitted_uri_chars != '' && config('IonAuth')->enable_query_strings == FALSE) {
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if (! preg_match("|^[" . str_replace(array('\\-', '\-'), '-', preg_quote(config('IonAuth')->permitted_uri_chars, '-')) . "]+$|i", $key)) {
				$key = preg_replace("/[^" . config('IonAuth')->permitted_uri_chars . "]+/i", "-", $key);
			}
		}

		$this->forgotten_password_code = $key;

		$this->trigger_events('extra_where');

		$update = array(
			'forgotten_password_code' => $key,
			'forgotten_password_time' => time()
		);

		$this->db->table($this->tables['users'])->update($update, array($this->identity_column => $identity));

		$return = $this->db->affectedRows() == 1;

		if ($return)
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
		else
			$this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));

		return $return;
	}

	/**
	 * Forgotten Password Complete
	 *
	 * @return string
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code, $salt = FALSE)
	{
		$this->trigger_events('pre_forgotten_password_complete');

		if (empty($code)) {
			$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
			return FALSE;
		}

		$profile = $this->where('forgotten_password_code', $code)->users()->getRow(); //pass the code to profile

		if ($profile) {

			if (config('IonAuth')->forgot_password_expiration > 0) {
				//Make sure it isn't expired
				$expiration = config('IonAuth')->forgot_password_expiration;
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->set_error('forgot_password_expired');
					$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
					return FALSE;
				}
			}

			$password = $this->salt();

			$data = array(
				'password'                => $this->hash_password($password, $salt),
				'forgotten_password_code' => NULL,
				'active'                  => 1,
			);

			$this->db->table($this->tables['users'])->update($data, array('forgotten_password_code' => $code));

			$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_successful'));
			return $password;
		}

		$this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
		return FALSE;
	}

	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register($identity, $password, $email, $additional_data = array(), $groups = array())
	{
		$this->trigger_events('pre_register');

		$manual_activation = config('IonAuth')->manual_activation;

		if ($this->identity_check($identity)) {
			$this->set_error('account_creation_duplicate_identity');
			return FALSE;
		} elseif (!config('IonAuth')->default_group && empty($groups)) {
			$this->set_error('account_creation_missing_default_group');
			return FALSE;
		}

		// check if the default set in config exists in database
		$query = $this->db->table($this->tables['groups'])->getWhere(array('name' => config('IonAuth')->default_group), 1)->getRow();
		if (!isset($query->id) && empty($groups)) {
			$this->set_error('account_creation_invalid_default_group');
			return FALSE;
		}

		// capture default group details
		$default_group = $query;

		// IP Address
		$ip_address = $this->_prepare_ip(service('request')->getIPAddress());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password, $salt);

		// Users table.
		$data = array(
			$this->identity_column   => $identity,
			'username'   => $identity,
			'password'   => $password,
			'email'      => $email,
			'ip_address' => $ip_address,
			'created_on' => time(),
			'active'     => ($manual_activation === false ? 1 : 0)
		);

		if ($this->store_salt) {
			$data['salt'] = $salt;
		}

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data
		$user_data = array_merge($this->_filter_data($this->tables['users'], $additional_data), $data);

		$this->trigger_events('extra_set');

		$this->db->table($this->tables['users'])->insert($user_data);

		$id = $this->db->insertID();

		// add in groups array if it doesn't exists and stop adding into default group if default group ids are set
		if (isset($default_group->id) && empty($groups)) {
			$groups[] = $default_group->id;
		}

		if (!empty($groups)) {
			// add to groups
			foreach ($groups as $group) {
				$this->add_to_group($group, $id);
			}
		}

		$this->trigger_events('post_register');

		return (isset($id)) ? $id : FALSE;
	}

	/**
	 * login
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function login($identity, $password, $remember = FALSE)
	{
		$this->trigger_events('pre_login');

		if (empty($identity) || empty($password)) {
			$this->set_error('login_unsuccessful');
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->table($this->tables['users'])->select($this->identity_column . ', email, id, password, active, last_login')
			->where($this->identity_column, $identity)
			->limit(1)
			->orderBy('id', 'desc')
			->get();

		if ($this->is_max_login_attempts_exceeded($identity)) {
			// Hash something anyway, just to take up time
			$this->hash_password($password);

			$this->trigger_events('post_login_unsuccessful');
			$this->set_error('login_timeout');

			return FALSE;
		}

		if ($query->getNumRows() === 1) {
			$user = $query->getRow();

			$password = $this->hash_password_db($user->id, $password);

			if ($password === TRUE) {
				if ($user->active == 0) {
					$this->trigger_events('post_login_unsuccessful');
					$this->set_error('login_unsuccessful_not_active');

					return FALSE;
				}

				$this->set_session($user);

				$this->update_last_login($user->id);

				$this->clear_login_attempts($identity);

				if ($remember && config('IonAuth')->remember_users) {
					$this->remember_user($user->id);
				}

				$this->trigger_events(array('post_login', 'post_login_successful'));
				$this->set_message('login_successful');

				return TRUE;
			}
		}

		// Hash something anyway, just to take up time
		$this->hash_password($password);

		$this->increase_login_attempts($identity);

		$this->trigger_events('post_login_unsuccessful');
		$this->set_error('login_unsuccessful');

		return FALSE;
	}

	/**
	 * recheck_session verifies if the session should be rechecked according to
	 * the configuration item recheck_timer. If it does, then it will check if the user is still active
	 * @return bool
	 */
	public function recheck_session()
	{
		$recheck = (null !== config('IonAuth')->recheck_timer) ? config('IonAuth')->recheck_timer : 0;

		if ($recheck !== 0) {
			$last_login = session()->get('last_check');
			if ($last_login + $recheck < time()) {
				$query = $this->db->table($this->tables['users'])->select('id')
					->where(array($this->identity_column => session()->get('identity'), 'active' => '1'))
					->limit(1)
					->orderBy('id', 'desc')
					->get();
				if ($query->getNumRows() === 1) {
					session()->set('last_check', time());
				} else {
					$this->trigger_events('logout');

					$identity = config('IonAuth')->identity;

					// if (substr(CI_VERSION, 0, 1) == '2') {
					// $this->session->unset_userdata(array($identity => '', 'id' => '', 'user_id' => ''));
					// } else {
					session()->remove([$identity, 'id', 'user_id']);
					// }
					return false;
				}
			}
		}

		return (bool) session()->get('identity');
	}

	/**
	 * is_max_login_attempts_exceeded
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity: user's identity
	 * @param string $ip_address: IP address
	 *                            Only used if track_login_ip_address set to TRUE.
	 *                            If NULL (default value), current IP address is used.
	 *                            Use get_last_attempt_ip($identity) to retrieve user's last IP
	 * @return boolean
	 **/
	public function is_max_login_attempts_exceeded($identity, $ip_address = NULL)
	{
		if (config('IonAuth')->track_login_attempts) {
			$max_attempts = config('IonAuth')->maximum_login_attempts;
			if ($max_attempts > 0) {
				$attempts = $this->get_attempts_num($identity, $ip_address);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
	}

	/**
	 * Get number of attempts to login occured from given IP-address or identity
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity: user's identity
	 * @param string $ip_address: IP address
	 *                            Only used if track_login_ip_address set to TRUE.
	 *                            If NULL (default value), current IP address is used.
	 *                            Use get_last_attempt_ip($identity) to retrieve user's last IP
	 * @return int
	 */
	public function get_attempts_num($identity, $ip_address = NULL)
	{
		if (config('IonAuth')->track_login_attempts) {
			$query = $this->db->table($this->tables['login_attempts'])->select('1', FALSE);
			$query = $query->where('login', $identity);
			if (config('IonAuth')->track_login_ip_address) {
				if (!isset($ip_address)) {
					$ip_address = $this->_prepare_ip(Services::request()->getIPAddress());
				}
				$query = $query->where('ip_address', $ip_address);
			}
			$query = $query->where('time >', time() - config('IonAuth')->lockout_time, FALSE);
			$qres = $query->get();
			return $qres->getNumRows();
		}
		return 0;
	}

	/**
	 * Get a boolean to determine if an account should be locked out due to
	 * exceeded login attempts within a given period
	 *
	 * This function is only a wrapper for is_max_login_attempts_exceeded() since it
	 * only retrieve attempts within the given period.
	 * It is kept for retrocompatibility purpose.
	 *
	 * @param string $identity: user's identity
	 * @param string $ip_address: IP address
	 *                            Only used if track_login_ip_address set to TRUE.
	 *                            If NULL (default value), current IP address is used.
	 *                            Use get_last_attempt_ip($identity) to retrieve user's last IP
	 * @return boolean
	 */
	public function is_time_locked_out($identity, $ip_address = NULL)
	{
		return $this->is_max_login_attempts_exceeded($identity, $ip_address);
	}

	/**
	 * Get the time of the last time a login attempt occured from given IP-address or identity
	 *
	 * This function is no longer used.
	 * It is kept for retrocompatibility purpose.
	 *
	 * @param string $identity: user's identity
	 * @param string $ip_address: IP address
	 *                            Only used if track_login_ip_address set to TRUE.
	 *                            If NULL (default value), current IP address is used.
	 *                            Use get_last_attempt_ip($identity) to retrieve user's last IP
	 * @return int
	 */
	public function get_last_attempt_time($identity, $ip_address = NULL)
	{
		if (config('IonAuth')->track_login_attempts) {
			$query = $this->db->table($this->tables['login_attempts'])->select('time');
			$query = $query->where('login', $identity);
			if (config('IonAuth')->track_login_ip_address) {
				if (!isset($ip_address)) {
					$ip_address = $this->_prepare_ip(Services::request()->getIPAddress());
				}
				$query = $query->where('ip_address', $ip_address);
			}
			$query = $query->orderBy('id', 'desc');
			$qres = $query = $query->limit(1)->get();

			if ($qres->getNumRows() > 0) {
				return $qres->getRow()->time;
			}
		}

		return 0;
	}

	/**
	 * Get the IP address of the last time a login attempt occured from given identity
	 *
	 * @param string $identity: user's identity
	 * @return string
	 */
	public function get_last_attempt_ip($identity)
	{
		if (config('IonAuth')->track_login_attempts && config('IonAuth')->track_login_ip_address) {
			$query = $this->db->table($this->tables['login_attempts'])->select('ip_address');
			$query = $query->where('login', $identity);
			$query = $query->orderBy('id', 'desc');
			$qres = $query = $query->limit(1)->get();

			if ($qres->getNumRows() > 0) {
				return $qres->getRow()->ip_address;
			}
		}

		return '';
	}

	/**
	 * increase_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * Note: the current IP address will be used if track_login_ip_address config value is TRUE
	 *
	 * @param string $identity: user's identity
	 **/
	public function increase_login_attempts($identity)
	{
		if (config('IonAuth')->track_login_attempts) {
			$data = array('ip_address' => '', 'login' => $identity, 'time' => time());
			if (config('IonAuth')->track_login_ip_address) {
				$data['ip_address'] = $this->_prepare_ip(Services::request()->getIPAddress());
			}
			return $this->db->table($this->tables['login_attempts'])->insert($data);
		}
		return FALSE;
	}

	/**
	 * clear_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity: user's identity
	 * @param int $old_attempts_expire_period: in seconds, any attempts older than this value will be removed.
	 *                                         It is used for regularly purging the attempts table.
	 *                                         (for security reason, minimum value is lockout_time config value)
	 * @param string $ip_address: IP address
	 *                            Only used if track_login_ip_address set to TRUE.
	 *                            If NULL (default value), current IP address is used.
	 *                            Use get_last_attempt_ip($identity) to retrieve user's last IP
	 **/
	public function clear_login_attempts($identity, $old_attempts_expire_period = 86400, $ip_address = NULL)
	{
		if (config('IonAuth')->track_login_attempts) {
			// Make sure $old_attempts_expire_period is at least equals to lockout_time
			$old_attempts_expire_period = max($old_attempts_expire_period, config('IonAuth')->lockout_time);

			$query = $this->db->table($this->tables['login_attempts'])->where('login', $identity);
			if (config('IonAuth')->track_login_ip_address) {
				if (!isset($ip_address)) {
					$ip_address = $this->_prepare_ip(Services::request()->getIPAddress());
				}
				$query = $query->where('ip_address', $ip_address);
			}
			// Purge obsolete login attempts
			$query = $query->orWhere('time <', time() - $old_attempts_expire_period, FALSE);

			return $query->delete();
		}
		return FALSE;
	}

	public function limit($limit)
	{
		$this->trigger_events('limit');
		$this->_ion_limit = $limit;

		return $this;
	}

	public function offset($offset)
	{
		$this->trigger_events('offset');
		$this->_ion_offset = $offset;

		return $this;
	}

	public function where($where, $value = NULL)
	{
		$this->trigger_events('where');

		if (!is_array($where)) {
			$where = array($where => $value);
		}

		array_push($this->_ion_where, $where);

		return $this;
	}

	public function like($like, $value = NULL, $position = 'both')
	{
		$this->trigger_events('like');

		array_push($this->_ion_like, array(
			'like'     => $like,
			'value'    => $value,
			'position' => $position
		));

		return $this;
	}

	public function select($select)
	{
		$this->trigger_events('select');

		$this->_ion_select[] = $select;

		return $this;
	}

	public function order_by($by, $order = 'desc')
	{
		$this->trigger_events('order_by');

		$this->_ion_order_by = $by;
		$this->_ion_order    = $order;

		return $this;
	}

	public function row()
	{
		$this->trigger_events('row');

		$row = $this->response->getRow();

		return $row;
	}

	public function row_array()
	{
		$this->trigger_events(array('row', 'row_array'));

		$row = $this->response->getRowArray();

		return $row;
	}

	public function result()
	{
		$this->trigger_events('result');

		$result = $this->response->getResult();

		return $result;
	}

	public function result_array()
	{
		$this->trigger_events(array('result', 'result_array'));

		$result = $this->response->getResultArray();

		return $result;
	}

	public function num_rows()
	{
		$this->trigger_events(array('num_rows'));

		$result = $this->response->getNumRows();

		return $result;
	}

	/**
	 * users
	 *
	 * @return object Users
	 * @author Ben Edmunds
	 **/
	public function users($groups = NULL)
	{
		$this->trigger_events('users');

		$query = $this->db->table($this->tables['users']);

		if (isset($this->_ion_select) && !empty($this->_ion_select)) {
			foreach ($this->_ion_select as $select) {
				$query->select($select);
			}

			$this->_ion_select = array();
		} else {
			//default selects
			$query->select(array(
				$this->tables['users'] . '.*',
				$this->tables['users'] . '.id as id',
				$this->tables['users'] . '.id as user_id'
			));
		}

		// filter by group id(s) if passed
		if (isset($groups)) {
			// build an array if only one group was passed
			if (!is_array($groups)) {
				$groups = array($groups);
			}

			// join and then run a where_in against the group ids
			if (isset($groups) && !empty($groups)) {
				$query->distinct();
				$query->join(
					$this->tables['users_groups'],
					$this->tables['users_groups'] . '.' . $this->join['users'] . '=' . $this->tables['users'] . '.id',
					'inner'
				);
			}

			// verify if group name or group id was used and create and put elements in different arrays
			$group_ids = array();
			$group_names = array();
			foreach ($groups as $group) {
				if (is_numeric($group)) $group_ids[] = $group;
				else $group_names[] = $group;
			}
			$or_where_in = (!empty($group_ids) && !empty($group_names)) ? 'orWhereIn' : 'whereIn';
			// if group name was used we do one more join with groups
			if (!empty($group_names)) {
				$query->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . ' = ' . $this->tables['groups'] . '.id', 'inner');
				$query->whereIn($this->tables['groups'] . '.name', $group_names);
			}
			if (!empty($group_ids)) {
				$query->{$or_where_in}($this->tables['users_groups'] . '.' . $this->join['groups'], $group_ids);
			}
		}

		$this->trigger_events('extra_where');

		// run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where)) {
			foreach ($this->_ion_where as $where) {
				$query->where($where);
			}

			$this->_ion_where = array();
		}

		if (isset($this->_ion_like) && !empty($this->_ion_like)) {
			foreach ($this->_ion_like as $like) {
				$query->orLike($like);
			}

			$this->_ion_like = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset)) {
			$query->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		} else if (isset($this->_ion_limit)) {
			$query->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		// set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order)) {
			$query->orderBy($this->_ion_order_by, $this->_ion_order);

			$this->_ion_order    = NULL;
			// $this->_ion_order    = "first_name";
			$this->_ion_order_by    = NULL;
			// $this->_ion_order_by = "first_name";
		}

		$this->response = $query->get();

		return $this->response;
	}

	/**
	 * user
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function user($id = NULL)
	{
		$this->trigger_events('user');

		// if no id was passed use the current users id
		$id = isset($id) ? $id : session()->get('user_id');

		// $this->limit(1);
		// $this->orderBy($this->tables['users'] . '.id', 'desc');
		// $this->where($this->tables['users'] . '.id', $id);
		array_push($this->_ion_where, array($this->tables['users'] . '.id' => $id));
		$this->_ion_limit = 1;
		$this->_ion_order_by =  $this->tables['users'] . '.id';
		$this->_ion_order = 'DESC';
		return $this->users();

		return $this;
	}

	/**
	 * get_users_groups
	 *
	 * @return array
	 * @author Ben Edmunds
	 **/
	public function get_users_groups($id = FALSE)
	{
		$this->trigger_events('get_users_group');

		// if no id was passed use the current users id
		$id || $id = session()->get('user_id');

		return $this->db->table($this->tables['users_groups'])->select($this->tables['users_groups'] . '.' . $this->join['groups'] . ' as id, ' . $this->tables['groups'] . '.name, ' . $this->tables['groups'] . '.description, ' . $this->tables['groups'] . '.group_access, ' . $this->tables['groups'] . '.user_access, ' . $this->tables['groups'] . '.presenter_access, ' . $this->tables['groups'] . '.subscriber_access, ' . $this->tables['groups'] . '.menu_access, ' . $this->tables['groups'] . '.category_access, ' . $this->tables['groups'] . '.article_access, ' . $this->tables['groups'] . '.tag_access, ' . $this->tables['groups'] . '.schedule_access, ' . $this->tables['groups'] . '.album_access, ' . $this->tables['groups'] . '.video_access, ' . $this->tables['groups'] . '.contact_access, ' . $this->tables['groups'] . '.media_access, ' . $this->tables['groups'] . '.teacher_access')
			->where($this->tables['users_groups'] . '.' . $this->join['users'], $id)
			->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . '=' . $this->tables['groups'] . '.id')
			->get();
	}

	/**
	 * add_to_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function add_to_group($group_ids, $user_id = false)
	{
		$this->trigger_events('add_to_group');

		// if no id was passed use the current users id
		$user_id || $user_id = session()->get('user_id');

		if (!is_array($group_ids)) {
			$group_ids = array($group_ids);
		}

		$return = 0;

		// Then insert each into the database
		foreach ($group_ids as $group_id) {
			if ($this->db->table($this->tables['users_groups'])->insert(array($this->join['groups'] => (float)$group_id, $this->join['users'] => (float)$user_id))) {
				if (isset($this->_cache_groups[$group_id])) {
					$group_name = $this->_cache_groups[$group_id];
				} else {
					$group = $this->group($group_id)->getResult();
					$group_name = $group[0]->name;
					$this->_cache_groups[$group_id] = $group_name;
				}
				$this->_cache_user_in_group[$user_id][$group_id] = $group_name;

				// Return the number of groups added
				$return += 1;
			}
		}

		return $return;
	}

	/**
	 * remove_from_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function remove_from_group($group_ids = false, $user_id = false)
	{
		$this->trigger_events('remove_from_group');

		// user id is required
		if (empty($user_id)) {
			return FALSE;
		}

		// if group id(s) are passed remove user from the group(s)
		if (! empty($group_ids)) {
			if (!is_array($group_ids)) {
				$group_ids = array($group_ids);
			}

			foreach ($group_ids as $group_id) {
				$this->db->table($this->tables['users_groups'])->delete(array($this->join['groups'] => (float)$group_id, $this->join['users'] => (float)$user_id));
				if (isset($this->_cache_user_in_group[$user_id]) && isset($this->_cache_user_in_group[$user_id][$group_id])) {
					unset($this->_cache_user_in_group[$user_id][$group_id]);
				}
			}

			$return = TRUE;
		}
		// otherwise remove user from all groups
		else {
			if ($return = $this->db->table($this->tables['users_groups'])->delete(array($this->join['users'] => (float)$user_id))) {
				$this->_cache_user_in_group[$user_id] = array();
			}
		}
		return $return;
	}

	/**
	 * groups
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function groups()
	{
		$this->trigger_events('groups');
		$query = $this->db->table($this->tables['groups']);

		// run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where)) {
			foreach ($this->_ion_where as $where) {
				$query = $query->where($where);
			}
			$this->_ion_where = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset)) {
			$query = $query->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		} else if (isset($this->_ion_limit)) {
			$query = $query->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		// set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order)) {
			$query = $query->orderBy($this->_ion_order_by, $this->_ion_order);
		}

		$this->response = $query->get();

		return $this->response;
	}

	/**
	 * group
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function group($id = NULL)
	{
		$this->trigger_events('group');

		if (isset($id)) {
			$this->where($this->tables['groups'] . '.id', $id);
		}

		$this->limit(1);
		$this->order_by('id', 'desc');

		return $this->groups();
	}

	/**
	 * update
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function updateCustom($id, array $data)
	{
		$this->trigger_events('pre_update_user');

		$user = $this->user($id)->getRow();

		$this->db->transBegin();

		if (array_key_exists($this->identity_column, $data) && $this->identity_check($data[$this->identity_column]) && $user->{$this->identity_column} !== $data[$this->identity_column]) {
			$this->db->transRollback();
			$this->set_error('account_creation_duplicate_identity');

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');

			return FALSE;
		}

		// Filter the data passed
		$data = $this->_filter_data($this->tables['users'], $data);

		if (array_key_exists($this->identity_column, $data) || array_key_exists('password', $data) || array_key_exists('email', $data)) {
			if (array_key_exists('password', $data)) {
				if (! empty($data['password'])) {
					$data['password'] = $this->hash_password($data['password'], $user->salt);
				} else {
					// unset password so it doesn't effect database entry if no password passed
					unset($data['password']);
				}
			}
		}

		$this->trigger_events('extra_where');
		$this->db->table($this->tables['users'])->update($data, array('id' => $user->id));

		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');
			return FALSE;
		}

		$this->db->transCommit();

		$this->trigger_events(array('post_update_user', 'post_update_user_successful'));
		$this->set_message('update_successful');
		return TRUE;
	}
	/**
	 * delete_user
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function delete_user($id)
	{
		$this->trigger_events('pre_delete_user');

		$this->db->transBegin();

		// remove user from groups
		$this->remove_from_group(NULL, $id);

		// delete user from users table should be placed after remove from group
		$this->db->table($this->tables['users'])->delete(array('id' => $id));


		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();
			$this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
			$this->set_error('delete_unsuccessful');
			return FALSE;
		}

		$this->db->transCommit();

		$this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
		$this->set_message('delete_successful');
		return TRUE;
	}

	/**
	 * update_last_login
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function update_last_login($id)
	{
		$this->trigger_events('update_last_login');

		helper('date');

		$this->trigger_events('extra_where');

		$this->db->table($this->tables['users'])->update(array('last_login' => time()), array('id' => $id));
		return $this->db->affectedRows() == 1;
	}

	/**
	 * set_lang
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function set_lang($lang = 'en')
	{
		$this->trigger_events('set_lang');

		// if the user_expire is set to zero we'll set the expiration two years from now.
		if (config('IonAuth')->user_expire === 0) {
			$expire = (60 * 60 * 24 * 365 * 2);
		}
		// otherwise use what is set
		else {
			$expire = config('IonAuth')->user_expire;
		}

		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $expire
		));

		return TRUE;
	}

	/**
	 * set_session
	 *
	 * @return bool
	 * @author jrmadsen67
	 **/
	public function set_session($user)
	{

		$this->trigger_events('pre_set_session');

		$session_data = array(
			'identity'             => $user->{$this->identity_column},
			$this->identity_column             => $user->{$this->identity_column},
			'email'                => $user->email,
			'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
			'old_last_login'       => $user->last_login,
			'last_check'           => time(),
		);

		session()->set($session_data);

		$this->trigger_events('post_set_session');

		return TRUE;
	}

	/**
	 * remember_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function remember_user($id)
	{
		$this->trigger_events('pre_remember_user');

		if (!$id) {
			return FALSE;
		}

		$user = $this->user($id)->getRow();

		$salt = $this->salt();

		$query = $this->db->table($this->tables['users'])->update(array('remember_code' => $salt), array('id' => $id));

		if ($this->db->affectedRows() > -1) {
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if (config('IonAuth')->user_expire === 0) {
				$expire = (60 * 60 * 24 * 365 * 2);
			}
			// otherwise use what is set
			else {
				$expire = config('IonAuth')->user_expire;
			}

			response()->setCookie(array(
				'name'   => config('IonAuth')->identity_cookie_name,
				'value'  => $user->{$this->identity_column},
				'expire' => $expire
			));

			response()->setCookie(array(
				'name'   => config('IonAuth')->remember_cookie_name,
				'value'  => $salt,
				'expire' => $expire
			));

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}

	/**
	 * login_remembed_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function login_remembered_user()
	{
		$this->trigger_events('pre_login_remembered_user');

		// check for valid data
		if (
			!service('request')->getCookie(config('IonAuth')->identity_cookie_name)
			|| !service('request')->getCookie(config('IonAuth')->remember_cookie_name)
			|| !$this->identity_check(service('request')->getCookie(config('IonAuth')->identity_cookie_name))
		) {
			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
			return FALSE;
		}

		// get the user
		$this->trigger_events('extra_where');
		$query = $this->db->table($this->tables['users'])->select($this->identity_column . ', id, email, last_login')
			->where($this->identity_column, urldecode(service('request')->getCookie(config('IonAuth')->identity_cookie_name)))
			->where('remember_code', service('request')->getCookie(config('IonAuth')->remember_cookie_name))
			->limit(1)
			->orderBy('id', 'desc')
			->get();

		// if the user was found, sign them in
		if ($query->getNumRows() == 1) {
			$user = $query->getRow();

			$this->update_last_login($user->id);

			$this->set_session($user);

			// extend the users cookies if the option is enabled
			if (config('IonAuth')->user_extend_on_login) {
				$this->remember_user($user->id);
			}

			$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
			return TRUE;
		}

		$this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
		return FALSE;
	}



	/**
	 * create_group
	 *
	 * @author aditya menon
	 */
	public function create_group($group_name = FALSE, $group_description = '', $additional_data = array())
	{
		// bail if the group name was not passed
		if (!$group_name) {
			$this->set_error('group_name_required');
			return FALSE;
		}

		// bail if the group name already exists
		$existing_group = $this->db->table($this->tables['groups'])->where(array('name' => $group_name))->get()->getNumRows();
		if ($existing_group !== 0) {
			$this->set_error('group_already_exists');
			return FALSE;
		}

		$data = array('name' => $group_name, 'description' => $group_description);

		// filter out any data passed that doesnt have a matching column in the groups table
		// and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);

		$this->trigger_events('extra_group_set');

		// insert the new group
		$this->db->table($this->tables['groups'])->insert($data);
		$group_id = $this->db->insertID();

		// report success
		$this->set_message('group_creation_successful');
		// return the brand new group id
		return $group_id;
	}

	/**
	 * update_group
	 *
	 * @return bool
	 * @author aditya menon
	 **/
	public function update_group($group_id = FALSE, $group_name = FALSE, $additional_data = array())
	{
		if (empty($group_id)) return FALSE;

		$data = array();

		if (!empty($group_name)) {
			// we are changing the name, so do some checks

			// bail if the group name already exists
			$existing_group = $this->db->table($this->tables['groups'])->where(array('name' => $group_name))->get()->getRow();
			if (isset($existing_group->id) && $existing_group->id != $group_id) {
				$this->set_error('group_already_exists');
				return FALSE;
			}

			$data['name'] = $group_name;
		}

		// restrict change of name of the admin group
		$group = $this->db->table($this->tables['groups'])->where(array('id' => $group_id))->get()->getRow();
		if (config('IonAuth')->admin_group === $group->name && $group_name !== $group->name) {
			$this->set_error('group_name_admin_not_alter');
			return FALSE;
		}


		// IMPORTANT!! Third parameter was string type $description; this following code is to maintain backward compatibility
		// New projects should work with 3rd param as array
		if (is_string($additional_data)) $additional_data = array('description' => $additional_data);


		// filter out any data passed that doesnt have a matching column in the groups table
		// and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);

		$this->db->table($this->tables['groups'])->update($data, ['id' => $group_id]);

		$this->set_message('group_update_successful');

		return TRUE;
	}
	/**
	 * delete_group
	 *
	 * @return bool
	 * @author aditya menon
	 **/
	public function delete_group($group_id = FALSE)
	{
		// bail if mandatory param not set
		if (!$group_id || empty($group_id)) {
			return FALSE;
		}
		$group = $this->group($group_id)->getRow();
		if ($group->name == config('IonAuth')->admin_group) {
			$this->trigger_events(array('post_delete_group', 'post_delete_group_notallowed'));
			$this->set_error('group_delete_notallowed');
			return FALSE;
		}

		$this->trigger_events('pre_delete_group');

		$this->db->transBegin();

		// remove all users from this group
		$this->db->table($this->tables['users_groups'])->delete(array($this->join['groups'] => $group_id));
		// remove the group itself
		$this->db->table($this->tables['groups'])->delete(array('id' => $group_id));

		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();
			$this->trigger_events(array('post_delete_group', 'post_delete_group_unsuccessful'));
			$this->set_error('group_delete_unsuccessful');
			return FALSE;
		}

		$this->db->transCommit();

		$this->trigger_events(array('post_delete_group', 'post_delete_group_successful'));
		$this->set_message('group_delete_successful');
		return TRUE;
	}

	public function set_hook($event, $name, $class, $method, $arguments)
	{
		$this->_ion_hooks->{$event}[$name] = new stdClass;
		$this->_ion_hooks->{$event}[$name]->class     = $class;
		$this->_ion_hooks->{$event}[$name]->method    = $method;
		$this->_ion_hooks->{$event}[$name]->arguments = $arguments;
	}

	public function remove_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name])) {
			unset($this->_ion_hooks->{$event}[$name]);
		}
	}

	public function remove_hooks($event)
	{
		if (isset($this->_ion_hooks->$event)) {
			unset($this->_ion_hooks->$event);
		}
	}

	protected function _call_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]) && method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method)) {
			$hook = $this->_ion_hooks->{$event}[$name];

			return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
		}

		return FALSE;
	}

	public function trigger_events($events)
	{
		if (is_array($events) && !empty($events)) {
			foreach ($events as $event) {
				$this->trigger_events($event);
			}
		} else {
			if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events)) {
				foreach ($this->_ion_hooks->$events as $name => $hook) {
					$this->_call_hook($events, $name);
				}
			}
		}
	}

	/**
	 * set_message_delimiters
	 *
	 * Set the message delimiters
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message_delimiters($start_delimiter, $end_delimiter)
	{
		$this->message_start_delimiter = $start_delimiter;
		$this->message_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_error_delimiters
	 *
	 * Set the error delimiters
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error_delimiters($start_delimiter, $end_delimiter)
	{
		$this->error_start_delimiter = $start_delimiter;
		$this->error_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message($message)
	{
		$this->messages[] = $message;

		return $message;
	}



	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message) {
			$messageLang = lang('IonAuthLang.' . $message) ? lang('IonAuthLang.' . $message) : '##' . $message . '##';
			$_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
		}

		return $_output;
	}

	/**
	 * messages as array
	 *
	 * Get the messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function messages_array($langify = TRUE)
	{
		if ($langify) {
			$_output = array();
			foreach ($this->messages as $message) {
				$messageLang = lang('IonAuthLang.' . $message) ? lang('IonAuthLang.' . $message) : '##' . $message . '##';
				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}
			return $_output;
		} else {
			return $this->messages;
		}
	}


	/**
	 * clear_messages
	 *
	 * Clear messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function clear_messages()
	{
		$this->messages = array();

		return TRUE;
	}


	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;

		return $error;
	}

	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function errorsCustom()
	{
		$_output = '';
		foreach ($this->errors as $error) {
			$errorLang = lang('IonAuthLang.' . $error) ? lang('IonAuthLang.' . $error) : '##' . $error . '##';
			$_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
		}

		return $_output;
	}

	/**
	 * errors as array
	 *
	 * Get the error messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function errors_array($langify = TRUE)
	{
		if ($langify) {
			$_output = array();
			foreach ($this->errors as $error) {
				$errorLang = lang('IonAuthLang.' . $error) ? lang('IonAuthLang.' . $error) : '##' . $error . '##';
				$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}
			return $_output;
		} else {
			return $this->errors;
		}
	}


	/**
	 * clear_errors
	 *
	 * Clear Errors
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function clear_errors()
	{
		$this->errors = array();

		return TRUE;
	}



	protected function _filter_data($table, $data)
	{
		$filtered_data = array();
		$columns = $this->db->getFieldNames($table);

		if (is_array($data)) {
			foreach ($columns as $column) {
				if (array_key_exists($column, $data))
					$filtered_data[$column] = $data[$column];
			}
		}

		return $filtered_data;
	}

	protected function _prepare_ip($ip_address)
	{
		// just return the string IP address now for better compatibility
		return $ip_address;
	}
}
