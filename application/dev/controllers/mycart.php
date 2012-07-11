<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mycart extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_carts');
    $this->load->model('m_cart_items');
		$this->load->library('cartcalc');
		$this->load->model('m_chargeouts');
  }
  
  function renderHTML() {

	// Calculate Totals
	$this->m_carts->usersID = $this->user->id();
	$this->m_carts->index = 'czone';
	$product = $this->m_carts->fetchGroupedForCalculated();
	
	$carts = array();

	if(count($product) > 0) {
		
	  $deliveryAddress = $this->user->userAddress();

	  $this->m_chargeouts->xto = $deliveryAddress->czone;
	  $this->m_chargeouts->xfroms = array_keys($product);
	  $this->m_chargeouts->index = 'xfrom';
	  $freight = $this->m_chargeouts->fetchIndexedForCalc();

	  $this->cartcalc->products($product);
	  $this->cartcalc->freights($freight);

	  $carts = $this->cartcalc->calcAll();
	}

	$this->load->vars('carts', $carts);
	$this->m_cart_items->index = 'cartsID';
	$this->load->vars('cartItems', $this->m_cart_items->fetchGrouped());
	$allAddresses = $this->user->alladdresses();
	$addresses = $allAddresses ? array_values($allAddresses): array();
	$this->load->vars('userAddresses', json_encode($addresses));
	$this->load->vars('userAddressID', $this->user->userAddressID());
	
	$this->load->vars('content',$this->load->view('mycart/list', '', true));
	$this->jsFiles('/scripts/userAddresses.js');
	$this->jsFiles('/scripts/mycart-list.js');
	
  }

	function formJSONNew() {
		
	  $json = json_decode(file_get_contents('php://input'));

	  // Set userAddress/deliveryAddress per client selection.
	  $this->user->userAddressID($json->deliveryAddressID);

	  $this->m_carts->usersID = $this->user->id();
	  $this->m_carts->index = 'czone';
	  $product = $this->m_carts->fetchGroupedForCalculated();

	  $deliveryAddress = $this->user->userAddress($json->deliveryAddressID);

	  $this->m_chargeouts->xto = $deliveryAddress->czone;
	  $this->m_chargeouts->xfroms = array_keys($product);
	  $this->m_chargeouts->index = 'xfrom';
	  $freight = $this->m_chargeouts->fetchIndexedForCalc();

	  $this->cartcalc->products($product);
	  $this->cartcalc->freights($freight);

	  die(json_encode($this->cartcalc->calcAll()));
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