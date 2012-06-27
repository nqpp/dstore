<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_suppliers');
    $this->load->model('m_supplier_metas');
    $this->load->model('m_supplier_addresses');
  }
  
  function renderHTML() {
	$this->m_suppliers->setSort('name');
	$this->load->vars('suppliers', $this->m_suppliers->fetch());

	$this->m_supplier_metas->schemaName = "phone";
	$this->m_supplier_metas->index = "suppliersID";
	$this->load->vars('phones', $this->m_supplier_metas->fetchGrouped());
	
	$this->m_supplier_addresses->index = "suppliersID";
	$this->load->vars('addresses', $this->m_supplier_addresses->fetchGrouped());

	$this->load->vars('content', $this->load->view('suppliers/list', '', true));

//	$this->jsFiles('/scripts/jquery.dragsort-0.5.1.js');
//	$this->jsFiles('/scripts/supplier-list.js');
	
  }
  
  function renderHTMLEntity() {
	$this->m_suppliers->id = $this->entityID;
	$this->m_supplier_addresses->suppliersID = $this->entityID;
	$this->m_supplier_metas->suppliersID = $this->entityID;
	
	$this->m_suppliers->setSort('name');
	$this->load->vars('suppliers', $this->m_suppliers->fetch());
	$this->load->vars('supplierJSON', $this->m_suppliers->getJSON());
	$this->load->vars('addressJSON', $this->m_supplier_addresses->fetchJSON());
	$this->load->vars('phoneJSON', $this->m_supplier_metas->fetchPhoneJSON());

	$this->m_metas->schemaName = 'phoneTypes';
	$this->load->vars('phoneTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'addressTypes';
	$this->load->vars('addressTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'states';
	$this->load->vars('states', $this->m_metas->fetch());

	$this->load->vars('js_tpl_entity', $this->load->view('suppliers/js_tpl_entity','',true));
	$this->load->vars('js_addresslist', $this->load->view('suppliers/js_tpl_addresslist','',true));
	$this->load->vars('js_phonelist', $this->load->view('contacts/js_tpl_phonelist','',true));
	$this->load->vars('js_tpl_typeahead_list', $this->load->view('contacts/js_tpl_typeahead_list','',true));
	$this->load->vars('content',$this->load->view('suppliers/entity','',true));

	$this->jsFiles('/scripts/location-lookup.js');
	$this->jsFiles('/scripts/phone-view.js');
	$this->jsFiles('/scripts/address-view.js');
	$this->jsFiles('/scripts/supplier-entity.js');
	
  }
  
  function renderHTMLDelete() {

	$this->m_suppliers->id = $this->entityID;
    $this->m_supplier_metas->suppliersID = $this->entityID;
    $this->m_supplier_addresses->suppliersID = $this->entityID;

	$this->m_supplier_metas->deleteSupplier();
	$this->m_supplier_addresses->deleteSupplier();
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