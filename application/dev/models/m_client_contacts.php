<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_client_contacts extends MM_Model {

  function __construct() {
	$this->pk = 'clientContactID';
    parent::__construct();
	$this->adminGroup = 32;
  }
  
  function fields() {
	return array(
		'clientsID',
		'usersID',
		'sort'
	);
  }
  
  function fetch() {
	
	if (!$this->clientsID) throw new Exception('No client ID supplied.[m_client_contacts:fetch]');
	
	$this->db->select('users.*');
	$this->db->join('users', 'userID = usersID','left outer');
	return parent::fetch();
  }
  
  function fetchJSON() {

	$select = "(
	  SELECT metaValue 
	  FROM userMetas 
	  WHERE usersID = userID AND schemaName = 'employmentdata' AND metaKey = 'Job Title' 
	  GROUP By sort
	  ) as jobTitle, ";
	
	$select .= "(
	  SELECT metaValue 
	  FROM userMetas 
	  WHERE usersID = userID AND schemaName = 'employmentdata' AND metaKey = 'Department' 
	  GROUP By sort
	  ) as department, ";
	
	$this->db->select($select,false);
	
	$result = $this->fetch();
	return json_encode($result);

  }
  
  function getJoined() {
	
	if (!$this->clientsID && !$this->usersID) throw new Exception('Neither Client ID or User ID supplied.[m_client_contacts:getJoinmed]');
	
	if ($this->clientsID) return $this->getMany();
	if ($this->usersID) return $this->getOne();
	
  }
  
  function getFullJoin() {
	
	$this->db->select('clients.*');
	$this->db->join('clients', 'clientID = clientsID','left outer');
	
	$this->db->select('users.*');
	$this->db->join('users', 'userID = usersID','left outer');
	
	return $this->get();
  }
  
  // in a one to one join - users are 1 to 1 with this model
  private function getOne() {
	
	$this->db->select('clients.*');
	$this->db->join('clients', 'clientID = clientsID','left outer');
	
	return reset(parent::fetch());
  }
  
  // in a one to many join - clients are in a 1 to many with this model
  private function getMany() {
	
	return $this->fetch();
	
  }
  
}
// EOF