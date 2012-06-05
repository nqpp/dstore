<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locations extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_locations');
    $this->load->model('m_zones');
  }
  
  function renderHTML() {

	$this->load->vars('locationsJSON', $this->m_locations->fetchJoinedJSON());
	$this->m_zones->setSort('code');
	$this->load->vars('zones', $this->m_zones->fetch());
	$this->load->vars('js_tpl_list', $this->load->view('locations/js_tpl_list','',true));
	$this->load->vars('content',$this->load->view('locations/list', '', true));

	$this->jsFiles('/scripts/location-list.js');

  }
  
  
//  function renderHTMLEntity() {
//	
//    $this->m_locations->id = $this->entityID;
//	$this->load->vars('locationJSON', $this->m_locations->getJoinedJSON());
//
//	$this->m_zones->setSort('code');
//	$this->load->vars('zones', $this->m_zones->fetch());
//	$this->load->vars('js_tpl_entity', $this->load->view('locations/js_tpl_entity','',true));
//	$this->load->vars('content',$this->load->view('locations/entity','', true));
//
//	$this->jsFiles('/scripts/location-entity.js');
//	
//  }


  function renderJSONDelete() {
	
    $this->m_locations->id = $this->entityID;
	$this->m_locations->delete();
	
  }
  
  function formJSONEntity() {
	
    $this->m_locations->id = $this->entityID;
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_locations->{$k} = $v;
	}

	$this->m_locations->update();
	$row = $this->m_locations->getJoined();
	print json_encode($row);
	
  }
  
  function formJSONNew() {
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_locations->$k = $v;
	}

	$this->m_locations->add();
	$row = $this->m_locations->getJoined();

	print json_encode($row);
	
  }

}