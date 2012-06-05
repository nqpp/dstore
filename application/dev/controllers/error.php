<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'/core/Unsecure.php');

class Error extends Unsecure {

  function __construct() {
    parent::__construct();
  }
  
  function renderHTML400() {
	
	header("HTTP/1.0 400 Bad Request");
    $this->load->vars('content',$this->load->view('errors/400','',true));
	
  }

  function renderHTML401() {
	
	header("HTTP/1.0 401 Unauthorized");
    $this->load->vars('content',$this->load->view('errors/401','',true));
	
  }

  function renderHTML403() {
	
	header("HTTP/1.0 403 Forbidden");
    $this->load->vars('content',$this->load->view('errors/403','',true));
	
  }

  function renderHTML404() {
	
	header("HTTP/1.0 404 Not Found");
    $this->load->vars('content',$this->load->view('errors/404','',true));
	
  }

  
}
// EOF