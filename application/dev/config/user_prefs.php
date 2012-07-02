<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_prefs extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_user_prefs');
  }

  function formJSONNew() {
	
	$json = json_decode(file_get_contents('php://input'));
	$this->m_user_prefs->set($json);
	$this->m_user_prefs->add();
	
	die (print (json_encode($this->m_user_prefs->get())));
  }
  
  function formJSONEntity() {
	
	$this->m_user_prefs->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));
	$this->m_user_prefs->set($json);
	$this->m_user_prefs->update();
	
	die (print (json_encode($this->m_user_prefs->get())));
  }
}
//EOF