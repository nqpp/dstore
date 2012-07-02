<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class M_user_prefs extends MM_Model {

  function __construct() {
	$this->pk = 'userPrefID';
	parent::__construct();
  }

  // db field names
  function fields() {
	return array(
		'usersID',
		'metasID',
		'state'
	);
  }

  function fetchJoined() {

	$this->db->select('metas.*');
	$this->db->join('metas', 'metaID = metasID', 'left outer');
	return $this->fetchGrouped();
  }

}

//EOF