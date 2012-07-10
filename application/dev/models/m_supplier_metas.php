<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_supplier_metas extends MM_Model {
  

  function __construct() {
	$this->pk = 'supplierMetaID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
	  'suppliersID',
	  'schemaName',
	  'metaKey',
	  'metaValue',
	  'sort'
    );
  }
  
  function fetchPhone() {
	
	$this->schemaName = "phone";
	return $this->fetch();
	
  }
  
  function fetchPhoneJSON() {
	
	return json_encode($this->fetchPhone());
	
  }
  
  function deleteSupplier() {

	if (!$this->suppliersID) throw new Exception('No supplierID supplied.[m_supplier_metas:deleteSupplier]');
	
	$this->db->where('suppliersID',$this->suppliersID);
	$this->db->delete('supplierMetas');
	
  }
  
  
}