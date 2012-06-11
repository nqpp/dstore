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
	
	$this->load->vars('content',$this->load->view('system/forgot_password','',true));
    $this->pageTemplate('page_dialog');
	
  }
  
  function renderHTMLSuccess() {
	
	$this->load->vars('content',$this->load->view('system/forgot_password_success','',true));
    $this->pageTemplate('page_dialog');
  }
  
  function formHTMLNew() {
	
    $this->pageTemplate('page_dialog');
	$user = $this->m_users->reset_password();
	$message = $this->load->view('system/forgot_password_message',$user,true);
	
	$this->m_metas->schemaName = 'systemEmail';
	$sysEmails = $this->m_metas->fetchKVPairObj();
	
	$this->m_metas->schemaName = 'systemEmailConfig';
	$this->m_emails->email_config = $this->m_metas->fetchKVPairObj();
	
	$this->m_emails->to = $user->email;
	$this->m_emails->bcc = $sysEmails->bcc;
	$this->m_emails->from = $sysEmails->from;
	$this->m_emails->name = $sysEmails->name;
	$this->m_emails->subject = 'DStore Password Reset';
	$this->m_emails->message = $message;
//	$this->m_emails->altmessage = $this->makePlainText($message);
	
	$this->m_emails->add();
	$this->m_emails->send();
//print_r($user);	
	
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