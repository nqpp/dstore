<?php

class Admin_dashboard extends MM_Controller {

  function __construct() {
    parent::__construct();
  }

  function renderHTML() {
    
    $this->load->vars('content',$this->load->view('admin_dashboard','', true));

  }



}
// EOF