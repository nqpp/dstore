<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cart_items extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_cart_items');
  }

  function renderHTML() {
	
  }

  function formJSONNew() {

	$json = json_decode(file_get_contents('php://input'));

	// Cannot have multiple instances of cart items in DB
	// Check for prescence.
	$this->m_cart_items->cartsID = $json->cartsID;
	$this->m_cart_items->productsID = $json->productsID;

	$test = $this->m_cart_items->fetch();
	$this->m_cart_items->reset();

	foreach ($json as $k => $v) {
	  $this->m_cart_items->{$k} = $v;
	}

	if (count($test) > 0) {

	  // Already exists in Db, update
	  $this->m_cart_items->id = $test[0]->cartItemID;
	  $this->m_cart_items->update();
	} else {

	  // Doesn't exist, create.
	  $this->m_cart_items->add();
	}

	$row = $this->m_cart_items->get();
	print json_encode($row);
  }

  function formJSONEntity() {

	$this->m_cart_items->id = $this->entityID;
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k => $v) {
	  $this->m_cart_items->{$k} = $v;
	}

	$this->m_cart_items->update();
	$row = $this->m_cart_items->get();
	print json_encode($row);
  }

}