<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_images extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_product_metas');
  }
  
  function renderJSONDelete() {
	
	$this->m_product_metas->id = $this->entityID;
	$this->m_product_metas->deleteImage();
	
  }
  
  function formJSONNew() {
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_product_metas->$k = $v;
	}

	$this->m_product_metas->add();
	print $this->m_product_metas->imageJSON();
	
  }
  
  function formJSONEntity() {
	
	$this->m_product_metas->id = $this->entityID;
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_product_metas->{$k} = $v;
	}

	$this->m_product_metas->update();
	print $this->m_product_metas->imageJSON();
	
  }
  
}