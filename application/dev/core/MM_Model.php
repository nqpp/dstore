<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MM_Model extends CI_Model {

  var $fields = array();
  var $id = false;
  var $index = false;
  var $searchStr = false;
  var $filters = array();
  
  var $pk; // primary key
  var $className;
  var $model; // db table name
  var $do; // DataObject
  var $dbSort = array();
  
  function __construct() {
    parent::__construct();
	$this->className = get_class($this);
	
	// lcfirst function may not exist in pre 5.3 versions of PHP, so duplicate functionality.
	if(!function_exists('lcfirst')) {
		
		function lcfirst($str) {
			
			$str[0] = strtolower($str[0]);
			return $str;
		}
	}
	
	$this->model = lcfirst(str_replace(' ','',ucwords(preg_replace('/[\s_]+/', ' ', substr($this->className,2)))));
	$this->do = 'do_'.$this->model;
    $this->init();
  }
  
  // initialise properties
  private function init() {

	if (!count($this->fields())) return;
	
    foreach ($this->fields() as $f) {
      $this->$f = false;
    }
  }
  
  function fields() {
	return array();
  }
  
  function reset() {
	$this->id = false;
	$this->index = false;
	$this->init();
  }
  
  // set class properties from an object
  function set($data) {
	if (! is_object($data)) throw new Exception('Supplied argument is not an object.');
	
	foreach (get_object_vars($data) as $prop=>$val) {
	  if (property_exists($this,$prop)) {
		$this->$prop = $val;
	  }
	}
	
  }
  
  // set properties according to post value if not set already
  function postToVar() {
    foreach ($this->fields() as $f) {
      if ($this->$f === false) $this->$f = $this->input->post($f);
    }
  }

  // filter record data per class property
  function dbWhereVars() {

    foreach ($this->fields() as $f) {
      if ($this->$f === false) continue;
      $this->db->where($this->model.'.'.$f,$this->$f);
    }

  }
  
  // set record data per class property
  function dbSetVars() {

    foreach ($this->fields() as $f) {
      if ($this->$f === false) continue;
      $this->db->set($f,$this->$f);
    }

  }
  
  function setSort($key) {
	$this->dbSort[] = $key;
  }
  
  function dbSort() {
	
	if (!count($this->dbSort)) return;

	$this->db->order_by(implode(',',$this->dbSort));
  }
  
  function fetch() {

	$this->db->select($this->model.'.*');
	$this->dbSort();
	$this->dbWhereVars();
	$data = $this->db->get($this->model);

	if (!$data->num_rows()) return array();

	return $data->result();
  }

  function fetchJSON() {

	$result = $this->fetch();
	return json_encode($result);
	
  }
  
  function fetchGrouped() {
	
	if (!$this->index) throw new Exception("No index supplied.[{$this->className}:fetchGrouped]");
	
	$result = $this->fetch();

	if (!count($result)) return array();
	
	$grouped = array();
	foreach ($result as $row) {
	  $grouped[$row->{$this->index}][] = $row;
	}
	
	return $grouped;
  }
  
  function fetchGroupedJSON() {

	$result = $this->fetchGrouped();
	return json_encode($result);
	
  }
  
  function fetchIndexed() {

	if (!$this->index) $this->index = $this->pk;
	
	$result = $this->fetch();

	if (!count($result)) return array();
	
	$indexed = array();
	foreach ($result as $row) {
	  $indexed[$row->{$this->index}] = $row;
	}
	
	$this->index = false;
	return $indexed;
  }
  
  function fetchIndexedJSON() {

	$result = $this->fetchIndexed();
	return json_encode($result);
	
  }
  
  
  function get() {
	
	if (!$this->id) throw new Exception("No id supplied.[{$this->className}:get]");
	
	$this->db->select($this->model.'.*');
	$this->db->where($this->pk,$this->id);
	$data = $this->db->get($this->model);

    if (!$data->num_rows()) throw new Exception("No record for ID.[{$this->className}:get]");

	return $data->row();	
  }
  
  function getJSON() {
	
	$row = $this->get();
	return json_encode($row);
	
  }
 
  function add() {
	
    $this->postToVar();
    $this->dbSetVars();

    $this->db->insert($this->model);
    $this->id = $this->db->insert_id();
  }
  
  /*
   * replaces add() when dao in use
   */
  function post($dao = false) {
	
	if ($dao == false || !is_object($dao)) throw new Exception("Data Access Object incorrect.[{$this->className}:post]");
	
	foreach ($this->fields as $f) {
	  if ($dao->{$f}()) {
		$this->db->set($dao->{$f}());
	  }
	}
	
    $this->db->insert($this->model);
    $this->id = $this->db->insert_id();
  }
 
  function update() {
	
	if (!$this->id) throw new Exception("No id supplied.[{$this->className}:update]");

	$this->postToVar();
    $this->dbSetVars();
	$this->db->where($this->pk,$this->id);
    $this->db->update($this->model);
  }
  
  function delete() {
	
	if (!$this->id) throw new Exception("No id supplied.[{$this->className}:update]");
	
	$this->db->where($this->pk,$this->id);
    $this->db->delete($this->model);
  }
 
}