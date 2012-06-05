<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/do_products.php');

class M_products extends MM_Model {

  function __construct() {
	$this->pk = 'productID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
      'parentID',
      'suppliersID',
      'category',
      'code',
      'name',
      'description',
      'perCarton',
      'cubicWeight',
      'deadWeight',
	  'sort'
    );
  }
  

  function fetchDO() {
	
	$this->setSort('products.sort');
    $result = parent::fetch();
	
	$doResult = array();
	foreach ($result as $row) {
	  $doResult[] = new DO_products($row);
	}
	
	return $doResult;
  }
  
  function fetchWithImage() {
	
	$select = "(
	  SELECT metaKey 
	  FROM productMetas 
	  WHERE productsID = productID AND schemaName = 'image' AND sort = 1 
	  GROUP By sort
	  ) as img, ";
	
	$select .= "(
	  SELECT SUBSTRING(metaKey,1,CHAR_LENGTH(metaKey)-4) 
	  FROM productMetas 
	  WHERE productsID = productID AND schemaName = 'image' AND sort = 1 
	  GROUP By sort
	  ) as imgDir ";
	
	$this->db->select($select,false);
	
	return $this->fetch();
	
  }
  
  function fetchWithImageDO() {
	
	$result = $this->fetchWithImage();
	
	$doResult = array();
	foreach ($result as $row) {
	  $doResult[] = new DO_products($row);
	}
	
	return $doResult;
	
  }
  
  function fetchWithImageJSON() {
	
	$result = $this->fetchWithImage();
	return json_encode($result);
	
  }
  
  function fetchSubProducts() {
	
	$this->parentID = $this->id;
	return $this->fetch();
  }
  
  function fetchSubProductsJSON() {
	
	$result = $this->fetchSubProducts();
	return json_encode($result);
	
  }
  
  function getDO() {
	$row = parent::get();
	$doRow = new DO_products($row);
	return $doRow;
  }
  
  function getJoined() {
	
	$this->db->select('suppliers.name as supplier');
	$this->db->join('suppliers', 'supplierID = suppliersID', 'left outer');
	return $this->get();
  }
  
  function getJoinedJSON() {
	return json_encode($this->getJoined());
  }
  
}