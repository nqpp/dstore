<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Freight_locations extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_freight_locations');
  }

  function formJSONEntity() {
	
	$this->m_freight_locations->id = $this->entityID;
	
	try {
	  $json = json_decode(file_get_contents('php://input'));
	  
	  foreach ($json as $k=>$v) {
		$this->m_freight_locations->{$k} = $v;
	  }

	  $this->m_freight_locations->update();
	  $result = $this->m_freight_locations->get();
	}
	catch (Exception $e) {
	  $result['status'] = 'error';
	  $result['message'] = $e->getMessage();
	}
	
	print json_encode($result);
	
  }
}