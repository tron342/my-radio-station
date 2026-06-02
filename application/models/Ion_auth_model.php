<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Model
*
* Author:  Ben Edmunds
* ben.edmunds@gmail.com
*			@benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.
*
* Requirements: PHP 8.0 or above
*
*/

class Ion_auth_model extends CI_Model
{
	public $tables = array();
	public $activation_code;
	public $forgotten_password_code;
	public $new_password;
	public $identity;
	public $_ion_where = array();
	public $_ion_select = array();
	public $_ion_like = array();
	public $_ion_limit = NULL;
	public $_ion_offset = NULL;
	public $_ion_order_by = NULL;
	public $_ion_order = NULL;
	protected $_ion_hooks;
	protected $response = NULL;
	protected $messages;
	protected $errors;
	protected $error_start_delimiter;
	protected $error_end_delimiter;
	public $_cache_user_in_group = array();
	protected $_cache_groups = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');

		$this->tables = $this->config->item('tables', 'ion_auth');
		$this->identity_column = $this->config->item('identity', 'ion_auth');
		$this->store_salt      = $this->config->item('store_salt', 'ion_auth');
		$this->salt_length     = $this->config->item('salt_length', 'ion_auth');
		$this->join            = $this->config->item('join', 'ion_auth');

		$this->hash_method = $this->config->item('hash_method', 'ion_auth');
		$this->default_rounds = $this->config->item('default_rounds', 'ion_auth');

		$this->messages    = array();
		$this->errors      = array();
		$delimiters_source = $this->config->item('delimiters_source', 'ion_auth');

		if ($delimiters_source === 'form_validation')
		{
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
		}
		else
		{
			$this->message_start_delimiter = $this->config->item('message_start_delimiter', 'ion_auth');
			$this->message_end_delimiter   = $this->config->item('message_end_delimiter', 'ion_auth');
			$this->error_start_delimiter   = $this->config->item('error_start_delimiter', 'ion_auth');
			$this->error_end_delimiter     = $this->config->item('error_end_delimiter', 'ion_auth');
		}

		$this->_ion_hooks = new stdClass;

		// NOTA: A biblioteca Bcrypt externa não é mais carregada aqui, 
		// pois usamos os métodos nativos estáveis do PHP 8+.
		
