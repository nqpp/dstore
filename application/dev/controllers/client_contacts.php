<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_contacts extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_client_contacts');
	$this->load->model('m_contact_metas');
	$this->load->model('m_contact_addresses');
	$this->load->model('m_contacts');
  }
  
  function renderHTMLEntity() {
	$this->m_client_contacts->id = $this->entityID;
	$contact = $this->m_client_contacts->getFullJoin();
//print '<xmp>';	
//print_r($contact);
//print '</xmp>';	
//exit;
//	$this->m_contacts->id = $this->entityID;
//	$this->m_client_contacts->usersID = $this->entityID;
	
	$this->load->vars('contactJSON', json_encode($contact));
	$this->m_contact_addresses->usersID = $contact->userID;
	$this->load->vars('addressJSON', $this->m_contact_addresses->fetchJSON());
	
	
//	$data->contact = $this->m_contacts->get();
//	$data->client = $this->m_client_contacts->getJoined();

	$this->load->vars('contactJSON', $this->m_contacts->getJSON());
	$this->load->vars('clientJSON', json_encode($this->m_client_contacts->getJoined()));
	$this->load->vars('phoneJSON', $this->m_contact_metas->fetchPhoneJSON());

	$this->load->vars('js_contact_entity', $this->load->view('client_contacts/js_tpl_contact_entity','',true));

	$this->load->model('m_metas');
	
	$this->m_metas->schemaName = 'phoneTypes';
	$this->load->vars('phoneTypes', $this->m_metas->fetch());
	$this->m_metas->schemaName = 'addressTypes';
	$this->load->vars('addressTypes', $this->m_metas->fetch());
	$this->m_metas->schemaName = 'states';
	$this->load->vars('states', $this->m_metas->fetch());

	$this->load->vars('js_phonelist', $this->load->view('client_contacts/js_tpl_phonelist','',true));
	$this->load->vars('js_addresslist', $this->load->view('client_contacts/js_tpl_addresslist','',true));
	$this->load->vars('content',$this->load->view('client_contacts/entity', $data, true));
	$this->jsFiles('/scripts/client-contact-entity.js');

  }
  
  function renderJSON() {
	
	$this->m_client_contacts->clientsID = $this->input->get('clientID');
	$result = $this->m_client_contacts->fetch();
	
	print json_encode($result);
	
  }
  
  function renderJSONEntity() {
	
	$this->m_contacts->id = $this->entityID;
	$row = $this->m_contacts->get();
	
	print json_encode($row);
	
  }
  
  function formJSONEntity() {
	
	$this->m_contacts->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_contacts->{$k} = $v;
	}

	$this->m_contacts->update();
	$row = $this->m_contacts->get();
	
	print json_encode($row);
	
  }
  
  function formJSONNew() {
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_contacts->{$k} = $v;
	}

	$this->m_contacts->add();
	$row = $this->m_contacts->get();

	foreach ($json as $k=>$v) {
	  $this->m_client_contacts->{$k} = $v;
	}

	$this->m_client_contacts->id = false;
	$this->m_client_contacts->usersID = $this->m_contacts->id;
	$this->m_client_contacts->add();
	$row->clientContactID = $this->m_client_contacts->id;
	
	print json_encode($row); 
  }

  function renderJSONDelete() {
	
	$this->m_client_contacts->id = $this->entityID;
	$row = $this->m_client_contacts->get();
	
	$this->m_client_contacts->delete();
	
	$this->m_contact_metas->usersID = $row->usersID;
	$this->m_contact_metas->deleteContact();
	
	$this->m_contact_addresses->usersID = $row->usersID;
	$this->m_contact_addresses->deleteContact();
	
	$this->m_contacts->id = $row->usersID;
	$this->m_contacts->delete();
  }
  
}
//EOF