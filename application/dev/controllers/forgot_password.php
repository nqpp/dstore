<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'/core/Unsecure.php');

class Forgot_password extends Unsecure {

  function __construct() {
    parent::__construct();
    $this->load->model('m_users');
  }
  
  function renderHTML() {
	
//	$this->check_https();
    $this->load->view('system/forgot_password');
	
  }
  
}
//EOF