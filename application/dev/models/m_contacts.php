<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/m_users.php');

class M_contacts extends M_users {

  function __construct() {
    parent::__construct();
	$this->adminGroup = 32;
	$this->model = 'users';
  }
  
  function get() {
	$row = parent::get();
	$row->pwSet = trim($row->passwd) != '' ? true: false;
	unset($row->passwd);
	return $row;
  }
  
}
// EOF