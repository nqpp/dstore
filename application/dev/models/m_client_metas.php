<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/do_client_metas.php');

class M_client_metas extends MM_Model {
  
  function __construct() {
	
	$this->pk = 'clientMetaID';
	$this->fields = $this->fields();
    parent::__construct();
	
  }
  
  // db field names
  function fields() {
    return array(
      'clientsID',
      'schemaName',
      'metaKey',
      'metaValue',
	  'sort'
    );
	
  }
  
  function fetch() {

	$this->setSort('clientMetas.sort');
    $result = parent::fetch();
	
	$doResult = array();
	foreach ($result as $row) {
	  $doResult[] = new DO_client_metas($row);
	}
	
	return $doResult;
  }
  
  function fetchAddress() {
	
	$this->setSort('clientMetas.sort');
	$this->db->select('clientMetaID as entityID');
	$this->db->like('metaKey','address%');
	return $this->fetch();
	
  }
  
  function fetchPhone() {
	
	$this->setSort('clientMetas.sort');
	$this->db->select('clientMetaID as entityID');
	$this->db->like('metaKey','phone%');
	return $this->fetch();
	
  }
  
  function fetchEmail() {
	
	$this->setSort('clientMetas.sort');
	$this->db->select('clientMetaID as entityID');
	$this->db->like('metaKey','email%');
	return $this->fetch();
	
  }
  
  function fetchAddressJSON() {
	
	$this->setSort('clientMetas.sort');
	$this->db->select('clientMetaID as entityID');
	$this->schemaName = 'address';
	$result = parent::fetch();
	
	return json_encode($result);
	
  }
  
  function fetchEmailJSON() {
	
	$this->setSort('clientMetas.sort');
	$this->db->select('clientMetaID as entityID');
	$this->schemaName = 'email';
	$result = parent::fetch();
	
	return json_encode($result);
	
  }
  
  function fetchPhoneJSON() {
	
	$this->setSort('clientMetas.sort');
	$this->db->select('clientMetaID as entityID');
	$this->schemaName = 'phone';
	$result = parent::fetch();
	
	return json_encode($result);
	
  }
  
  function deleteClient() {
	
	if (!$this->clientsID) throw new Exception('Client ID not supplied.[m_client_metas:deleteClient]');
	
	$this->db->where('clientsID', $this->clientsID);
	$this->db->delete('clientMetas');
	
  }
  
}
// EOF