<?php

/*
 *	Cartcalc class
 * 
 *	Calculate data for a cart item based on product data and freight data
 *	submittted to the class. 
 *	Product object passed as argument to product method.
 *	Freight object passed as argument to freight method.
 *	Result returned via calc() method.
 * 
 *	Multiple cart items and freight rows accommodated. 
 *	Submit products array / cart items to carts method.
 *	Submit freights array to freights method with czone as array index.
 * 
 */

class Cartcalc {
  
  private $product = false;
  private $freight = false;
  
  function __construct() {
	
  }
  
  // array of products
  function products($products = false) {
	
	if ($products) $this->products = $products;
	return $this->products;
	
  }
  
  // singular product
  function product($product = false) {

	if ($product) {
	  $this->product = $product;
	  $this->set($product);
	}

	return $this->product;
	
  }
  
  // array of freight rows
  function freights($freights = false) {
	
	if ($freights) $this->freights = $freights;
	return $this->freights;
	
  }
  
  // singular freight
  function freight($freight = false) {
	
	if ($freight) {
	  $this->freight = $freight;
	  $this->set($freight);
	}

	return $this->freight;
	
  }
  
  // return freight row for a czone
  function czoneFreight($czone = false) {
	
	if (! $czone) return false;
	
	$freights = $this->freights();
	
	if (!count($freights)) return false;
	if (!isset($freights[$czone])) return false;
	
	return $freights[$czone];
	
  }
  
  
  
  /*
   * Freight variables
   */
  function xfrom($xfrom = false) {
	
	if ($xfrom) $this->xfrom = $xfrom;
	return $this->xfrom;
	
  }
  
  function xto($xto = false) {
	
	if ($xto) $this->xto = $xto;
	return $this->xto;
	
  }
  
  function basic($basic = false) {
	
	if ($basic) $this->basic = $basic;
	return $this->basic;
	
  }
  
  function unitrate($unitrate = false) {
	
	if ($unitrate) $this->unitrate = $unitrate;
	return $this->unitrate;
	
  }
  
  function mincharge($mincharge = false) {
	
	if ($mincharge) $this->mincharge = $mincharge;
	return $this->mincharge;
	
  }
  
  /*
   * Product variables
   */
  
  function productsID($productsID = false) {
	
	if ($productsID) $this->productsID = $productsID;
	return $this->productsID;
	
  }
  
  function taxRate($taxRate = false) {
	
	if ($taxRate) $this->taxRate = $taxRate;
	return $this->taxRate;
	
  }
  
  function itemPrice($itemPrice = false) {
	
	if ($itemPrice) $this->itemPrice = $itemPrice;
	return $this->itemPrice;
	
  }
  
  function qtyTotal($qtyTotal = false) {
	
	if ($qtyTotal) $this->qtyTotal = $qtyTotal;
	return $this->qtyTotal;
	
  }
  
  function cubicWeight($cubicWeight = false) {
	
	if ($cubicWeight) $this->cubicWeight = $cubicWeight;
	return $this->cubicWeight;
	
  }
  
  function deadWeight($deadWeight = false) {
	
	if ($deadWeight) $this->deadWeight = $deadWeight;
	return $this->deadWeight;
	
  }
  
  // calculated field - greater of cubic or dead weight
  function unitWeight() {
	
	if ($this->cubicWeight() > $this->deadWeight()) {
	  return $this->cubicWeight();
	}
	
	return $this->deadWeight();
	
  }
  
  // calculated field
  function gst() {
	
	$this->gst = round(($this->subtotal() + $this->freightTotal()) * $this->taxRate() / 100, 2);
	return $this->gst;
	
  }
  
  // calculated field
  function subtotal() {
	
	$this->subtotal = round($this->qtyTotal() * $this->itemPrice(), 2);
	return $this->subtotal;
	
  }
  
  // calculated field
  function freightTotal() {
	
	$this->freightTotal = round($this->unitWeight() * $this->qtyTotal() * $this->unitrate() + $this->basic(), 2);
	return $this->freightTotal;
	
  }
  
  // calculated field
  function total() {
	
	$this->total = $this->subtotal() + $this->freightTotal() + $this->gst();
	return $this->total;
	
  }
  
  
  
  function calc() {
	
	if (!$this->product()) return false;
	
	$calc->moqReached = true;
	$calc->productsID = $this->productsID();
	$calc->qtyTotal = $this->qtyTotal();
	$calc->itemPrice = $this->itemPrice();
	$calc->freightTotal = $this->freightTotal();
	$calc->subtotal = $this->subtotal();
	$calc->gst = $this->gst();
	$calc->total = $this->total();
	
	return $calc;
	
  }
  
  function calcAll() {
	
	if (! count($this->carts())) return false;
	if (! count($this->freights())) return false;
	
	$calculated = array();
	foreach ($this->carts() as $czone=>$items) {
	  $this->freight($this->czoneFreight($czone));
	  	  
	  foreach ($items as $product) {
		$this->product($product);
		$calculated[] = $this->calc();
	  }
	  
	}
	
	return $calculated;
  }
  
  function set($data) {
	
	if (! is_object($data)) {
	  throw new Exception('Argument is not object.');
	}
	
	foreach (get_object_vars($data) as $prop=>$val) {
	  if (method_exists($this,$prop)) {
		$this->$prop($val);
	  }
	}

  }
  

}
//EOF