<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Change_view extends MM_controller {

  function __construct() {
    parent::__construct();
  }

  function renderHTMLAdmin() {
	
	$this->user->pseudoMode('admin');
	$this->user->pseudoAdminGroup(1);
	die (header("Location:dashboards.html?admin"));
  
  }
  
  function renderHTMLManager() {
	
	$this->user->pseudoMode('manager');
	$this->user->pseudoAdminGroup(2);
	die (header("Location:dashboards.html?manager"));
	
  }
  
  function renderHTMLClient() {
	
	$this->user->pseudoMode('client');
	$this->user->pseudoAdminGroup(4);
//	$this->user->zoneID(12);
	die (header("Location:dashboards.html?client"));
	
  }
  
}