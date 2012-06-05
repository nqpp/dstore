<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_handler {

  var $ci;
  private $user = false;
  private $config;
  private $vars = false;
  private $userID = false;
  
  function __construct() {

    $this->ci =& get_instance();
    $this->ci->load->library('email');
  }

  // initialise
  function initialise($row) {

    if (!is_object($row)) throw new Exception('No variables supplied to email handler');

    if (isset($row->userID))
            $this->userID = $row->userID;

    if (!$this->user)
            $this->user = $this->user();

    if (!$this->config) {
      $this->config = $this->config();
      $this->ci->email->initialize($this->config);
    }
    

    foreach ($this->vars() as $v=>$r) {
      $this->$v = false;

      if (isset($row->$v) AND $row->$v) {
        $this->$v = $row->$v;
      }
      elseif ($r) { // not set but required
        throw new Exception ("$v is required but not set.");
      }
    }

  }

  // get user detail 
  private function user() {

    $this->ci->load->model('m_admin','admin');
    
    if ($this->userID) {
      $this->ci->admin->id = $this->userID; // newsletter cron user
    }
    else {
      $this->ci->admin->id = User::$id;
    }
    
    return $this->ci->admin->get();
  }

  // set email library config vars
  function config() {

    if (!$this->user) throw new Exception ('User data not set');
    if (!$this->user->smtp_pass) throw new Exception ('User SMTP password not set');

    return array(
      'mailtype'=>'html',
      'newline'=>"\r\n",
      'protocol'=>'smtp',
      'smtp_host'=>'ssl://smtp.gmail.com',
      'smtp_user'=>$this->user->email_address,
      'smtp_pass'=>$this->user->smtp_pass,
      'smtp_port'=>'465',
      'wrapchars'=>100
    );
  }

  /// email message vars and whether required
  private function vars() {
 
    return array(
      'email_from'=>true,
      'email_name'=>true,
      'email_to'=>true,
      'email_cc'=>false,
      'email_bcc'=>false,
      'subject'=>true,
      'message'=>true,
      'alt_message'=>false,
      'attachments'=>false,
      'priority'=>false
    );

  }

  // set properties, add attachments and send
  function send() {
    $this->ci->email->to($this->email_to);
    $this->ci->email->from($this->email_from, $this->email_name);
    $this->ci->email->subject($this->subject);
    $this->ci->email->message($this->message);

    if ($this->email_cc)
            $this->ci->email->cc($this->email_cc);

    if ($this->email_bcc)
            $this->ci->email->bcc($this->email_bcc);

    if ($this->alt_message)
            $this->ci->email->set_alt_message($this->alt_message);

    if ($this->attachments and count($this->attachments)) {
      foreach ($this->attachments as $a) {
        $this->ci->email->attach($a);
      }
    }

    /* DEMO VERSION - Email send disabled */
    return;
    
    if (!$this->ci->email->send()) {
      log_message('error',$this->ci->email->print_debugger());
      throw new Exception('Email not sent. Errors have been logged');
    }
  }


}
// EOF