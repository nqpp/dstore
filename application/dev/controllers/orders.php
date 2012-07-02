<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_orders');
	$this->load->model('m_order_products');
	$this->load->model('m_order_product_quantities');
  }
  
  function renderHTML() {
	
	$this->load->vars('orders', $this->m_orders->fetch());
//	$this->load->vars('ordersJSON', $this->m_orders->fetchJSON());
	
	$this->m_order_products->index = "ordersID";
	$this->load->vars('orderProducts', $this->m_order_products->fetchGrouped());
	
	$this->load->vars('content', $this->load->view('orders/list', '', true));
	
  }
  
  function renderHTMLEntity() {
	
  }
  
}
//EOF