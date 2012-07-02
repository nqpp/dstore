<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_suppliers extends MM_Model {

  function __construct() {
	$this->pk = 'supplierID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'name',
      'entityName'
    );
  }

}