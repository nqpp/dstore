<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_order_products extends MM_Model {

  public $cart = array();
  
  function __construct() {
	$this->pk = 'orderProductID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'ordersID',
      'productsID',
      'code',
      'name',
      'qtyTotal',
      'itemPrice',
      'freightTotal',
	  'taxRate'
    );
  }
  
  function addCart() {
	
	if (!$this->cart || !count($this->cart)) throw new Exception('No cart data to add to order[m_order_products:addCart]');
	
	$this->name = $this->cart->name;
	$this->qtyTotal = $this->cart->qtyTotal;
	$this->itemPrice = $this->cart->itemPrice;
	$this->freightTotal = $this->cart->freightTotal;
	$this->taxRate = $this->cart->taxRate;
	
	$this->add();
	
  }
  
}
//EOF