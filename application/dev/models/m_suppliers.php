<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_suppliers extends MM_Model {

  function __construct() {
	$this->pk = 'supplierID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
      'name',
      'postcode'
    );
  }

}