<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/DataObject.php');

class DO_product_metas extends DataObject {

  var $defaultImg = 'no_image.png';
  
  function productMetaID($productMetaID = false) {
	
	if($productMetaID) $this->row->productMetaID = $productMetaID;
	return $this->row->productMetaID;
  }
  
  function productsID($productsID = false) {
	
	if($productsID) $this->row->productsID = $productsID;
	return $this->row->productsID;
  }
  
  function schemaName($schemaName = false) {
	
	if($schemaName) $this->row->schemaName = $schemaName;
	return $this->row->schemaName;
  }
  
  function metaKey($metaKey = false) {
	
	if($metaKey) $this->row->metaKey = $metaKey;
	return $this->row->metaKey;
  }
  
  function metaValue($metaValue = false) {
	
	if($metaValue) $this->row->metaValue = $metaValue;
	return $this->row->metaValue;
  }
  
  function sort($sort = false) {
	
	if($sort) $this->row->sort = $sort;
	return $this->row->sort;
  }
  
  function img($img = false)  {
	return $this->metaKey($img);
	
  }
  
  function imgDir($imgDir = false) {
	
	if($imgDir) $this->row->imgDir = $imgDir;
	else if ($this->row->metaKey) $this->row->imgDir = array_shift(explode('.',$this->row->metaKey));
	else $this->row->imgDir = array_shift(explode('.',$this->defaultImg));
	  
	return $this->row->imgDir;
  }
  
}
// EOF