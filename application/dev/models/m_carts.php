<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_carts extends MM_Model {

  function __construct() {
	$this->pk = 'cartID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'usersID',
      'createdAt',
      'productsID',
      'name',
      'qtyTotal',
      'itemPrice',
      'freightTotal',
      'taxRate'
    );
  }

  function fetchUserCart() {
	
	$this->usersID = $this->user->id();
	$cart = $this->fetchIndexed();
	return $cart;
  }

	function fetchUserCartJSON() {
	
		$cart = $this->fetchUserCart();
		return json_encode($cart);
  }
  
  function fetchGroupedForCalculated() {
	
	$this->db->select('cubicWeight,deadWeight');
	$this->db->join('products', 'productID = productsID', 'left outer');
	
	$this->db->join('supplierAddresses', 'products.suppliersID = supplierAddresses.suppliersID', 'left outer');
	$this->db->where('type','Dispatch');
	
	$this->db->select('czone');
	$this->db->join('locations', 'locationID = locationsID', 'left outer');
	
	return $this->fetchGrouped();
	
  }
  

	function fetchUserCartTotalled() {
		
		$this->usersID = $this->user->id();
		$cart = $this->fetch();

		if(count($cart)) {
			
			$cart = $cart[0];
			
			$this->qtyTotal = $cart->qtyTotal;
			if($this->reachedMOQ()) {

				$totals = $this->calculateTotals();
				foreach($totals as $k=>$v) $cart->{$k} = $v;
			}
		}

		return $cart;
	}
  
  function fetchUserCartTotalledJSON() {
		
		$cart = $this->fetchUserCartTotalled();
		return json_encode($cart);
	}
  
  function deleteUserCart() {
	
	$this->db->where('usersID', $this->user->id());
	$this->db->delete('carts');

  }
  
  function reachedMOQ() {
	
		if(!$this->productsID || !$this->qtyTotal) return false;
	
		$this->db->select('metaKey as qty');
		$this->db->where('schemaName', 'price');
		$this->db->where('productsID', $this->productsID);
		$this->db->order_by('qty');
		$this->db->limit(1);
		$result = $this->db->get('productMetas');
		
		$moqArr = $result->row();

		if($this->qtyTotal < $moqArr->qty) return false;
		return true;
	}
	
	function calculateTotals() {
		
		if(!$this->productsID || !$this->qtyTotal) return false;
		
		$cart = new stdclass;
		
		// Prefetch data
		$this->db->where('productID', $this->productsID);
		$result = $this->db->get('products');
		$product = $result->row();
		
		$this->db->where('suppliersID', $product->suppliersID);
		$this->db->where('zonesID', $this->user->zoneID());
		$result = $this->db->get('supplierFreights');
		$freightTable = $result->row();

		$this->db->where('metaKey', 'GST');
		$result = $this->db->get('metas');
		$result = $result->row();

		$result = count($result) ? $result->metaValue : 10; // If no result, default to 10% (GST rate as at 06/12, or until those filthy conservatives get into power...)
		$gst = $result / 100;
		
		$this->db->where('productsID', $this->productsID);
		$this->db->where('schemaName', 'price');
		$this->db->order_by('metaKey');
		$result = $this->db->get('productMetas');
		$prices = $result->result();
		
		// Find price point
		$cart->itemPrice = NULL; // Set to null initially; if an appropriate price point matches, this will be overwritten.

		foreach($prices as $price) {

			if($this->qtyTotal >= $price->metaKey) $cart->itemPrice = $price->metaValue;
		}
				
		// Calculate weighted total		
		$itemWeight = ($product->cubicWeight > $product->deadWeight) ? $product->cubicWeight : $product->deadWeight;
		$totalWeight = $itemWeight * $this->qtyTotal;
		$freightTotal = ($totalWeight * $freightTable->kgRate) + $freightTable->baseRate;
		
		// Minimum charge
		if($freightTotal < $freightTable->minCharge) $freightTotal = $freightTable->minCharge;
		
		$cart->freightTotal = money_format('%.2n', $freightTotal);
		
		// Totals, for JSON response.
		$cart->taxRate = $gst;
		$cart->subtotal = money_format('%.2n', $cart->itemPrice * $this->qtyTotal);
		$cart->gst = money_format('%.2n', ($cart->subtotal + $cart->freightTotal) * $gst);
		$cart->total = money_format('%.2n', $cart->subtotal + $cart->freightTotal + $cart->gst);
		
		return $cart;
	}

	
}
