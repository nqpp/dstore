<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myorders extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_orders');
	$this->load->model('m_order_products');
	$this->load->model('m_order_product_quantities');
	$this->load->model('m_order_addresses');
  }
  
  function renderHTML() {
	
	$this->m_orders->usersID = $this->user->userID();
	$orders = $this->m_orders->fetchIndexed();
	$this->m_order_products->orderIDs = array_keys($orders);
	$this->m_order_products->index = 'ordersID';
	$orderProducts = $this->m_order_products->fetchGrouped();
	
	$this->load->vars('orders', $orders);
	$this->load->vars('orderProducts', $orderProducts);
	
	$this->load->vars('content', $this->load->view('myorders/list','',true));
  }

  function renderHTMLEntity() {
	
	$this->m_orders->id = $this->entityID;
	$this->load->vars('order', $this->m_orders->get());
	
  }
  
  function renderHTMLNew() {
	
	$this->load->model('m_carts');
	$this->load->model('m_cart_items');
	$this->load->model('m_contact_addresses');
	
	// get cart row(s)
	$carts = $this->m_carts->fetchUserCart();
	
	if (!count($carts)) die (header("Location:/mycart.html"));
	
	// get items for cart(s)
	$this->m_cart_items->cartIDs = array_keys($carts);
	$cartItems = $this->m_cart_items->fetchCartItems();
	// add the order
	$this->m_orders->add();
	// cycle through carts and add product for each
	// cycle through items and add order product qty for each
	$this->m_order_products->ordersID = $this->m_orders->id;
	foreach ($carts as $c) {
	  $this->m_order_products->cart = $c;
	  $this->m_order_products->addCart();
	  
	  $this->m_order_product_quantities->orderProductsID = $this->m_order_products->id;
	  foreach ($cartItems[$c->cartID] as $ci) {
		$this->m_order_product_quantities->cartItems = $ci;
		$this->m_order_product_quantities->addCart();
		
	  }
	}
	
	// get delivery address
//	$this->m_contact_addresses->id = $this->input->post('deliveryAddressID');
$this->m_contact_addresses->id = 1;
	$deliveryAddress = $this->m_contact_addresses->get();
	// write delivery address
	$this->m_order_addresses->ordersID = $this->m_orders->id;
	$this->m_order_addresses->userAddress = $deliveryAddress;
	$this->m_order_addresses->addAddress();	
	
	// empty cart data for user
	$this->m_carts->deleteUserCart();
	$this->m_cart_items->removeByCartIDs();
	
	die (header("Location:/myorders.html"));
	
  }
  
  

}
//EOF