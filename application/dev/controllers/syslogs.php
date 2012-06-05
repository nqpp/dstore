<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syslogs extends MM_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_syslogs');
  }

  function renderHTML() {

	$this->load->vars('logs', $this->m_syslogs->fetch());
	$this->load->vars('content', $this->load->view('syslogs/list', '', true));
	$this->jsFiles('/scripts/paginate.js');
	$this->jsScripts('$(function(){$("table.paginate").paginate();})');
	
  }

  function renderHTMLEntity() {

    $this->m_syslogs->filename = $this->entityID;

	$this->load->vars('filename', $this->m_syslogs->filename);
	$this->load->vars('log', $this->m_syslogs->get());
	$this->load->vars('content', $this->load->view('syslogs/edit', '', true));
	
  }

  function renderHTMLDelete() {

    $this->m_syslogs->filename = $this->entityID;

	$this->m_syslogs->delete();
	$this->session->set_flashdata('msg',array('type'=>'success','text'=>'log file deleted.'));
    
    die(header('Location: /syslogs.html'));
  }

}
// EOF