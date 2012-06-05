<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'/core/Unsecure.php');

class Request_login extends Unsecure {

  function __construct() {
    parent::__construct();
//    $this->load->model('m_users');
  }
  
  function renderHTML() {
	
//	$this->check_https();
    $this->load->view('system/request_login');
	
  }
  
}
//EOF