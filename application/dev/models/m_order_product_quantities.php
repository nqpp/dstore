<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_order_product_quantities extends MM_Model {

  function __construct() {
	$this->pk = 'orderProductID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'orderproductsID',
      'productsID',
      'code',
      'name',
      'qty'
    );
  }
  
}
//EOF