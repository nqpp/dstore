<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store_cart extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_carts');
    $this->load->model('m_cart_items');
  }
  
  function renderHTML() {
	
		$this->load->vars('cartJSON',json_encode($this->compileCart()));
		$this->load->vars('js_tpl_cart_item', $this->load->view('store_cart/js_tpl_cart_item','',true));
		$this->load->vars('content',$this->load->view('store_cart/entity', '', true));
//		$this->jsFiles('/scripts/store_cart-entity.js');
		$this->jsFiles('/scripts/cart.js');
		$this->jsFiles('/scripts/jquery-ui-1.8.21.min.js');
		
		$this->load->vars('scriptFiles', $this->jsFiles());
  }
  
  function compileCart() {
	
	$cart = $this->m_carts->fetchUserCart();
	
	if (!count($cart)) return array();

	$this->m_cart_items->cartIDs = array_keys($cart);
	$items = $this->m_cart_items->fetchCartItems();

	$return = array();

	foreach ($cart as $id=>$row) {
		
	  if (isset($items[$id])) {
		
		$row->items = $items[$id];
		$return[] = $row;
	  }
	}
	
	return $return;
  }
  
}