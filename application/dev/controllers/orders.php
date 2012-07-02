<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_orders');
	$this->load->model('m_order_products');
	$this->load->model('m_order_product_quantities');
	$this->load->model('m_order_addresses');
	$this->load->model('m_metas');
  }
  
  function renderHTML() {

	$this->m_orders->filter();
	$ordersIndexed = $this->m_orders->fetchIndexedWithClient();
	$orders = $this->m_orders->fetchWithClient();
	
	$this->m_order_products->index = "ordersID";
	$orderProducts = $this->m_order_products->fetchCalcGrouped();
	
	$this->m_order_product_quantities->index = "orderProductsID";
	$this->m_order_product_quantities->orderIDs = array_keys($ordersIndexed);
	$orderProductQuantities = $this->m_order_product_quantities->fetchForOrdersGrouped();

	$this->m_order_addresses->index = "ordersID";
	$orderAddresses = $this->m_order_addresses->fetchIndexed();
	
	$this->m_metas->schemaName = "orderStatusTypes";
	$orderStatusTypes = $this->m_metas->fetch();

	$this->load->vars('ordersJSON', json_encode($orders));
	$this->load->vars('orderProductsJSON', json_encode($orderProducts));
	$this->load->vars('orderProductQuantitiesJSON', json_encode($orderProductQuantities));
	$this->load->vars('orderAddressesJSON', json_encode($orderAddresses));
	$this->load->vars('orderStatusFilterJSON', json_encode($this->userpref->orderStatusFilter()));
	
	$this->load->vars('orderStatusTypesJSON', json_encode($orderStatusTypes));
	$this->load->vars('orderStatusTypes', $orderStatusTypes);
	
	$this->load->vars('js_tpl_list', $this->load->view('orders/js_tpl_list', '', true));
	$this->load->vars('js_tpl_order_products_list', $this->load->view('orders/js_tpl_order_products_list', '', true));
	$this->load->vars('js_tpl_quantities_list', $this->load->view('orders/js_tpl_quantities_list', '', true));
	$this->load->vars('js_tpl_order_address', $this->load->view('orders/js_tpl_order_address', '', true));
	$this->load->vars('js_tpl_status_filter', $this->load->view('orders/js_tpl_status_filter', '', true));
	$this->load->vars('content', $this->load->view('orders/list', '', true));
	$this->jsFiles('/scripts/order-list.js');
	
  }
  
  function renderHTMLEntity() {
	
  }
  
  function formJSONEntity() {
	
	$this->m_orders->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));
	
	$this->m_orders->set($json);
	$this->m_orders->update();
	die (print (json_encode($this->m_orders->get())));
	
	
  }
  
}
//EOF