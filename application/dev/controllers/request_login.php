<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'/core/Unsecure.php');

class Request_login extends Unsecure {

  function __construct() {
    parent::__construct();
  }
  
  function renderHTML() {
	
	$this->load->vars('content',$this->load->view('system/request_login','',true));
    $this->pageTemplate('page_dialog');
	
  }
  
}
//EOF