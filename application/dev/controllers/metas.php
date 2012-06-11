<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metas extends MM_Controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_metas');
  }

  function renderHTML() {

	$this->m_metas->filter();
	$this->m_metas->sort();
	$this->load->vars('metas', $this->m_metas->fetchGrouped());
	$this->load->vars('content',$this->load->view('metas/list','',true));
	$this->jsFiles('/scripts/paginate.js');
	$this->jsFiles('/scripts/metas.js');
	$this->jsScripts('$(function(){$("table.paginate").paginate({"perPage":30});})');
	
  }

  function renderHTMLNew() {

	$this->load->vars('schemaNames', $this->m_metas->fetchSchemas());
	$this->load->vars('content',$this->load->view('metas/add','', true));

  }
  
  function renderHTMLEntity() {

    $this->m_metas->id = $this->entityID;

	$data = $this->m_metas->get();
	$this->m_metas->reset();
	$data->schemaNames = $this->m_metas->fetchSchemas();
	$this->load->vars('content',$this->load->view('metas/edit',$data, true));
    
  }
  
  function renderHTMLDelete() {

    $this->m_metas->id = $this->entityID;
	$this->m_metas->delete();
	$this->session->set_flashdata('msg',array('type'=>'success','text'=>'meta deleted.'));

    die(header('Location: /metas.html'));

  }

  function renderHTMLDeleteschema($schemaName) {

    $this->m_metas->schemaName = $this->entityID;
	$this->m_metas->delete_schema();
	$this->session->set_flashdata('msg',array('type'=>'success','text'=>'meta schema "'.$schemaName.'" deleted.'));

    die(header('Location: /metas.html'));

  }

  function formHTMLEntity() {
	
    $this->m_metas->id = $this->entityID;

	if ($this->input->post('add_action') !== false) {
	  $this->formHTMLNew();
	  return;
	}

	$this->m_metas->update();
	die(header('Location: /metas/'.$this->m_metas->id.'.html'));

  }

  function formHTMLNew() {
	
	$this->m_metas->add();
	die(header('Location: /metas/'.$this->m_metas->id.'.html'));
	
  }

  /*
   * function make()
   * empties metas table and rebuilds with core install data
   */
  function make() {
	
	$this->m_metas->make();
	die(header('Location: /metas.html'));
    
  }

}
