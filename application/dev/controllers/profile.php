<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_contacts');
    $this->load->model('m_contact_metas');
  }
  
  function renderHTML() {
	
	$this->m_contacts->userID = $this->user->userID();
	$this->m_contact_metas->usersID = $this->user->userID();
	$this->load->vars('contact',$this->m_contacts->get());
  }

}