<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_addresses extends MM_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_client_addresses');
  }
  
  function renderJSONDelete() {
	
	$this->m_client_addresses->id = $this->entityID;
	$this->m_client_addresses->delete();
	
  }
  
  function formJSONEntity() {
	
	$this->m_client_addresses->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_client_addresses->{$k} = $v;
	}

	$this->m_client_addresses->update();
	$row = $this->m_client_addresses->get();

	print json_encode($row);
	
  }
  
  function formJSONNew() {

	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_client_addresses->{$k} = $v;
	}

	$this->m_client_addresses->add();
	$row = $this->m_client_addresses->get();
	
	print json_encode($row);
	
  }
  
}
// EOF