<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locations extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_locations');
//    $this->load->model('m_zones');
  }
  
  function renderHTML() {

	$this->m_locations->filter();
	$locations = $this->m_locations->fetch();
	$this->load->vars('locationsJSON', json_encode($locations));
	$this->load->vars('filter', $this->m_locations->getFilter());
	
	$this->load->vars('js_tpl_list', $this->load->view('locations/js_tpl_list','',true));
	$this->load->vars('js_tpl_pagination', $this->load->view('locations/js_tpl_pagination','',true));
	$this->load->vars('content',$this->load->view('locations/list', '', true));

	$this->jsFiles('/scripts/backbone.paginator.js');
	$this->jsFiles('/scripts/location-list.js');

  }
  
  function renderJSONDelete() {
	
    $this->m_locations->id = $this->entityID;
	$this->m_locations->delete();
	
  }
  
  function renderJSONFiltered() {

	$this->m_locations->filter();
	$this->m_locations->limit();
	print json_encode($this->m_locations->fetch());
	
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