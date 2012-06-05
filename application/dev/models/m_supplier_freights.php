<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_supplier_freights extends MM_Model {
  
  public $suppliers = array();
  public $zones = array();

  function __construct() {
	$this->pk = 'supplierFreightID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
	  'suppliersID',
	  'zonesID',
	  'baseRate',
	  'kgRate',
	  'flatRate',
	  'minCharge'
    );
  }
  
  function fetchJoined() {
	
	if ($this->suppliersID !== false) return $this->fetchZones();
	if ($this->zonesID !== false) return $this->fetchSuppliers();
	
  }
  
  function fetchZones() {
	
	$this->db->select("zones.*");
	$this->db->join('zones', 'zoneID = zonesID', 'left outer');
	
	return $this->fetch();
  }
  
  function fetchSuppliers() {
	
	$this->db->select("suppliers.*");
	$this->db->join('suppliers', 'supplierID = suppliersID', 'left outer');
	
	return $this->fetch();
  }
  
  function getJoined() {
	
	if ($this->suppliersID !== false && $this->zonesID !== false) return $this->getRow();
	if ($this->suppliersID !== false) return $this->getZones();
	if ($this->zonesID !== false) return $this->getSuppliers();
	
  }
  
  function getRow() {
	$row = reset($this->fetch());
	return $row;
  }
  
  function getZones() {
	
	$this->db->select("zones.*");
	$this->db->join('zones', 'zoneID = zonesID', 'left outer');
	
	return $this->get();
  }
  
  function getSuppliers() {
	
	$this->db->select("suppliers.*");
	$this->db->join('suppliers', 'supplierID = suppliersID', 'left outer');
	
	return $this->get();
  }
  
  function addZone() {
	
	if (!count($this->suppliers)) return;
	
	foreach (array_keys($this->suppliers) as $suppliersID) {
	  $this->suppliersID = $suppliersID;
	  $this->add();
	}
	
  }
  
  function addSupplier() {
	
	if (!count($this->zones)) return;
	
	foreach (array_keys($this->zones) as $zonesID) {
	  $this->zonesID = $zonesID;
	  $this->add();
	}
	
  }
  
  function deleteSupplier() {

	if (!$this->suppliersID) throw new Exception('No supplierID supplied.[m_supplier_zones:deleteSupplier]');
	
	$this->db->where('suppliersID',$this->suppliersID);
	$this->db->delete('supplierFreights');
	
  }
  
  function deleteZone() {
	
	if (!$this->zonesID) throw new Exception('No zoneID supplied.[m_supplier_zones:deleteZone]');
	
	$this->db->where('zonesID',$this->zonesID);
	$this->db->delete('supplierFreights');
	
  }
  
}