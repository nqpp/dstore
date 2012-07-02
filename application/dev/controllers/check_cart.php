<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Check_cart extends MM_Controller {

  function __construct() {

	parent::__construct();
	$this->load->model('m_product_metas');
	$this->load->model('m_metas');
	$this->load->model('m_products');
	$this->load->model('m_chargeouts');

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

	die(print (json_encode($this->cartcalc->calc())));
	
  }

  function formJSONEntity() {

	$this->m_carts->id = $this->entityID;
	return $this->formJSONNew();
  }
  
}