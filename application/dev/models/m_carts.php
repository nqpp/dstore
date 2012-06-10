<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_carts extends MM_Model {

  function __construct() {
	$this->pk = 'cartID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
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
	return json_encode($this->fetchUserCart());
  }
  
  function deleteUserCart() {
	
	$this->db->where('usersID', $this->user->id());
	$this->db->delete('carts');

  }
  
  
}