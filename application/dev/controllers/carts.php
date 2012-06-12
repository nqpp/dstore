<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Carts extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_carts');
	$this->load->model('m_cart_items');
  }

  function renderHTML() {
	
  }

	function renderJSON() {
		
		$data = $this->m_carts->fetchUserCart(); 
		
		$rtn = array();
		foreach($data as $item) $rtn[] = $item;
		
		echo json_encode($rtn);
	}

  function formJSONNew() {

	$json = json_decode(file_get_contents('php://input'));

	// Cannot have multiple instances of product in multiple carts.
	// Check for product in cart for user
	$this->m_carts->usersID = User::id();
	$this->m_carts->productsID = $json->productsID;

	$test = $this->m_carts->fetch();
	$this->m_carts->reset();

	foreach ($json as $k => $v) {
	  $this->m_carts->{$k} = $v;
	}

	if (count($test) > 0) {

	  // Product already in cart, grab id and update.
	  $cartID = $test[0]->cartID;

	  $this->m_carts->id = $cartID;
	  $this->m_carts->update();
	} else {

	  // Product not in cart, create new row.
	  $this->m_carts->usersID = User::id();
	  $this->m_carts->createdAt = date("Y-m-d H:i:s");

	  $this->m_carts->add();
	}

	$row = $this->m_carts->get();
	print json_encode($row);
  }

  function formJSONEntity() {

	$this->m_carts->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k => $v) {
	  $this->m_carts->{$k} = $v;
	}

	$this->m_carts->update();
	$row = $this->m_carts->get();
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