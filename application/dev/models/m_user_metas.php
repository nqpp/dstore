<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user_metas extends MM_Model {

  function __construct() {
	$this->pk = 'userMetaID';
	$this->fields = $this->fields();
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'usersID',
      'schemaName',
      'metaKey',
      'metaValue',
	  'sort'
    );
  }
  
  
  function fetchEmploymentDataJSON() {
	
	$this->setSort('userMetas.sort');
	$this->schemaName = 'employmentdata';
	$result = parent::fetch();
	
	return json_encode($result);
	
  }
  
  function fetchEmailJSON() {
	
	$this->setSort('userMetas.sort');
//	$this->db->select('clientMetaID as entityID');
	$this->schemaName = 'email';
	$result = parent::fetch();
	
	return json_encode($result);
	
  }
  
  function fetchPhoneJSON() {
	
	$this->setSort('userMetas.sort');
//	$this->db->select('clientMetaID as entityID');
	$this->schemaName = 'phone';
	$result = parent::fetch();
	
	return json_encode($result);
	
  }
  
  function deleteUser() {
	
	if (!$this->usersID) throw new Exception('User ID not supplied.[m_user_metas:deleteUser]');
	
	$this->db->where('usersID', $this->usersID);
	$this->db->delete('userMetas');
	
  }
  
  
}