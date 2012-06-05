<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/DataObject.php');

class DO_products extends DataObject {
  
  var $defaultImg = 'no_image.png';
  
  function productID($productID = false) {
	
	if($productID) $this->row->productID = $productID;
	return $this->row->productID;
  }
  
  function name($name = false) {
	
	if($name) $this->row->name = $name;
	return $this->row->name;
  }
  
  function description($description = false) {
	
	if($description) $this->row->description = $description;
	return $this->row->description;
  }
  
  function sort($sort = false) {
	
	if($sort) $this->row->sort = $sort;
	return $this->row->sort;
  }
  
  function img($img = false) {
	
	if($img) {
	  $this->row->img = $img;
	}
	else if (!$this->row->img) {
	  $this->row->img = $this->defaultImg;	  
	}
	return $this->row->img;
  }
  
  function imgDir($imgDir = false) {
	
	if($imgDir) {
	  $this->row->imgDir = $imgDir;
	}
	else if ($this->row->img) {
	  $this->row->imgDir = array_shift(explode('.',$this->row->img));
	}
	else {
	  $this->row->imgDir = array_shift(explode('.',$this->defaultImg));
	}
	  
	return $this->row->imgDir;
  }

}