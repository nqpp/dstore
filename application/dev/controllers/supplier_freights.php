<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_freights extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_supplier_freights');
  }

  function formJSONEntity() {
	
	$this->m_supplier_freights->id = $this->entityID;
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_supplier_freights->{$k} = $v;
	}

	$this->m_supplier_freights->update();
	$row = $this->m_supplier_freights->getZones();
	print json_encode($row);
	
  }
}