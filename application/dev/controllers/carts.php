<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Carts extends MM_Controller {
	
	var $cart;

  function __construct() {
	
		parent::__construct();
		$this->load->model('m_carts');
		$this->load->model('m_cart_items');
		$this->load->model('m_product_metas');
		$this->load->model('m_products');
		$this->load->model('m_chargeouts');
  }

	function renderJSON() {

		echo $this->m_carts->fetchUserCartJSON();
	}

  function formJSONNew() {

		$this->load->library('cartcalc');
		$json = json_decode(file_get_contents('php://input'));
		
		$this->m_product_metas->productsID = $json->productsID;
		$this->m_product_metas->qtyTotal = $json->qtyTotal;
		$pricePoint = $this->m_product_metas->getPricePoint();

		if (!$pricePoint) die(header('HTTP/1.0 400 Bad Request'));

		$this->m_metas->schemaName = "tax";
		$tax = $this->m_metas->fetchKVPairObj();
		$gst = isset($tax->GST) ? $tax->GST : 10;

		$this->m_products->id = $json->productsID;

		$product = $this->m_products->getSupplierCzone();

		$product->productsID = $json->productsID;
		$product->taxRate = $gst;
		$product->itemPrice = $pricePoint->metaValue;
		$product->qtyTotal = $json->qtyTotal;

		$this->cartcalc->product($product);

		$this->m_chargeouts->xto = $this->user->czone();
		$this->m_chargeouts->xfrom = $product->czone;
		$freight = reset($this->m_chargeouts->fetch());

		$this->cartcalc->freight($freight);
		
		$totals = $this->cartcalc->calc();

		$this->m_carts->name = $product->name;
	  $this->m_carts->createdAt = date("Y-m-d H:i:s");
		$this->m_carts->itemPrice = $totals->itemPrice;
		$this->m_carts->freightTotal = $totals->freightTotal;
		$this->m_carts->taxRate = $totals->taxRate;
		$this->m_carts->usersID = $this->user->id();
		$this->m_carts->productsID = $json->productsID;
		$this->m_carts->qtyTotal = $json->qtyTotal;
		

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
		
		$this->m_product_metas->productsID = $json->productsID;
		$this->m_product_metas->qtyTotal = $json->qtyTotal;
		$pricePoint = $this->m_product_metas->getPricePoint();

		if (!$pricePoint) die(header('HTTP/1.0 400 Bad Request'));

		$this->m_metas->schemaName = "tax";
		$tax = $this->m_metas->fetchKVPairObj();
		$gst = isset($tax->GST) ? $tax->GST : 10;

		$this->m_products->id = $json->productsID;

		$product = $this->m_products->getSupplierCzone();

		$product->productsID = $json->productsID;
		$product->taxRate = $gst;
		$product->itemPrice = $pricePoint->metaValue;
		$product->qtyTotal = $json->qtyTotal;

		$this->cartcalc->product($product);

		$this->m_chargeouts->xto = $this->user->czone();
		$this->m_chargeouts->xfrom = $product->czone;
		$freight = reset($this->m_chargeouts->fetch());

		$this->cartcalc->freight($freight);
		
		$totals = $this->cartcalc->calc();

		$this->m_carts->name = $product->name;
	  $this->m_carts->createdAt = date("Y-m-d H:i:s");
		$this->m_carts->itemPrice = $totals->itemPrice;
		$this->m_carts->freightTotal = $totals->freightTotal;
		$this->m_carts->taxRate = $totals->taxRate;
		$this->m_carts->usersID = $this->user->id();
		$this->m_carts->productsID = $json->productsID;
		$this->m_carts->qtyTotal = $json->qtyTotal;

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