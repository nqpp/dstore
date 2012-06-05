<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/DataObject.php');

class DO_client_contact_metas extends DataObject {

  var $defaultImg = 'no_image.png';
  
  function clientContactMetaID($clientContactMetaID = false) {
	
	if($clientContactMetaID) $this->row->clientContactMetaID = $clientContactMetaID;
	return $this->row->clientContactMetaID;
  }
  
  function clientContactsID($clientContactsID = false) {
	
	if($clientContactsID) $this->row->clientContactsID = $clientContactsID;
	return $this->row->clientContactsID;
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