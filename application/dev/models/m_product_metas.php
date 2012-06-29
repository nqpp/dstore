<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/do_product_metas.php');

class M_product_metas extends MM_Model {

  public $qtyTotal = false;
  
  function __construct() {
	$this->pk = 'productMetaID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  private function fields() {
    return array(
      'productsID',
      'schemaName',
      'metaKey',
      'metaValue',
	  'sort'
    );
  }
  
  
  function fetch() {

	$this->setSort('productMetas.sort');
    return parent::fetch();
	
  }
  
  function fetchDO() {

    $result = $this->fetch();
	
	$doResult = array();
	foreach ($result as $row) {
	  $doResult[] = new DO_product_metas($row);
	}
	
	return $doResult;
  }
  
  function fetchIndexedDO() {

	if (!$this->index) $this->index = $this->pk;
	
	$result = $this->fetchDO();

	if (!count($result)) return array();
	
	$indexed = array();
	foreach ($result as $row) {
	  $indexed[$row->{$this->index}()] = $row;
	}
	
	$this->index = false;
	return $indexed;
  }
  
  function fetchAllForProduct() {
	
	if (!$this->productsID) throw new Exception("No id supplied.[{$this->className}:fetchAllForProduct]");
	
	$this->index = 'schemaName';
	return $this->fetchGrouped();
	
  }
  
  // used for store cart calculation to determine price point for qty
  // return row on success or false on fail
  function getPricePoint() {
	
	if (!$this->productsID) return false;
	if (!$this->qtyTotal) return false;

	$this->index = "metaKey";
	$this->db->where('schemaName', 'price');
	$this->db->where('productsID', $this->productsID);
	$this->db->order_by('sort');
	$result = $this->fetchIndexed();

	if(! count($result)) return false;

	$point = 0;
	foreach ($result as $row) {
	  if ($row->metaKey <= $this->qtyTotal && $row->metaKey > $point) $point = $row->metaKey;
	}

	if (!isset($result[$point])) return false;

	return $result[$point];

  }
  
  
  function deleteProduct() {
	
	if (!$this->productsID) throw new Exception("No id supplied.[{$this->className}:deleteProduct]");
	
	$metas = $this->fetchAllForProduct();
	
	if (isset($metas['image'])) {
	  foreach ($metas['image'] as $img) {
		$this->id = $img->productMetaID;
		$this->deleteImage();
	  }
	}
	
	$this->db->where('productsID',$this->productsID);
	$this->db->delete('productMetas');
	
  }
  
  function deleteImage() {
	
	require_once(APPPATH.'libraries/IMagick_Image.php');
	$util = new ImageUtil();
	
	$this->config->load('images');
	$config = $this->config->item('images');
	
	$row = $this->get();
	$filename = $row->metaKey;
	$_directory = implode('.', array_slice(explode('.', $filename), 0, -1)).'/';
	$directory = $config['topDirectory'].$_directory;
	$util->removeDir($directory);
	
	return parent::delete();
  }
  
  function images() {
	
	if (!$this->productsID) throw new Exception("No productsID supplied.[{$this->className}:images]");
	
	$this->schemaName = 'image';
	$images = $this->fetchDO();
	$this->schemaName = false;
	return $images;
	
  }
  
  function colours() {
	
	if (!$this->productsID) throw new Exception("No productsID supplied.[{$this->className}:images]");
	
	$this->schemaName = 'colour';
	$images = $this->fetch();
	$this->schemaName = false;
	return $images;
	
  }
  
  function prices() {
	
	if (!$this->productsID) throw new Exception("No productsID supplied.[{$this->className}:prices]");
	
	$this->index = 'metaKey';
	$this->schemaName = 'price';
	$prices = $this->fetchIndexedDO();
	$this->schemaName = false;
	$this->index = false;
	
	return $prices;
	
  }
  
  function priceJSON() {
	
	if (!$this->productsID) throw new Exception("No productsID supplied.[{$this->className}:priceJSON]");

	$this->schemaName = 'price';
	$result = $this->fetch();
	
	return json_encode($result);
  }
  
  function colourJSON() {
	
	if (!$this->productsID) throw new Exception("No productsID supplied.[{$this->className}:colourJSON]");

	$this->schemaName = 'colour';
	$result = $this->fetch();
	
	return json_encode($result);
  }
  
  function imageJSON() {
	
	if (!$this->productsID) throw new Exception("No productsID supplied.[{$this->className}:imageJSON]");
	
	$this->db->select("SUBSTRING(metaKey,1,CHAR_LENGTH(metaKey)-4) as imgDir", false);
	$this->schemaName = 'image';
	$result = $this->fetch();
	return json_encode($result);
  }
  
 
}