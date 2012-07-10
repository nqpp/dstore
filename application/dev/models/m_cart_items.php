<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_cart_items extends MM_Model {
  
  public $cartIDs = array();

  function __construct() {
	$this->pk = 'cartItemID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'cartsID',
      'productsID',
      'name',
      'code',
      'qty'
    );
  }
  
  function fetchCartItems() {
	
	if (!count($this->cartIDs)) return;
	
	$this->db->where_in('cartsID', $this->cartIDs);
	$this->index = 'cartsID';
	$items = $this->fetchGrouped();
	return $items;
  }

  function removeByCartsId() {

	$this->db->where('cartsID', $this->cartsID);
	$this->db->delete('cartItems');
  }

  function removeByCartIDs() {

	if (!$this->cartIDs || !count($this->cartIDs)) return;
	
	$this->db->where_in('cartsID', $this->cartIDs);
	$this->db->delete('cartItems');
	
  }

  function truncate() {
	$this->db->truncate('cartItems');	
  }

}