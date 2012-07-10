<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_order_addresses extends MM_Model {

  public $userAddress = array();
  
  function __construct() {
	$this->pk = 'orderAddressID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'ordersID',
      'address',
      'city',
      'state',
	  'postcode'
    );
  }
  
  function addAddress() {
	
	if (!$this->userAddress || !count($this->userAddress)) throw new Exception('No address data to add to order[m_order_addreses:addAddress]');
	
	$this->locationsID = $this->userAddress->locationsID;
	$this->address = $this->userAddress->address;
	$this->city = $this->userAddress->city;
	$this->state = $this->userAddress->state;
	$this->postcode = $this->userAddress->postcode;
	
	$this->add();
	
  }
  
  function reset() {
	$this->db->truncate('orderAddresses');	
  }

}