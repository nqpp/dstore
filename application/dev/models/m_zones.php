<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_zones extends MM_Model {

  function __construct() {
	$this->pk = 'zoneID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
	  'code'
    );
  }
  
}