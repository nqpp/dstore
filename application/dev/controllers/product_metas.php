<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_metas extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_product_metas');
  }
  
  function renderJSONEntity() {
	
	$this->m_product_metas->id = $this->entityID;
	$row = $this->m_product_metas->get();
	print json_encode($row);
	  
  }
  
  function renderJSONDelete() {
	
	$this->m_product_metas->id = $this->entityID;
	$this->m_product_metas->delete();
	
  }
  
  function formJSONNew() {
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_product_metas->$k = $v;
	}

	$this->m_product_metas->add();
	$row = $this->m_product_metas->get();
	print json_encode($row);
	
  }
  
  function formJSONEntity() {
	
	$this->m_product_metas->id = $this->entityID;
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_product_metas->{$k} = $v;
	}

	$this->m_product_metas->update();
	$row = $this->m_product_metas->get();
	print json_encode($row);
	
  }
 
}