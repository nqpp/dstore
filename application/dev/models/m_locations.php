<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_locations extends MM_Model {
  
  private $filter = false;
  private $limit = false;

  function __construct() {
	$this->pk = 'locationID';
    parent::__construct();
	$this->setFilter();
	$this->setLimit();
  }

  // db field names
  function fields() {
    return array(
	  'postcode',
	  'suburb',
	  'depot',
	  'directzone',
	  'onforwardzone',
	  'czone'
    );
  }
  
  /*
   * fields used to filter results
   */
  private function filterFields() {
    return array(
	  'suburb'
    );
  }

  function setFilter() {
	
	if (!$this->filter) {
	  $this->filter = $this->input->get('filter');
	}
	
	if (!$this->filter) {
	  $this->filter = 'num';
	}
	
  }
  
  function getFilter() {
	return $this->filter;
  }
  
  function filter() {
	
	if (!$this->filter) return;
	
	if ($this->filter == 'num') {
	  $filter = "REGEXP '^[0-9]'";
	}
	else {
	  $filter = "LIKE '{$this->filter}%'";
	}
	
	$sql = '(';

	$f = true;
	foreach ($this->filterFields() as $field) {
	  $sql .= $f ? '': ' OR ';
	  $sql .= "$field $filter";
	  $f = false;
	}

	$sql .= ')';
	
	$this->db->where($sql);
	
  }
  
  function setLimit() {
	
	if (!$this->limit) {
	  $this->limit = $this->input->get('limit');
	}
	
  }
  
  function limit() {
	
	if (! $this->limit) return;
	
	$this->db->limit($this->limit);
	
  }
  
}