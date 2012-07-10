<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_addresses extends MM_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_supplier_addresses');
  }
  
  function renderJSONDelete() {
	
	$this->m_supplier_addresses->id = $this->entityID;
	$this->m_supplier_addresses->delete();
	
  }
  
  function formJSONEntity() {
	
	$this->m_supplier_addresses->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_supplier_addresses->{$k} = $v;
	}

	$this->m_supplier_addresses->update();
	$row = $this->m_supplier_addresses->get();

	print json_encode($row);
	
  }
  
  function formJSONNew() {

	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_supplier_addresses->{$k} = $v;
	}

	$this->m_supplier_addresses->add();
	$row = $this->m_supplier_addresses->get();
	
	print json_encode($row);
	
  }
  
}
// EOF