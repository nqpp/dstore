<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_orders extends MM_Model {

  function __construct() {
	$this->pk = 'orderID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'usersID',
      'createdAt',
	  'status',
	  'purchaseOrder'
    );
  }
  
  function fetch() {
	
	$this->setSort('createdAt DESC');
	return parent::fetch();
	
  }
  
  function fetchIndexedWithClient() {

	$this->db->select('firstName, lastName');
	$this->db->select('clients.name');
	$this->db->join('users', 'userID = orders.usersID', 'left outer');
	$this->db->join('clientContacts', 'orders.usersID = clientContacts.usersID', 'left outer');
	$this->db->join('clients', 'clientID = clientsID', 'left outer');
	
	return $this->fetchIndexed();
  }
  
  function add() {
	
	$this->usersID = $this->user->userID();
	$this->createdAt = date('Y-m-d H:i:s');
	$this->status = 'new';
	parent::add();
	
  }
  
  function fetchJoinedUserIndexed() {
	
	$this->db->select('users.*');
	$this->db->join('users','userID = orders.usersID');
	
	$this->db->join('clientContacts','userID = clientContacts.usersID');
	
	$this->db->select('clients.*');
	$this->db->join('clients','clientID = clientsID');
	
	return $this->fetchIndexed();
	
  }
  
}
//EOF