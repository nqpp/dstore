<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_users');
  }

  function renderHTML() {
	
	$this->load->vars('users', $this->m_users->fetchJoined());
	$this->load->vars('content', $this->load->view('users/list','', true));

	$this->jsFiles('/scripts/paginate.js');
	$this->jsScripts('$(function(){$("table.paginate").paginate();})');

  }

  function renderHTMLNew() {
	
    $this->load->model('m_metas');
	$this->m_metas->schemaName = 'adminGroup';
	$this->load->vars('groupMeta', $this->m_metas->fetchIndexed());
	$this->load->vars('content', $this->load->view('users/add','', true));

  }

  function renderHTMLEntity() {
	
	$this->m_users->id = $this->entityID;
	
    $this->load->model('m_metas');
	$data = $this->m_users->get();
	$this->m_metas->schemaName = 'adminGroup';
	$data->groupMeta = $this->m_metas->fetchIndexed();
	$this->load->vars('content', $this->load->view('users/edit',$data, true));

  }
  
  function renderHTMLProfile() {

    $this->m_users->id = $this->$user->id();
	$data = $this->m_users->get();
	$this->load->vars('content', $this->load->view('users/profile',$data, true));

  }
  
  function renderHTMLDelete() {

	$this->m_users->id = $this->entityID;

	$this->m_users->delete();
	$this->session->set_flashdata('msg',array('type'=>'success','text'=>'User deleted.'));

    die(header('Location: /users.html'));
  }
  
  function renderJSONCurrent() {
	
    $this->m_users->id = $this->user->id();
	$data = $this->m_users->getRestricted();

	die (print (json_encode($data)));
  }

  function formHTMLEntity() {
	
	$this->m_users->id = $this->entityID;
	$this->m_users->update();
	
	die(header("Location: /users/{$this->m_users->id}.html"));
  }
  
  function formHTMLProfile() {
	
	$this->m_users->id = $this->user->id();
	$this->m_users->update();
	
	die(header('Location: /users.html?profile'));
  }
  
  function formHTMLNew() {
	
	$this->m_users->add();
	die(header("Location: /users/{$this->m_users->id}.html"));

  }

}