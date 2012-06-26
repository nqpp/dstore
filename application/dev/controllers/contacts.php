<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_contacts');
	$this->load->model('m_client_contacts');
	$this->load->model('m_contact_metas');
	$this->load->model('m_contact_addresses');
	$this->load->model('m_metas');
  }
  
  function renderHTMLEntity() {
	
	$this->m_contacts->id = $this->entityID;
	$this->m_client_contacts->usersID = $this->entityID;
	$this->m_contact_addresses->usersID = $this->entityID;
	$this->m_contact_metas->usersID = $this->entityID;
	
	$client = $this->m_client_contacts->getJoined();
	
	$this->load->vars('contactJSON',$this->m_contacts->getJSON());
	$this->load->vars('client',$client);
	$this->load->vars('clientJSON',json_encode($client));
	$this->load->vars('addressJSON', $this->m_contact_addresses->fetchJSON());
	$this->load->vars('phoneJSON', $this->m_contact_metas->fetchPhoneJSON());

	$this->m_metas->schemaName = 'phoneTypes';
	$this->load->vars('phoneTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'addressTypes';
	$this->load->vars('addressTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'states';
	$this->load->vars('states', $this->m_metas->fetch());

	$this->load->vars('js_tpl_entity',$this->load->view('contacts/js_tpl_entity','',true));
	$this->load->vars('js_addresslist', $this->load->view('contacts/js_tpl_addresslist','',true));
	$this->load->vars('js_phonelist', $this->load->view('contacts/js_tpl_phonelist','',true));
	$this->load->vars('js_tpl_typeahead_list', $this->load->view('contacts/js_tpl_typeahead_list','',true));
	
	$this->load->vars('content',$this->load->view('contacts/entity', '', true));
	$this->jsFiles('/scripts/contact-entity.js');

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

}