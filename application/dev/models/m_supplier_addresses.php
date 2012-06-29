<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_supplier_addresses extends MM_Model {
  

  function __construct() {
	$this->pk = 'supplierAddressID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
	  'suppliersID',
	  'type',
	  'locationsID',
	  'address',
	  'city',
	  'state',
	  'postcode',
	  'sort'
    );
  }
  
  function getSupplierDispatchLocation() {
	
	if (!$this->suppliersID) throw new Exception('No supplierID supplied.[m_supplier_addresses:getSupplierDispatchLocation]');
	
	$this->db->select('locations.czone');
	$this->db->join('locations', 'locationID = locationsID', 'left outer');
	$this->db->where('type','dispatch');
	$result = $this->fetch();
	
	if (!count($result)) return false;
	
	return reset($result);
	
  }
  
  function deleteSupplier() {

	if (!$this->suppliersID) throw new Exception('No supplierID supplied.[m_supplier_addresses:deleteSupplier]');
	
	$this->db->where('suppliersID',$this->suppliersID);
	$this->db->delete('supplierAddresses');
	
  }
  
  
}