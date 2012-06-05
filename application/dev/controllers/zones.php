<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zones extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_zones');
    $this->load->model('m_supplier_freights');
    $this->load->model('m_suppliers');
  }
  
  function renderHTML() {

	$this->load->vars('zonesJSON', $this->m_zones->fetchJSON());
	$this->load->vars('js_tpl_list', $this->load->view('zones/js_tpl_list','',true));
	$this->load->vars('content', $this->load->view('zones/list', '', true));

	$this->jsFiles('/scripts/zone-list.js');
	  
  }
  
  
//  function renderHTMLEntity() {
//	
//    $this->m_zones->id = $this->entityID;
//
//	$this->load->vars('zoneJSON', $this->m_zones->getJSON());
//	$this->load->vars('js_tpl_entity', $this->load->view('zones/js_tpl_entity','',true));
//	$this->load->vars('content', $this->load->view('zones/entity', '', true));
//
//	$this->jsFiles('/scripts/zone-entity.js');
//  }
  
  function renderJSONDelete() {
	
    $this->m_zones->id = $this->entityID;
    $this->m_supplier_freights->zonesID = $this->entityID;

	$this->m_supplier_freights->deleteZone();
	$this->m_zones->delete();
	
  }
  
  function formJSONEntity() {
	
    $this->m_zones->id = $this->entityID;
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_zones->{$k} = $v;
	}

	$this->m_zones->update();
	$row = $this->m_zones->get();
	print json_encode($row);
	
  }
  
  function formJSONNew() {
	
	$result = array();
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_zones->$k = $v;
	}

	$this->m_zones->add();
	$row = $this->m_zones->get();

	$this->m_supplier_freights->zonesID = $this->m_zones->id;
	$this->m_supplier_freights->suppliers = $this->m_suppliers->fetchIndexed();
	$this->m_supplier_freights->addZone();

	print json_encode($row);
	
  }
  
}