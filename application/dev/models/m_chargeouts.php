<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_chargeouts extends MM_Model {

  var $xfroms = false;

  function __construct() {
	$this->pk = 'chargeoutID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'xfrom',
      'xto',
      'basic',
	  'unitrate'
    );
  }
  
  function fetchIndexedForCalc() {
	
	if (!$this->xfroms || !count($this->xfroms)) return;
	
	$this->db->where_in('xfrom', $this->xfroms);
	return $this->fetchIndexed();
	
  }
  
}
// EOF