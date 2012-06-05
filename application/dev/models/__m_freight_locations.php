<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_freight_locations extends MM_Model {
  
  public $freights = array();
  public $locations = array();

  function __construct() {
	$this->pk = 'freightLocationID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
	  'freightsID',
	  'locationsID',
	  'baseRateMultiplier',
	  'itemRateMultiplier',
	  'flatRateMultiplier',
	  'minChargeMultiplier'
    );
  }
  
  function fetchJoined() {
	
	if ($this->freightsID !== false) return $this->fetchLocations();
	if ($this->locationsID !== false) return $this->fetchFreights();
	
  }
  
  function fetchLocations() {
	
	$this->db->select("locations.*");
	$this->db->join('locations', 'locationID = locationsID', 'left outer');
	
	return $this->fetch();
  }
  
  function fetchFreights() {
	
	$this->db->select("freights.*");
	$this->db->join('freights', 'freightID = freightsID', 'left outer');
	
	$this->db->select("products.*");
	$this->db->join('products', 'productID = productsID', 'left outer');
	
	return $this->fetch();
  }
  
  function addLocation() {
	
	if (!count($this->freights)) return;
	
	foreach (array_keys($this->freights) as $freightsID) {
	  $this->freightsID = $freightsID;
	  $this->add();
	}
	
  }
  
  function addFreight() {
	
	if (!count($this->locations)) return;
	
	foreach (array_keys($this->locations) as $locationsID) {
	  $this->locationsID = $locationsID;
	  $this->add();
	}
	
  }
  
  function deleteFreight() {
	
	if (!$this->freightsID) throw new Exception('No freightID supplied.[m_freight_locations:deleteFreight]');
	
	$this->db->where('freightsID',$this->freightsID);
	$this->db->delete('freight_locations');
	
  }
  
  function deleteLocation() {
	
	if (!$this->locationsID) throw new Exception('No locationID supplied.[m_freight_locations:deleteLocation]');
	
	$this->db->where('locationsID',$this->locationsID);
	$this->db->delete('freight_locations');
	
  }
  
}