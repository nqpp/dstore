<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cart_items extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_cart_items');
  }

  function renderHTML() {
	
  }

  function formJSONNew() {

		$json = json_decode(file_get_contents('php://input'));

		$this->load->model('m_carts');
		$this->m_carts->usersID = User::id();
		$this->m_carts->productsID = $json->parentID;
	
		$cart = $this->m_carts->fetch();
	
		$json->cartsID = $cart[0]->cartID;
		$json->productsID = $json->productID;

		foreach ($json as $k => $v) {
		  $this->m_cart_items->{$k} = $v;
		}
	
		$this->m_cart_items->add();
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

	function renderJSONDelete() {
		
		$this->m_cart_items->id = $this->entityID;
		$this->m_cart_items->delete();
	}

}