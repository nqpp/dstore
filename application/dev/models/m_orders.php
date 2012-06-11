<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_orders extends MM_Model {

  function __construct() {
	$this->pk = 'orderID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'usersID',
      'createdAt',
	  'status'
    );
  }
  
}
//EOF