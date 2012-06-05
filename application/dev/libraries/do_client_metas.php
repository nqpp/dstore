<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/DataObject.php');

class DO_client_metas extends DataObject {

  var $defaultImg = 'no_image.png';
  
  function clientMetaID($clientMetaID = false) {
	
	if($clientMetaID) $this->row->clientMetaID = $clientMetaID;
	return $this->row->clientMetaID;
  }
  
  function clientsID($clientsID = false) {
	
	if($clientsID) $this->row->clientsID = $clientsID;
	return $this->row->clientsID;
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
  
}
// EOF