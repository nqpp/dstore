<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mycart extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_carts');
    $this->load->model('m_cart_items');
  }
  
  function renderHTML() {
	$this->load->vars('carts', $this->m_carts->fetchUserCart());
	$this->m_cart_items->index = 'cartsID';
	$this->load->vars('cartItems', $this->m_cart_items->fetchGrouped());
	$this->load->vars('userAddresses', json_encode($this->user->alladdresses()));
	
	$this->load->model('m_user_addresses');
	$this->m_user_addresses->usersID = $this->user->id();
	$this->m_user_addresses->type = 'Delivery';
//var_dump($this->m_user_addresses->fetchForSelectJSON()); exit;
	$this->load->vars('userAddresses', $this->m_user_addresses->fetchForSelectJSON());
//print 'mycart'; exit;	
	
	$this->load->vars('content',$this->load->view('mycart/list', '', true));
	$this->jsFiles('/scripts/userAddresses.js');
	$this->jsFiles('/scripts/mycart-list.js');
	
  }
  
  /*
   * remove a cart row and assoc items
   */
  function renderHTMLDelete() {
	
	$this->m_carts->id = $this->entityID;
	$this->m_cart_items->cartsID = $this->entityID;

	$row = $this->m_carts->get();
	
	if ($row->usersID != $this->user->userID()) throw new Exception('Cart item does not belong to this user');
	
	$this->m_cart_items->removeByCartsId();
	$this->m_carts->delete();
	
	die(header("location:/mycart.html"));
	
  }
  
  /*
   * clear the cart of all rows and assoc items for this user
   */
  function renderHTMLClear() {
	
	$carts = $this->m_carts->fetchUserCart();
	$this->m_cart_items->cartIds = array_keys($carts);
	
	$this->m_cart_items->removeByCartIDs();
	$this->m_carts->deleteUserCart();
	
	die(header("location:/mycart.html"));
	
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