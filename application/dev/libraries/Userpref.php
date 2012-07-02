<?php

class Userpref {
  
//  private $model;
  private $prefs = array();
  
  function __construct() {
//    $ci =& get_instance();
//	$ci->load->model('m_user_prefs');
//	$this->model = $ci->m_user_prefs;
  }
  
//  function init($userID) {
  function init($pref) {
	$this->prefs = $pref;
//	$this->model->userID = $userID;
//	$this->model->index = 'schemaName';
//	$this->prefs = $this->model->fetchJoined();
  }
  
  
  
  // general getter. 
  // if a method with same name as key exists, return result of call to that.
  function get($key) {
	
	if (!isset($this->prefs[$key])) return false;
	
	if (method_exists($this,$key)) {
	  return $this->$key();
	}
	
	return $this->prefs[$key];
  }
  
  function orderStatusFilter() {
	
	if (!isset($this->prefs['orderStatusFilter'])) return array();
	
	$result = false;
//	$result = array();
	foreach ($this->prefs['orderStatusFilter'] as $p) {
	  $result[$p->metasID] = $p;
//	  if ($p->metaValue == 1) $result[$p->metaKey] = $p;
	}
	return $result;
  }
  
}
//EOF