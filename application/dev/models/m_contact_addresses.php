<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/m_user_addresses.php');

class M_contact_addresses extends M_user_addresses {
  
  function __construct() {
    parent::__construct();
	$this->model = 'userAddresses';
  }
  
  function deleteContact() {
	$this->deleteUser();
  }
  
//  function fetchJSON() {
//	$result = $this->fetch();
//	return json_encode($result);
//  }
  
}
// EOF