<?php

class Userpref {
  
  private $prefs = array();
  
  function __construct() {

  }
  
  function init($pref) {
	$this->prefs = $pref;
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
	
//	$result = false;
//	foreach ($this->prefs['orderStatusFilter'] as $p) {
//	  $result[$p->metasID] = $p;
////	  if ($p->metaValue == 1) $result[$p->metaKey] = $p;
//	}
	
	return $this->prefs['orderStatusFilter'];
//	return $result;
  }
  
}
//EOF