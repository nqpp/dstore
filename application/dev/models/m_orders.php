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
  
  function joinWithClient() {
	
	$this->db->select("DATE_FORMAT(createdAt, '%d %b %Y') as createdDate",false);
	$this->db->select("LPAD(orderID, 5, '0') as orderNumber",false);
	
	$this->db->select('firstName, lastName');
	$this->db->select('clients.name');
	$this->db->join('users', 'userID = orders.usersID', 'left outer');
	$this->db->join('clientContacts', 'orders.usersID = clientContacts.usersID', 'left outer');
	$this->db->join('clients', 'clientID = clientsID', 'left outer');
	
  }
  
  function fetchWithClient() {
	$this->joinWithClient();
	return $this->fetch();
  }
  
  function fetchIndexedWithClient() {
	$this->joinWithClient();
	return $this->fetchIndexed();
  }
  
  function fetchJoinedUserIndexed() {
	
	$this->db->select('users.*');
	$this->db->join('users','userID = orders.usersID');
	$this->db->join('clientContacts','userID = clientContacts.usersID');
	$this->db->select('clients.*');
	$this->db->join('clients','clientID = clientsID');
	
	return $this->fetchIndexed();
	
  }
  
  function add() {
	
	$this->usersID = $this->user->userID();
	$this->createdAt = date('Y-m-d H:i:s');
	$this->status = 'new';
	parent::add();
	
  }
 
  // filter on statuses held in user prefs
  function filter() {
	
	$pref = $this->userpref->get('orderStatusFilter');
	$s = array();

	if (!count($pref)) return;
	
	foreach ($pref as $p) {
	  if ($p->preference == "1") {
		$s[] = $p->metaKey;
	  }
	}
	
	if (!count($s)) return;

//print_r($s); return;	
	$this->db->where_in('status',$s);
  }
  
}
//EOF