<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/do_clients.php');

class M_clients extends MM_Model {
  
  function __construct() {
	
	$this->pk = 'clientID';
	$this->fields = $this->fields();
    parent::__construct();
	
  }
  
  // db field names
  function fields() {
    return array(
      'name',
      'billingName',
      'abn'
    );
	
  }
  
  function fetchDO() {
	
    $result = parent::fetch();
	
	$doResult = array();
	foreach ($result as $row) {
	  $doResult[] = new DO_clients($row);
	}
	
	return $doResult;
  }
  
  function fetchJSON() {
	
    $result = parent::fetch();
	return json_encode($result);
  }
  
  function getDO() {
	
	$row = parent::get();
	return new DO_clients($row);
	
  }
  
  function getJSON() {
	
	$row = parent::get();
	return json_encode($row);

  }
  
  function add() {
	
    $this->postToVar();
	
	if ($this->name and !$this->billingName) {
	  $this->billingName = $this->name;
	}
	
	parent::add();
	
  }
  
}
// EOF