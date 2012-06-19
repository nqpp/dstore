<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_orders extends MM_Model {

  function __construct() {
	$this->pk = 'orderID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'usersID',
      'createdAt',
	  'status'
    );
  }
  
  function fetch() {
	
	$this->setSort('createdAt DESC');
	return parent::fetch();
	
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