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
	
	$this->m_order_product_quantities->index = "orderProductsID";
	$this->m_order_product_quantities->orderIDs = array_keys($orders);
	$orderProductQuantities = $this->m_order_product_quantities->fetchForOrdersGrouped();
	
	$this->m_order_addresses->index = "ordersID";
	$orderAddresses = $this->m_order_addresses->fetchIndexed();
	
	$this->load->vars('orders', $orders);
	$this->load->vars('orderProducts', $orderProducts);
	$this->load->vars('orderProductQuantities', $orderProductQuantities);
	$this->load->vars('orderAddresses', $orderAddresses);
	
	$this->load->vars('content', $this->load->view('myorders/list','',true));
  }

  function renderHTMLEntity() {
	
	$this->m_orders->id = $this->entityID;
	$this->load->vars('order', $this->m_orders->get());
	
  }
  
  function formHTMLNew() {
	$this->load->model('m_emails');
	$this->load->model('m_metas');
	$this->load->model('m_carts');
	$this->load->model('m_cart_items');
	$this->load->model('m_contact_addresses');
	$this->load->model('m_user_addresses');
	
	$this->cartToOrder();
	$this->getOrderDetail();
	$this->addClientEmail();
	$this->sendClientEmail();
	
	// empty cart data for user
	$this->m_carts->deleteUserCart();
	$this->m_cart_items->removeByCartIDs();
	
	die (header("Location:/myorders.html"));
	
  }
  
  private function cartToOrder() {
	
 	// get cart row(s)
	$carts = $this->m_carts->fetchUserCartIndexed();

	if (!count($carts)) die (header("Location:/mycart.html"));
	
	// get items for cart(s)
	$this->m_cart_items->cartIDs = array_keys($carts);
	$cartItems = $this->m_cart_items->fetchCartItems();
	
	// add the order
	$this->m_orders->add();
	// cycle through carts and add product for each
	$this->m_order_products->ordersID = $this->m_orders->id;
	foreach ($carts as $c) {
	  $this->m_order_products->set($c);
	  $this->m_order_products->add();
	  
	  // cycle through items and add order product qty for each
	  $this->m_order_product_quantities->orderProductsID = $this->m_order_products->id;
	  foreach ($cartItems[$c->cartID] as $ci) {
		$this->m_order_product_quantities->set($ci);
		$this->m_order_product_quantities->add();
		
	  }
	}
	
	// get delivery address
	$this->m_user_addresses->id = $this->input->post('deliveryAddressID'); // should be able to get this from user object now.
	$deliveryAddress = $this->m_user_addresses->get();
	// write delivery address
	$this->m_order_addresses->ordersID = $this->m_orders->id;
	$this->m_order_addresses->set($deliveryAddress);
	$this->m_order_addresses->add();
	
  }
  
  function getOrderDetail() {
	
	$order = $this->m_orders->get();
	$this->m_order_products->reset();
	$this->m_order_products->ordersID = $this->m_orders->id;
	$order->products = $this->m_order_products->fetch();

	$this->load->vars('order', $order);
	
  }
  
  function addClientEmail() {
	
 	$this->m_metas->schemaName = 'systemEmail';
	$sysEmails = $this->m_metas->fetchKVPairObj();
	$this->m_emails->set($sysEmails); // set sender info for email
	
	$this->m_emails->to = $this->user->email();
	$this->m_emails->subject = 'DStore Order Submitted';
	$this->m_emails->message = $this->load->view('myorders/email_message','',true);
//	$this->m_emails->altmessage = $this->makePlainText($message);
	$this->m_emails->add();
	
 }

  function sendClientEmail() {

    $this->load->library('email_handler');
	
	$this->m_metas->schemaName = 'systemEmailConfig';
	$this->email_handler->set($this->m_metas->fetchKVPairObj());
	$this->email_handler->set($this->m_emails->get()); // set email data
	$this->email_handler->send();
	
	$this->m_emails->saveSent();
  }
  
}
//EOF