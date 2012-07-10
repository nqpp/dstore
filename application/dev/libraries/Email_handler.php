<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Email_handler class
 * 
 * Pass in details of message as well as config for sending.
 * Allows validation of required items.
 * 
 * TODO: test on live server
 * 
 */
class Email_handler {

  private $email; // placeholder for ci email object
  private $nosend = false;  // control whether emails get sent
  
  function __construct() {

    $ci =& get_instance();
    $ci->load->library('email');
	$this->email = $ci->email;
	$this->defaults();
	
  }
  
  function defaults() {
	
	$this->smtp_port(465);
	$this->wrapchars('100');
	$this->mailtype('html');
	
  }

  function set($data) {
	
	if (! is_object($data)) throw new Exception('Argument is not object.');
	
	foreach (get_object_vars($data) as $prop=>$val) {
	  if (method_exists($this,$prop)) {
		$this->$prop($val);
	  }
	}

  }
  
  private function config() {
	
	$this->config = array();
	$this->config['mailtype'] = (string)$this->mailtype();
	$this->config['newline'] = "\r\n"; // !! IMPORTANT !! must be set this way or will not validate at gmail
	$this->config['wrapchars'] = $this->wrapchars();
	$this->config['protocol'] = $this->protocol();
	
	$this->{'send_'.$this->config['protocol']}();
	
	$this->email->initialize($this->config);
	
  }
  
  /*
   * config specific to smtp
   */
  private function send_smtp() {
	
	$this->config['smtp_host'] = $this->validate_smtp_host();
	$this->config['smtp_user'] = $this->validate_smtp_user();
	$this->config['smtp_pass'] = $this->validate_smtp_pass();
	$this->config['smtp_port'] = (int)$this->smtp_port();

  }
  
  /*
   * config specific to mail
   */
  private function send_mail() {
	
  }
  
  /*
   * config specific to sendmail
   */
  private function send_sendmail() {
	
	$this->config['mailpath'] = $this->mailpath();
	
  }

  /*
   * determine how message should be sent
   */
  private function sendtype($sendtype = false) {
	
	if ($sendtype !== false) $this->sendtype = $sendtype;
	return $this->sendtype;
	
  }
  
  /*
   * format for message. html or text
   */
  function mailtype($mailtype = false) {
	
	if ($mailtype !== false) $this->mailtype = $mailtype;
	return $this->mailtype;
	
  }
  
  /*
   * server path to sendmail
   */
  function mailpath($mailpath = false) {
	
	if ($mailpath !== false) $this->mailpath = $mailpath;
	return $this->mailpath;
	
  }
  
  function newline($newline = false) {
	
	if ($newline) $this->newline = $newline;
	return $this->newline;
	
  }
  
  function protocol($protocol = false) {
	
	if ($protocol) $this->protocol = $protocol;
	return $this->protocol;
	
  }
  
  function smtp_host($smtp_host = false) {
	
	if ($smtp_host) $this->smtp_host = $smtp_host;
	return $this->smtp_host;
	
  }
  
  function validate_smtp_host() {

	if (!$this->smtp_host()) {
      log_message('error','No SMTP Host supplied');
	  throw new Exception('No SMTP Host supplied');
	}
	return $this->smtp_host();
	
  }
  
  function smtp_user($smtp_user = false) {
	
	if ($smtp_user) $this->smtp_user = $smtp_user;
	return $this->smtp_user;
	
  }
  
  function validate_smtp_user() {

	if (!$this->smtp_user()) {
      log_message('error','No SMTP User supplied');
	  throw new Exception('No SMTP User supplied');
	}
	return $this->smtp_user();
	
  }
  
  function smtp_pass($smtp_pass = false) {
	
	if ($smtp_pass) $this->smtp_pass = $smtp_pass;
	return $this->smtp_pass;
	
  }
  
  function validate_smtp_pass() {

	if (!$this->smtp_pass()) {
      log_message('error','No SMTP Password supplied');
	  throw new Exception('No SMTP Password supplied');
	}
	return $this->smtp_pass();
	
  }
  
  function smtp_port($smtp_port = false) {
	
	if ($smtp_port) $this->smtp_port = $smtp_port;
	return $this->smtp_port;
	
  }
  
  function wrapchars($wrapchars = false) {
	
	if ($wrapchars) $this->wrapchars = $wrapchars;
	return $this->wrapchars;
	
  }
  
  function to($to = false) {
	
	if ($to) $this->to = $to;
	return $this->to;
	
  }
  
  function cc($cc = false) {
	
	if ($cc) $this->cc = $cc;
	return $this->cc;
	
  }
  
  function bcc($bcc = false) {
	
	if ($bcc) $this->bcc = $bcc;
	return $this->bcc;
	
  }
  
  function from($from = false) {
	
	if ($from) $this->from = $from;
	return $this->from;
	
  }
  
  function name($name = false) {
	
	if ($name) $this->name = $name;
	return $this->name;
	
  }
  
  function subject($subject = false) {
	
	if ($subject) $this->subject = $subject;
	return $this->subject;
	
  }
  
  function message($message = false) {
	
	if ($message) $this->message = $message;
	return $this->message;
	
  }
  
  function altmessage($altmessage = false) {
	
	if ($altmessage) $this->altmessage = $altmessage;
	return $this->altmessage;
	
  }
  
  function attachment($attachment = false) {
	
	if ($attachment) {
	  $this->attachments[] = $attachment;
	}
	if ($this->attachments && count($this->attachements)) {
	  return $this->attachments;
	}
	
	return false;
  }
  

  
  /*
   * fields that are required
   */
  function is_required() {
	return array(
      'from',
      'name',
      'to',
      'subject',
      'message'		
	);
  }
  
  /* 
   * validate fields for email send
   */
  function validate() {
	
	foreach ($this->is_required() as $prop) {
	  if (!$this->$prop()) {
		throw new Exception("$prop Field is required.");
	  }
	}
	
  }

  // set properties, add attachments and send
  function send($data = false) {
	
	if ($this->nosend) return;
	if ($data) {
	  $this->set($data);
	}

	$this->config();
	$this->validate();
	
    $this->email->to($this->to());
	
	if ($this->cc()) {
	  $this->email->cc($this->cc());	  
	}
	
	if ($this->bcc()) {
	  $this->email->bcc($this->bcc());	  
	}

    $this->email->from($this->from(),$this->name());
    $this->email->subject($this->subject());
    $this->email->message($this->message());
	
	if ($this->altmessage()) {
	  $this->email->altmessage($this->altmessage());	  
	}
	
    if ($this->attachments) {
      foreach ($this->attachments as $a) {
        $this->email->attach($a);
      }
    }

	if (!$this->email->send()) {
      log_message('error',$this->email->print_debugger());
      throw new Exception('Email not sent. Errors have been logged');
    }

  }
  
  function __get($name) {
	
	if (! isset($this->$name)) return false;
	return $this->$name;
	
  }

}
// EOF