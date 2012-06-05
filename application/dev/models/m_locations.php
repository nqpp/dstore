<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_locations extends MM_Model {

  function __construct() {
	$this->pk = 'locationID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
	  'zonesID',
	  'postcode',
	  'suburb'
    );
  }
  
  function fetchJoined() {
	
	$this->db->select('zones.code as zone');
	$this->db->join('zones', 'zoneID = zonesID', 'left outer');
	return $this->fetch();
  }
  
  function fetchJoinedJSON() {
	return json_encode($this->fetchJoined());
  }
  
  function getJoined() {
	
	$this->db->select('zones.code as zone');
	$this->db->join('zones', 'zoneID = zonesID', 'left outer');
	return $this->get();
  }
  
  function getJoinedJSON() {
	return json_encode($this->getJoined());
  }
  
}