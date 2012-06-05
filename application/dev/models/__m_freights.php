<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_freights extends MM_Model {

  function __construct() {
	$this->pk = 'freightID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
      'productsID',
      'baseRate',
      'itemRate',
      'flatRate',
	  'minCharge'
    );
  }
  
  function getProduct() {
	
	if (!$this->productsID) throw new Exception('No productID supplied.[m_freights:getProduct]');
	
	$this->db->where('productsID',$this->productsID);
	$result = $this->fetch();
	
	if (count ($result)) return reset($result);
	
	return false;
  }
  
  function deleteProduct() {
	
	if (!$this->productsID) throw new Exception('No productID supplied.[m_freights:deleteProduct]');
	
	$this->db->where('productsID',$this->productsID);
	$this->db->delete('freights');
  }
  
}