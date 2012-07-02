<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user_addresses extends MM_Model {

  function __construct() {
	$this->pk = 'userAddressID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'usersID',
      'locationsID',
      'type',
      'address',
      'city',
      'state',
      'postcode',
	  'sort'
    );
  }
  
  function getLocationsJoined() {
	
	$this->db->select('locations.*');
	$this->db->join('locations', 'locationID = locationsID', 'left outer');
	
	return $this->fetch();
	
  }
  
  function getLocationJoined() {
	
	$result = $this->getLocationsJoined();
	
	if (!count($result)) return false;
	
	return reset($result);
  }
  
  function deleteUser() {
	
    if (!$this->usersID) throw new Exception('No userID supplied.');
	
	$this->db->where('usersID',$this->usersID);
	$this->db->delete('userAddresses');
	
  }
  
}