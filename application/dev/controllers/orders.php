<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_orders');
	$this->load->model('m_order_products');
	$this->load->model('m_order_product_quantities');
  }
  
  function renderHTML() {
	
	$orders = $this->m_orders->fetchIndexedWithClient();
	
	$this->m_order_products->index = "ordersID";
	$orderProducts = $this->m_order_products->fetchGrouped();
	
	$this->m_order_product_quantities->index = "orderProductsID";
	$this->m_order_product_quantities->orderIDs = array_keys($orders);
	$orderProductQuantities = $this->m_order_product_quantities->fetchForOrdersGrouped();

	$this->load->vars('orders', $orders);
	$this->load->vars('orderProducts', $orderProducts);
	$this->load->vars('orderProductQuantities', $orderProductQuantities);
	
	$this->load->vars('content', $this->load->view('orders/list', '', true));
	
  }
  
  function renderHTMLEntity() {
	
  }
  
}
//EOF