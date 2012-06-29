<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'models/m_user_metas.php');

class M_contact_metas extends M_user_metas {
  
  public $userIDs = array();
  
  function __construct() {
    parent::__construct();
	$this->model = 'userMetas';
  }
  
  function deleteContact() {
	$this->deleteUser();
  }
  
  function fetchUsersMetasGrouped() {
	
	if (!count($this->userIDs)) return array();
	
	$this->index = 'usersID';
	$this->db->where_in('usersID',$this->userIDs);
	return $this->fetchGrouped();
	
  }
  
  function fetchUsersMetasGroupedJSON() {
	return json_encode($this->fetchUsersMetasGrouped());
  }
  
}
// EOF