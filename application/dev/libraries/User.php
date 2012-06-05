<?php

class User {
  
  private $session;

  function __construct() {

    $ci =& get_instance();
	$this->session = $ci->session;

  }
  
  function set($data) {
	
	if (! is_object($data)) throw new Exception('Argument is not object.');
	
	foreach (get_object_vars($data) as $prop=>$val) {
	  if (method_exists($this,$prop)) {
		$this->$prop($val);
	  }
	}

  }
  
  function id() {
	
	return $this->session->userdata('user_userID');
	
  }
  
  function userID($userID = false) {
	
	if ($userID) $this->setField('userID', $userID);
	return $this->session->userdata('user_userID');
	
  }
  
  function firstName($firstName = false) {
	
	if ($firstName) $this->setField('firstName', $firstName);
	return $this->session->userdata('user_firstName');
	
  }
  
  function lastName($lastName = false) {
	
	if ($lastName) $this->setField('lastName', $lastName);
	return $this->session->userdata('user_lastName');
	
  }
  
  function fullName() {
	
	return $this->session->userdata('user_firstName').' '.$this->session->userdata('user_lastName');
	
  }
  
  function email($email = false) {
	
	if ($email) $this->setField('email', $email);
	return $this->session->userdata('user_email');
	
  }
  
  function adminGroup($adminGroup = false) {
	
	if ($adminGroup) $this->setField('adminGroup', $adminGroup);
	if ($adminGroup == 1) $this->pseudoMode('admin');
	return $this->session->userdata('user_adminGroup');
	
  }
  
  function pseudoAdminGroup($pseudoAdminGroup = false) {
	
	if ($pseudoAdminGroup) $this->setField('pseudoAdminGroup', $pseudoAdminGroup);
	
	$pseudoAdminGroup = $this->session->userdata('user_pseudoAdminGroup');
	if ($pseudoAdminGroup) return $pseudoAdminGroup;
	return $this->adminGroup();
	
  }
  
  function allowedPages($allowedPages = false) {
	
	if ($this->usePseudoMode()) return pseudoAllowedPages($pseudoAllowedPages);
	
	if ($allowedPages) $this->setField('allowedPages', $allowedPages);
	return $this->session->userdata('user_allowedPages');
	
  }
  
  function pseudoAllowedPages($pseudoAllowedPages = false) {
	
	if ($pseudoAllowedPages) $this->setField('pseudoAllowedPages', $pseudoAllowedPages);
	return $this->session->userdata('user_pseudoAllowedPages');
	
  }
  
  function zoneID($zoneID = false) {
	
	if ($this->usePseudoMode()) return pseudoZoneID($zoneID);
	
	if ($zoneID) $this->setField('zoneID', $zoneID);
	return $this->session->userdata('user_zoneID');
	
  }
  
  function pseudoZoneID($pseudoZoneID = false) {
	
	if ($pseudoZoneID) $this->setField('pseudoZoneID', $pseudoZoneID);
	return $this->session->userdata('user_pseudoZoneID');
	
  }
  
  function pseudoMode($pseudoMode = false) {

	if (!$this->adminGroup() & 1) {
	  $this->usePseudoMode(false);
	  return false;
	}
	
	if ($pseudoMode) {
	  $this->setField('pseudoMode',$pseudoMode);
	  
	  if ($pseudoMode == 'admin') {
		$this->usePseudoMode(false);
	  }
	  else {
		$this->usePseudoMode(true);
	  }
	}
	return $this->session->userdata('user_pseudoMode');
	
  }
  
  function usePseudoMode($usePseudoMode = false) {
	
	if ($this->session->userdata('user_adminGroup') !== 1) return false;
	
	$this->setField('usePseudoMode', $usePseudoMode);
	return $this->session->userdata('user_usePseudoMode');
	
  }
  
  private function setField($field, $value) {
	
	$this->$field = $value;
	$this->session->set_userdata('user_'.$field,$value);
	
  }

  function kill() {
	
	$this->session->sess_destroy();

  }
  

}