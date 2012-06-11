<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_metas extends MM_Model {

  var $metaKeys = false;

  function __construct() {
	$this->pk = 'metaID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'schemaName',
      'schemaID',
      'metaKey',
      'metaValue',
	  'sort'
    );
  }
  
  function fetch() {
	
	$this->setSort('metas.sort');
	$this->setSort('metas.schemaName');
	return parent::fetch();
  }
  
  function fetchGrouped() {
	
	$this->setSort('metas.sort');
	$this->index = 'schemaName';
	$this->dbWhereVars();
	return parent::fetchGrouped();
  }

  function fetchSchemas() {

    $this->index = 'schemaName';
    $result = $this->fetchIndexed();
    return array_keys($result);
  }
  
  function fetchKVPair() {

    $result = $this->fetch();

	if (!count($result)) return array();

    $pair = array();
    foreach ($result as $row) {
      $pair[$row->metaKey] = $row->metaValue;
    }

    return $pair;
  }

  function fetchKVPairObj() {

    $result = $this->fetchKVPair();
	if (!count($result)) return array();
	
	return (object)$result;
	
  }

  function add() {

    $this->postToVar();
    
    if(!$this->schemaName) throw new Exception('No SchemaName supplied.[m_meta:add]');
    if ($this->schemaName == 'other') $this->schemaName = $this->input->post('schemaNameNew');

    $this->dbSetVars();
    
    $this->db->insert('metas');
    $this->id = $this->db->insert_id();
  }

  function delete_schema() {

    if(!$this->schemaName) throw new Exception('No schemaName supplied.[m_metas:delete_schema]');

    $this->db->where('schemaName',$this->schemaName);
    $this->db->delete('metas');
  }

  function filter() {
    $this->schemaName = $this->input->get('schemaName');
  }

  function sort() {

    $sort = $this->input->get('sort');

	if ($sort) {
	  $dir = 'asc';

	  if (substr($sort,0,1) == '-') {
		$dir = 'desc';
		$sort = substr($sort,1);
	  }

	  $this->db->order_by($sort,$dir);
	}

  }

  
  function make() {
	$sql = 'TRUNCATE TABLE metas';
	$this->db->query($sql);
	
	$sql = 'INSERT INTO metas (schemaName,metaKey,metaValue,sort) VALUES ';
	
	$f = true;
	foreach ($this->defaultData() as $d) {
	  $sql .= $f ? '':',';
	  $sql .= "('{$d['schemaName']}','{$d['metaKey']}','{$d['metaValue']}',{$d['sort']})";
	  
	  $f = false;
	}
	$sql .= ';';
	$this->db->query($sql);
  }

  private function defaultData() {
	return array(
	  array('schemaName'=>'adminGroup','metaKey'=>1,'metaValue'=>'Admin','sort'=>1),
	  array('schemaName'=>'adminGroup','metaKey'=>2,'metaValue'=>'User','sort'=>2),
	  array('schemaName'=>'states','metaKey'=>'ACT','metaValue'=>'Australian Capital Territory','sort'=>1),
	  array('schemaName'=>'states','metaKey'=>'QLD','metaValue'=>'Queensland','sort'=>2),
	  array('schemaName'=>'states','metaKey'=>'NSW','metaValue'=>'New South Wales','sort'=>3),
	  array('schemaName'=>'states','metaKey'=>'VIC','metaValue'=>'Victoria','sort'=>4),
	  array('schemaName'=>'states','metaKey'=>'TAS','metaValue'=>'Tasmania','sort'=>5),
	  array('schemaName'=>'states','metaKey'=>'NT','metaValue'=>'Northern Territory','sort'=>6),
	  array('schemaName'=>'states','metaKey'=>'SA','metaValue'=>'South Australia','sort'=>7),
	  array('schemaName'=>'states','metaKey'=>'WA','metaValue'=>'Western Australia','sort'=>8),
	  array('schemaName'=>'adminGroupTemplate','metaKey'=>'1','metaValue'=>'page_default','sort'=>1)
	);
  }
}
// EOF