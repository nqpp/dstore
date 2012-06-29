<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends MM_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_clients');
    $this->load->model('m_client_metas');
    $this->load->model('m_client_addresses');
    $this->load->model('m_client_contacts');
    $this->load->model('m_contact_metas');
  }
  
  
  function renderHTML() {

	$this->load->vars('clientsJSON', $this->m_clients->fetchJSON());
	$this->load->vars('js_tpl_list', $this->load->view('clients/js_tpl_list','',true));
	$this->load->vars('content',$this->load->view('clients/list', '', true));

	$this->jsFiles('/scripts/client-list.js');
	
  }
  
  function renderHTMLNew() {
	
	$this->load->vars('content',$this->load->view('clients/new', '', true));
	
  }
  
  function renderHTMLEntity() {
	
	$this->load->model('m_metas');
	
	$this->m_clients->id = $this->entityID;
	$this->m_client_addresses->clientsID = $this->entityID;
	$this->m_client_contacts->clientsID = $this->entityID;
	$this->m_client_metas->clientsID = $this->entityID;
	
	$this->m_client_contacts->clientsID = $this->m_clients->id;
	$this->m_client_contacts->index = 'usersID';
	$contacts = $this->m_client_contacts->fetchIndexed();

	$this->m_contact_metas->schemaName = 'phone';
	$this->m_contact_metas->userIDs = array_keys($contacts);
	$contactPhones = $this->m_contact_metas->fetchUsersMetasGroupedJSON();
	
	$this->load->vars('contactPhonesJSON',$contactPhones); 
	$this->load->vars('contactsJSON', $this->m_client_contacts->fetchJSON());

	$this->load->vars('clientJSON', $this->m_clients->getJSON());
	$this->load->vars('addressJSON', $this->m_client_addresses->fetchJSON());
	$this->load->vars('phoneJSON', $this->m_client_metas->fetchPhoneJSON());

	$this->m_metas->schemaName = 'phoneTypes';
	$this->load->vars('phoneTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'addressTypes';
	$this->load->vars('addressTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'states';
	$this->load->vars('states', $this->m_metas->fetch());

	$this->load->vars('js_tpl_entity', $this->load->view('clients/js_tpl_entity','',true));
	$this->load->vars('js_tpl_contactlist', $this->load->view('clients/js_tpl_contactlist','',true));
	$this->load->vars('js_tpl_addresslist', $this->load->view('clients/js_tpl_addresslist','',true));
	$this->load->vars('js_tpl_phonelist', $this->load->view('clients/js_tpl_phonelist','',true));
	$this->load->vars('js_tpl_contact_phonelist', $this->load->view('clients/js_tpl_contact_phonelist','',true));
	
	$this->load->vars('content',$this->load->view('clients/entity', '', true));
	$this->jsFiles('/scripts/client-entity.js');

  }
  
  function renderHTMLDelete() {
	
	$this->m_clients->id = $this->entityID;
	
	try {
	  $this->m_clients->delete();
	}
	catch (Exception $e) {
      $this->session->set_flashdata('msg',array('type'=>'error','text'=>$e->getMessage()));
	}
	
	die(header("Location:/clients.html"));
  }
  
  function formHTMLEntity() {
	$this->m_clients->id = $this->entityID;
	
	try {
	  $this->m_clients->update();
	}
	catch (Exception $e) {
      $this->session->set_flashdata('msg',array('type'=>'error','text'=>$e->getMessage()));
	}
	die(header("Location:/clients{$this->m_clients->id}.html"));

  }
  
  function formHTMLNew() {
	
	try {
	  $this->m_clients->add();
	}
	catch (Exception $e) {
      $this->session->set_flashdata('msg',array('type'=>'error','text'=>$e->getMessage()));
	}
	die(header("Location:/clients/{$this->m_clients->id}.html"));
  }
  
  function renderJSONEntity() {
	
	$this->m_clients->id = $this->entityID;
	$result = array();
	
	try {
	  $this->m_clients->get();
	  $data = $this->m_clients->get();
	  $data->id = $data->clientID;
	  $result['status'] = 'ok';
	  $result['data'] = $data;
	}
	catch (Exception $e) {
	  $result['status'] = 'error';
	  $result['message'] = $e->getMessage();
	}
	
	print json_encode($result);
	
  }
  
  function renderJSONDelete() {

	$this->m_clients->id = $this->entityID;
	$result = array();
	
	try {
	  $this->m_clients->delete();
	  $result['status'] = 'ok';
	}
	catch (Exception $e) {
	  $result['status'] = 'error';
	  $result['message'] = $e->getMessage();
	}
	
	print json_encode($result);
	
  }
  
  function formJSONEntity() {
	
	$this->m_clients->id = $this->entityID;
	$result = array();
	
	try {
	  $json = json_decode(file_get_contents('php://input'));
	  
	  foreach ($json as $k=>$v) {
		$this->m_clients->{$k} = $v;
	  }

	  $this->m_clients->update();
	  $result = $this->m_clients->get();
	}
	catch (Exception $e) {
	  $result['status'] = 'error';
	  $result['message'] = $e->getMessage();
	}
	
	print json_encode($result);
	
  }
  
  function formJSONNew() {
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_clients->$k = $v;
	}

	$this->m_clients->add();
	$row = $this->m_clients->get();
	
	print json_encode($row);
	
  }
  
  
}