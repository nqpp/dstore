<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_client_addresses extends MM_Model {

  function __construct() {
	$this->pk = 'clientAddressID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
      'clientsID',
      'type',
      'address',
      'city',
      'state',
      'postcode',
	  'sort'
    );
  }
  

}