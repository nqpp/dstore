<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_suppliers');
    $this->load->model('m_supplier_freights');
  }
  
  function renderHTML() {
	
	$this->load->vars('suppliersJSON', $this->m_suppliers->fetchJSON());
	$this->load->vars('js_tpl_list', $this->load->view('suppliers/js_tpl_list','',true));
	$this->load->vars('content', $this->load->view('suppliers/list', '', true));

	$this->jsFiles('/scripts/jquery.dragsort-0.5.1.js');
	$this->jsFiles('/scripts/supplier-list.js');
	
  }
  
  function renderHTMLEntity() {
	
//    $this->load->model('m_supplier_freights');
	
	$this->m_suppliers->id = $this->entityID;
	$this->m_supplier_freights->suppliersID = $this->entityID;
	
	$this->load->vars('supplierJSON', $this->m_suppliers->getJSON());
	$this->load->vars('supplierFreightsJSON', json_encode($this->m_supplier_freights->fetchJoined()));

	$this->load->vars('js_tpl_entity', $this->load->view('suppliers/js_tpl_entity','',true));
	$this->load->vars('js_tpl_supplier_freights', $this->load->view('suppliers/js_tpl_supplier_freights','',true));
	$this->load->vars('content',$this->load->view('suppliers/entity','',true));

	$this->jsFiles('/scripts/supplier-entity.js');
	
  }
  
  function renderHTMLDelete() {

	$this->m_suppliers->id = $this->entityID;
    $this->m_supplier_freights->suppliersID = $this->entityID;

	$this->m_supplier_freights->deleteSupplier();
	$this->m_suppliers->delete();
	
	die(header("Location:/suppliers.html"));
  }
  
  function formJSONNew() {
	
    $this->load->model('m_zones');
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_suppliers->{$k} = $v;
	}

	$this->m_suppliers->add();
	$row = $this->m_suppliers->get();
	
	$this->m_supplier_freights->suppliersID = $this->m_suppliers->id;
	$this->m_supplier_freights->zones = $this->m_zones->fetchIndexed();
	$this->m_supplier_freights->addSupplier();

	print json_encode($row);
	
  }

  function formJSONEntity() {
	
	$this->m_suppliers->id = $this->entityID;

	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_suppliers->{$k} = $v;
	}

	$this->m_suppliers->update();
	$row = $this->m_suppliers->get();
	print json_encode($row);
	
  }
  
  function renderJSONDelete() {
	
	$this->m_suppliers->id = $this->entityID;
    $this->m_supplier_freights->suppliersID = $this->entityID;

	$this->m_supplier_freights->deleteSupplier();
	$this->m_suppliers->delete();
	
  }

}