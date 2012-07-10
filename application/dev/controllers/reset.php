<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Reset class
 * 
 * Admin development use.
 * Empties all carts and orders
 */
class Reset extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_carts');
	$this->load->model('m_cart_items');
	$this->load->model('m_orders');
	$this->load->model('m_order_products');
	$this->load->model('m_order_product_quantities');
	$this->load->model('m_order_addresses');
  }
  
  function renderHTML() {
//print 'reset'; exit;
	$this->m_carts->reset();
	$this->m_cart_items->reset();
	$this->m_orders->reset();
	$this->m_order_products->reset();
	$this->m_order_product_quantities->reset();
	$this->m_order_addresses->reset();
	
	die( header("Location:/dashboards.html?admin"));
	
  }

}
//EOF