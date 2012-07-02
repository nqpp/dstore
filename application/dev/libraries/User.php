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
	
	return $this->userID();
	
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
  
  function czone($czone = false) {
	
//	if ($this->usePseudoMode()) return pseudoCzone($czone);
	
	if ($czone) $this->setField('czone', $czone);
	return $this->session->userdata('user_czone');
	
  }
  
//  function pseudoCzone($pseudoCzone = false) {
//	
//	if ($pseudoCzone) $this->setField('pseudoCzone', $pseudoCzone);
//	return $this->session->userdata('user_pseudoCzone');
//	
//  }
  
  function postcode($postcode = false) {
	
	if ($postcode) $this->setField('postcode', $postcode);
	return $this->session->userdata('user_postcode');
	
  }
  
  function suburb($suburb = false) {
	
	if ($suburb) $this->setField('suburb', $suburb);
	return $this->session->userdata('user_suburb');
	
  }
  
  function state($state = false) {
	
	if ($state) $this->setField('state', $state);
	return $this->session->userdata('user_state');
	
  }
  
  function alladdresses($alladdresses = false) {
	
	if ($alladdresses) $this->setField('alladdresses', $alladdresses);
	return $this->session->userdata('user_alladdresses');
	
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