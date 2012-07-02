<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_order_product_quantities extends MM_Model {
  
  public $cartItems = array();
  public $orderIDs = array();

  function __construct() {
	$this->pk = 'orderProductQuantityID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'orderProductsID',
      'productsID',
      'code',
      'name',
      'qty'
    );
  }
  
  function fetchForOrdersGrouped() {
	
	if (!$this->orderIDs || !count($this->orderIDs)) return array();
	
	$this->db->join('orderProducts','orderProductID = orderProductsID', 'left outer');
	$this->db->where_in('ordersID', $this->orderIDs);
	
	return $this->fetchGrouped();
  }
  
  function addCart() {
	
	if (!$this->cartItems || !count($this->cartItems)) throw new Exception('No cart data to add to order[m_order_product_quantities:addCart]');
	
	$this->productsID = $this->cartItems->productsID;
	$this->code = $this->cartItems->code;
	$this->name = $this->cartItems->name;
	$this->qty = $this->cartItems->qty;
	
	$this->add();
	
  }
  
}
//EOF