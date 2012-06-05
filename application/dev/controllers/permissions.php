<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_permissions');
  }

  function renderHTML() {

	$this->load->model('m_metas');
	
	$this->m_permissions->index = 'parentID';
	$this->load->vars('items', $this->m_permissions->fetchGrouped());
	
	$this->m_metas->schemaName = 'adminGroup';
	$this->load->vars('adminGroups', $this->m_metas->fetchIndexed());
	$this->load->vars('content',$this->load->view('permissions/list', '', true));

	$this->jsFiles('/scripts/permissions.js');
	$this->jsFiles('/scripts/common.js');

  }
  
  function renderHTMLMake() {

	$this->m_permissions->new_make();
	die (header ("Location:/permissions"));
	
  }

  
}
// EOF