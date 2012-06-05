<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user_addresses extends MM_Model {

  function __construct() {
	$this->pk = 'userAddressID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
      'usersID',
      'type',
      'address',
      'city',
      'state',
      'postcode',
	  'sort'
    );
  }
  
  function getLocationJoined() {
	
	$this->db->select('locations.*');
	$this->db->join('locations', 'locations.postcode = userAddresses.postcode', 'left outer');
	
	$result = $this->fetch();
	
	if (!count($result)) return false;
	
	return reset($result);
  }
  
  function deleteUser() {
	
    if (!$this->usersID) throw new Exception('No userID supplied.');
	
	$this->db->where('usersID',$this->usersID);
	$this->db->delete('userAddresses');
	
  }
  
}