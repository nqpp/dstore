<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_metas extends MM_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_contact_metas');
  }
  
  function renderJSONDelete() {
	
	$this->m_contact_metas->id = $this->entityID;
	$this->m_contact_metas->delete();
	
  }
  
  function formJSONEntity() {
	
	$this->m_contact_metas->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_contact_metas->{$k} = $v;
	}

	$this->m_contact_metas->update();
	$row = $this->m_contact_metas->get();
	
	print json_encode($row);
	
  }
  
  function formJSONNew() {

	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_contact_metas->{$k} = $v;
	}

	$this->m_contact_metas->add();
	$row = $this->m_contact_metas->get();

	print json_encode($row);
	
  }
  
}
// EOF