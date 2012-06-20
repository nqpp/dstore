<?php header('Content-type: application/json'); if (!defined('BASEPATH')) exit('No direct script access allowed');

class Check_cart extends MM_Controller {

	function __construct() {
	
		parent::__construct();
		$this->load->model('m_carts');
		$this->load->model('m_cart_items');
  }

	function formJSONNew() {

		$json = json_decode(file_get_contents('php://input'));

		$this->m_carts->usersID = User::id();
		$this->m_carts->productsID = $json->productsID;
		$this->m_carts->qtyTotal = $json->qtyTotal;
	
		if(!$this->m_carts->reachedMOQ()) die(header('HTTP/1.0 400 Bad Request'));
	
		$totals = $this->m_carts->calculateTotals();

		$row->moqReached = true;
		$row->productsID = $json->productsID;
		$row->qtyTotal = $json->qtyTotal;
		$row->itemPrice = money_format('%.2n', $totals->itemPrice);
		$row->freightTotal = money_format('%.2n', $totals->freightTotal);
		$row->subtotal = money_format('%.2n', $totals->subtotal);
		$row->gst = money_format('%.2n', $totals->gst);
		$row->total = money_format('%.2n', $totals->total);
	
		
		die(print json_encode($row));
  }

	function formJSONEntity() {
		
		$this->m_carts->id = $this->entityID;
		return $this->formJSONNew();
	}
}