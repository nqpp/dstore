<?php header('Content-type: application/json');  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Carts extends MM_Controller {
	
	var $cart;

  function __construct() {
	
		parent::__construct();
		$this->load->model('m_carts');
		$this->load->model('m_cart_items');
  }

  function renderHTML() {
	
  }

	function renderHTMLEntity() {

	} 

	function renderJSON() {
		
		$data = $this->m_carts->fetchUserCart(); 
		
		$rtn = array();
		foreach($data as $item) $rtn[] = $item;
		
		$totals = $this->calculateTotals($data->productsID, $data->qtyTotal);
		if($totals) {
			$rtn[]['subtotal'] = $totals->subtotal;
			$rtn[]['freightTotal'] = $totals->freightTotal;
			$rtn[]['gst'] = $totals->gst;
			$rtn[]['total'] = $totals->total;
		}
		
		echo json_encode($rtn);
	}

  function formJSONNew() {

		$json = json_decode(file_get_contents('php://input'));

		$this->m_carts->usersID = User::id();
		$this->m_carts->productsID = $json->productsID;
		$this->m_carts->qtyTotal = $json->qtyTotal;
	
		if(!$this->m_carts->reachedMOQ()) die(header('HTTP/1.0 400 Bad Request'));
	
		foreach ($json as $k => $v) $this->m_carts->{$k} = $v;
	
		$totals = $this->m_carts->calculateTotals();

		$this->load->model('m_products');
		$this->m_products->id = $json->productsID;
		$product = $this->m_products->get();
	
		$this->m_carts->name = $product->name;
	  $this->m_carts->createdAt = date("Y-m-d H:i:s");
		$this->m_carts->itemPrice = $totals->itemPrice;
		$this->m_carts->freightTotal = $totals->freightTotal;
		$this->m_carts->taxRate = $totals->taxRate;

	  $this->m_carts->add();
	
		$row = $this->m_carts->get();
	
		$row->itemPrice = money_format('%.2n', $row->itemPrice);
		$row->freightTotal = money_format('%.2n', $row->freightTotal);
		$row->subtotal = money_format('%.2n', $totals->subtotal);
		$row->gst = money_format('%.2n', $totals->gst);
		$row->total = money_format('%.2n', $totals->total);
	
		print json_encode($row);
  }

  function formJSONEntity() {

		$this->m_carts->id = $this->entityID;
		$json = json_decode(file_get_contents('php://input'));
		
		foreach ($json as $k => $v) $this->m_carts->{$k} = $v;
	
		if(!$this->m_carts->reachedMOQ()) die(header('HTTP/1.0 400 Bad Request'));
	
		$totals = $this->m_carts->calculateTotals();

		$this->m_carts->itemPrice = $totals->itemPrice;
		$this->m_carts->freightTotal = $totals->freightTotal;
		$this->m_carts->taxRate = $totals->taxRate;

		$this->m_carts->update();
		$row = $this->m_carts->get();
	
		$row->itemPrice = money_format('%.2n', $row->itemPrice);
		$row->freightTotal = money_format('%.2n', $row->freightTotal);
		$row->subtotal = money_format('%.2n', $totals->subtotal);
		$row->gst = money_format('%.2n', $totals->gst);
		$row->total = money_format('%.2n', $totals->total);
	
		print json_encode($row);
  }

	function renderJSONDelete() {
		
		// Remove cart_items
		$this->m_cart_items->cartsID = $this->entityID;
		$this->m_cart_items->removeByCartsId();
		
		// Remove cart		
		$this->m_carts->id = $this->entityID;
		$this->m_carts->delete();
	}
}