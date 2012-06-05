<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Freights extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_freights');
    $this->load->model('m_freight_locations');
  }

  function formJSONEntity() {
	
	$this->m_freights->id = $this->entityID;
	
	try {
	  $json = json_decode(file_get_contents('php://input'));
	  
	  foreach ($json as $k=>$v) {
		$this->m_freights->{$k} = $v;
	  }

	  $this->m_freights->update();
	  $result = $this->m_freights->get();
	}
	catch (Exception $e) {
	  $result['status'] = 'error';
	  $result['message'] = $e->getMessage();
	}
	
	print json_encode($result);
	
  }
}