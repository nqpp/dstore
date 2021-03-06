<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH.'/core/Unsecure.php');

class Forgot_password extends Unsecure {

  function __construct() {
    parent::__construct();
    $this->load->model('m_users');
    $this->load->model('m_emails');
    $this->load->model('m_metas');
  }
  
  function renderHTML() {
	
	$this->load->vars('content',$this->load->view('forgot_password/entity','',true));
    $this->pageTemplate('page_dialog');
	
  }
  
  function renderHTMLSuccess() {
	
	$this->load->vars('content',$this->load->view('forgot_password/success','',true));
    $this->pageTemplate('page_dialog');
  }
  
  function formHTMLNew() {
	
    $this->pageTemplate('page_dialog');
    $this->load->library('email_handler');
	
	$user = $this->m_users->reset_password();
	$message = $this->load->view('forgot_password/message',$user,true);
	
	$this->m_metas->schemaName = 'systemEmail';
	$sysEmails = $this->m_metas->fetchKVPairObj();
	$this->m_emails->set($sysEmails); // set sender info for email
	
	$this->m_emails->to = $user->email;
	$this->m_emails->subject = 'DStore Password Reset';
	$this->m_emails->message = $message;
//	$this->m_emails->altmessage = $this->makePlainText($message);
	$this->m_emails->add();
	$email = $this->m_emails->get();
	
	$this->m_metas->schemaName = 'systemEmailConfig';
	$this->email_handler->set($this->m_metas->fetchKVPairObj());

	$this->email_handler->set($email); // set email data
	$this->email_handler->send();
	
	$this->m_emails->saveSent();
	
	
	die(header("Location:forgot_password.html?success"));
	
  }
  
  private function makePlainText($html) {
  print $html; 
	
	$regex = "|<body>(.*)|";
	$preg = preg_match_all($regex,$html,$match);
  print_r($match); 
  exit;
  }
  
}
//EOF