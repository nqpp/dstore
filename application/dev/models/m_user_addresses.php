<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

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

  function getLocationsIndexedJoined() {

	$this->db->select('locations.*');
	$this->db->join('locations', 'locationID = locationsID', 'left outer');

	return $this->fetchIndexed();
  }

  function getLocationJoined() {

	$result = $this->getLocationsIndexedJoined();

	if (!count($result))
	  return false;

	return reset($result);
  }

  function deleteUser() {

	if (!$this->usersID)
	  throw new Exception('No userID supplied.');

	$this->db->where('usersID', $this->usersID);
	$this->db->delete('userAddresses');
  }

	function fetchForSelect() {
		
		$this->usersID = $this->user->id();
		$this->type = 'Delivery';

		$results = array();
		$rawResults = $this->fetch();
		
		foreach($rawResults as $row) {
			
			$results[] = array(
				'userAddressID'=>$row->userAddressID,
				'address'=>$row->address . ', ' . $row->city . ' ' . $row->state . ' ' . $row->postcode
			);
		}
		
		return $results;
	}

  function fetchForSelectJSON() {

	return json_encode($this->fetchForSelect());
  }

}