		$this->trigger_events('model_constructor');
	}

	/**
	 * ATUALIZADO PARA PHP 8+: Encripta a senha usando o gerador estável nativo.
	 **/
	public function hash_password($password, $salt=false, $use_sha1_override=FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}

		if ($use_sha1_override === FALSE)
		{
			// Cost padrão seguro configurado dinamicamente para manter compatibilidade
			$rounds = (!empty($this->default_rounds)) ? $this->default_rounds : 8;
			return password_hash($password, PASSWORD_BCRYPT, ['cost' => $rounds]);
		}

		if ($this->store_salt && $salt)
		{
			return sha1($password . $salt);
		}
		else
		{
			$salt = $this->salt();
			return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}

	/**
	 * ATUALIZADO PARA PHP 8+: Valida a senha do banco usando password_verify nativo.
	 **/
	public function hash_password_db($id, $password, $use_sha1_override=FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select('password, salt')
						  ->where('id', $id)
						  ->limit(1)
						  ->order_by('id', 'desc')
						  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		$hash_password_db = $query->row();

		if ($use_sha1_override === FALSE)
		{
			// Verifica a hash nativamente (Funciona perfeitamente para as senhas novas geradas em Bcrypt)
			return password_verify($password, $hash_password_db->password);
		}

		// Fallback legível para sha1 antigo caso necessário em instalações em transição
		if ($this->store_salt)
		{
			$db_password = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}

		return ($db_password == $hash_password_db->password);
	}

	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}

	/**
	 * ATUALIZADO PARA PHP 8+: Removido mcrypt (que causava Fatal Error) 
	 * substituído por random_bytes de alta segurança nativa.
	 **/
	public function salt()
	{
		$raw_salt_len = 16;
		try {
			$buffer = random_bytes($raw_salt_len);
		} catch (Exception $e) {
			$buffer = openssl_random_pseudo_bytes($raw_salt_len);
		}

		$base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
		$bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$base64_string   = base64_encode($buffer);
		$salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);

		return substr($salt, 0, $this->salt_length);
	}

	public function activate($id, $code = false)
	{
		$this->trigger_events('pre_activate');

		if ($code !== FALSE)
		{
			$query = $this->db->select($this->identity_column)
							  ->where('activation_code', $code)
							  ->where('id', $id)
							  ->limit(1)
							  ->order_by('id', 'desc')
							  ->get($this->tables['users']);

			if ($query->num_rows() !== 1)
			{
				$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
				$this->set_error('activate_unsuccessful');
				return FALSE;
			}
		}

		$data = array(
			'activation_code' => NULL,
			'active'          => 1
		);

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array('id' => $id));

		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->trigger_events(array('post_activate', 'post_activate_successful'));
			$this->set_message('activate_successful');
		}
		else
		{
			$this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
			$this->set_error('activate_unsuccessful');
		}

		return $return;
	}

	public function deactivate($id = NULL)
	{
		$this->trigger_events('deactivate');

		if (!isset($id))
		{
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
		$this->db->update($this->tables['users'], $data, array('id' => $id));

		$return = $this->db->affected_rows() == 1;
		if ($return)
			$this->set_message('deactivate_successful');
		else
			$this->set_error('deactivate_unsuccessful');

		return $return;
	}

	public function clear_forgotten_password_code($code) {
		if (empty($code))
		{
			return FALSE;
		}

		$this->db->where('forgotten_password_code', $code);

		if ($this->db->count_all_results($this->tables['users']) > 0)
		{
			$data = array(
				'forgotten_password_code' => NULL,
				'forgotten_password_time' => NULL
			);

			$this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));
			return TRUE;
		}

		return FALSE;
	}

	public function reset_password($identity, $new) {
		$this->trigger_events('pre_change_password');

		if (!$this->identity_check($identity)) {
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			return FALSE;
		}

		$this->trigger_events('extra_where');

		$query = $this->db->select('id, password, salt')
						  ->where($this->identity_column, $identity)
						  ->limit(1)
						  ->order_by('id', 'desc')
						  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$result = $query->row();
		$new = $this->hash_password($new, $result->salt);

		$data = array(
			'password' => $new,
			'remember_code' => NULL,
			'forgotten_password_code' => NULL,
			'forgotten_password_time' => NULL,
		);

		$this->trigger_events('extra_where');
		$this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));

		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
			$this->set_message('password_change_successful');
		}
		else
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
		}

		return $return;
	}

	public function change_password($identity, $old, $new)
	{
		$this->trigger_events('pre_change_password');
		$this->trigger_events('extra_where');

		$query = $this->db->select('id, password, salt')
						  ->where($this->identity_column, $identity)
						  ->limit(1)
						  ->order_by('id', 'desc')
						  ->get($this->tables['users']);

		if ($query->num_rows() !== 1)
		{
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$user = $query->row();
		$old_password_matches = $this->hash_password_db($user->id, $old);

		if ($old_password_matches === TRUE)
		{
			$hashed_new_password  = $this->hash_password($new, $user->salt);
			$data = array(
				'password' => $hashed_new_password,
				'remember_code' => NULL,
			);

			$this->trigger_events('extra_where');
			$successfully_changed_password_in_db = $this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
			
			if ($successfully_changed_password_in_db)
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			}
			else
			{
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}

			return $successfully_changed_password_in_db;
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}

	public function username_check($username = '')
	{
		$this->trigger_events('username_check');
		if (empty($username)) return FALSE;

		$this->trigger_events('extra_where');
		return $this->db->where('username', $username)
										->group_by("id")
										->order_by("id", "ASC")
										->limit(1)
						->count_all_results