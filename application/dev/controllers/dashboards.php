<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboards extends MM_Controller {

  function __construct() {
    parent::__construct();
  }

  function renderHTMLAdmin() {
    
    $this->load->vars('content',$this->load->view('dashboards/admin','', true));

  }

  function renderHTMLManager() {
    
    $this->load->vars('content',$this->load->view('dashboards/manager','', true));

  }

  function renderHTMLClient() {
    
    $this->load->vars('content',$this->load->view('dashboards/client','', true));

  }



}
// EOF