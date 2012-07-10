<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_contacts');
    $this->load->model('m_contact_metas');
    $this->load->model('m_contact_addresses');
  }
  
  function renderHTML() {
	
	$this->m_contacts->id = $this->user->userID();
	$this->m_contact_metas->usersID = $this->user->userID();
	$this->m_contact_addresses->usersID = $this->user->userID();
	
	$this->load->vars('contactJSON',$this->m_contacts->getJSON());
	$this->load->vars('addressJSON', $this->m_contact_addresses->fetchJSON());
	$this->load->vars('phoneJSON', $this->m_contact_metas->fetchPhoneJSON());
	$this->load->vars('employmentDataJSON', $this->m_contact_metas->fetchEmploymentDataJSON());

	$this->m_metas->schemaName = 'phoneTypes';
	$this->load->vars('phoneTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'addressTypes';
	$this->load->vars('addressTypes', $this->m_metas->fetch());

	$this->m_metas->schemaName = 'states';
	$this->load->vars('states', $this->m_metas->fetch());
	
	$this->load->vars('js_tpl_entity',$this->load->view('profile/js_tpl_entity','',true));
	$this->load->vars('js_tpl_addresslist', $this->load->view('contacts/js_tpl_addresslist','',true));
	$this->load->vars('js_tpl_phonelist', $this->load->view('contacts/js_tpl_phonelist','',true));
	$this->load->vars('js_tpl_employmentdatalist', $this->load->view('contacts/js_tpl_employmentdatalist','',true));
	$this->load->vars('js_tpl_typeahead_list', $this->load->view('contacts/js_tpl_typeahead_list','',true));
	$this->load->vars('content', $this->load->view('profile/entity','', true));
	$this->jsFiles('/scripts/location-lookup.js');
	$this->jsFiles('/scripts/address-view.js');
	$this->jsFiles('/scripts/phone-view.js');
	$this->jsFiles('/scripts/employment-data-view.js');
	$this->jsFiles('/scripts/contact-entity.js');
	
  }

}