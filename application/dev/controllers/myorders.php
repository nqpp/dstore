<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myorders extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_orders');
	$this->load->model('m_order_products');
	$this->load->model('m_order_product_quantities');
//print 'Myorders'; exit;
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
	
  }

}
//EOF