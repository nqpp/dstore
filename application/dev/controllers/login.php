<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'/core/Unsecure.php');

/*
 * Login Class
 * 
 * TODO: determine delivery address of client on login.
 */
class Login extends Unsecure {
  
  private $https = false;

  function __construct() {
    parent::__construct();
    $this->load->model('m_users');
  }
  
  function renderHTML() {
	
	$this->check_https();
	$this->load->vars('content',$this->load->view('system/login','',true));
    $this->pageTemplate('page_dialog');
	
  }
  
  function formHTMLNew() {
	
	$this->load->model('m_permissions');
	$this->load->model('m_user_addresses');
	$this->load->model('m_metas');
	
	try {
	  $data = $this->m_users->authenticate();
	  
	  $this->m_permissions->adminGroup = $data->adminGroup;
	  $data->allowedPages = $this->m_permissions->fetchAllowedUris();
	  
	  $this->m_user_addresses->usersID = $data->userID;
	  $this->m_user_addresses->type = 'delivery';
	  $addresses = $this->m_user_addresses->getLocationsIndexedJoined();

	  if (count($addresses)) {
		$primary = reset($addresses);
		$this->user->set($primary);
		$this->user->alladdresses($addresses);
		
	  }
	  else {
		$this->user->czone('-1');
	  }
	  
	  $this->user->set($data);
	  
	  $this->m_metas->schemaName = 'adminGroupRedirect';
	  $this->m_metas->metaKey = $data->adminGroup;
	  $meta = reset($this->m_metas->fetch());

	  if($this->input->get('redirect')) {
		$redirect = $this->input->get('redirect');
	  }
	  else if (count($meta)) {
		$redirect = $meta->metaValue;
	  }
	  else {
		$redirect = 'error?404';
	  }
	  
	  $redirect .= strpos($redirect,'/') === 0 ? '':'/';
	  die(header("Location: $redirect"));

	} catch(Exception $e) {
	  $this->session->set_flashdata('msg',array('type'=>'error','text'=>$e->getMessage()));
	  die(header("Location: /login.html"));
	}
	
  }
  
  function check_https() {
	
	if (!$this->https) return;
	
	if (!isset($_SERVER['HTTPS']) OR $_SERVER['HTTPS'] != 'on') {
	  $base_url = $this->config->item('base_url');
	  
	  if (substr($base_url,0,5) !== 'https') {
		die (header ("Location:{$base_url}login.html"));
	  }
	}
  }

}
// EOF