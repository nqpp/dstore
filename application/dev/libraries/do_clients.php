<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/DataObject.php');

class DO_clients extends DataObject {

  function clientID($clientID = false) {
	
	if($clientID) $this->row->clientID = $clientID;
	return $this->row->clientID;
	
  }
  
  function name($name = false) {
	
	if($name) $this->row->name = $name;
	return $this->row->name;
	
  }
  
  function billingName($billingName = false) {
	
	if($billingName) $this->row->billingName = $billingName;
	return $this->row->billingName;
	
  }
  
  function abn($abn = false) {
	
	if($abn) $this->row->abn = $abn;
	return $this->row->abn;
	
  }
  
  
}
// EOF