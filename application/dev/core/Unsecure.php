<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Unsecure Class
 * 
 * Basic controller extender that provides RESTful service and basic page variables.
 * No authentification provided.
 * 
 * Used for Login page.
 * 
 */
class Unsecure extends CI_Controller {
  
  protected $entityID;
  protected $method;
  protected $methodCall;

  function __construct() {
    parent::__construct();
	$this->load_tpl_vars();
  }
  
  function _remap($id = false) {
	
	if ($id == 'index') $id = false;
	$this->entityID = $id;
	
	$req = $_SERVER['REQUEST_METHOD'];
	$qs = strtolower($_SERVER['QUERY_STRING']);
	
	if (strpos($qs,'=') !== false) {
	  // there are name value pairs present so is not an extension
	  $qs = false; 
	}

	$this->method = array(
		'pre'=>'',
		'type'=>'HTML',
		'extension'=>'',
	);
	
	
	if ($qs) $this->method['extension'] = ucfirst($qs);
	
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	  $this->method['type'] = $this->get_content_type();
	}
	
	switch($req) {
	  case 'GET':
		// instance
		if ($id) {
		  $this->method['pre'] = 'render';
		  $this->method['extension'] = 'Entity';
		  
		}
		// list
		else {
		  $this->method['pre'] = 'render';
		}
		break;
	  case 'POST':
		  $this->method['pre'] = 'form';
		  $this->method['extension'] = 'New';

		  if ($id) {
			$this->method['extension'] = 'Entity';
		  }
			
		break;
	  case 'PUT':
		  $this->method['pre'] = 'form';
		  $this->method['extension'] = 'Entity';
		  
		  if (!$id) {
			$this->method['extension'] = 'New';
		  }
		  
		break;
	  case 'DELETE':
		  $this->method['pre'] = 'render';
		  $this->method['extension'] = 'Delete';
		break;
	}

	if ($qs) {
	  $this->method['extension'] = ucfirst($qs);  
	}

	$this->methodCall = implode('',$this->method);
	
//print $this->methodCall; exit;

	if (method_exists($this, $this->methodCall) == false) {
	  $this->{$this->method['type'].'Error'}();
	  exit;
	}

	$this->{$this->methodCall}();

  }
  
  private function HTMLError($err_code = '404') {
	
	die(header('Location: /error?'.$err_code.'.html'));
	
  }
  
  private function JSONError($err_code = '404') {
	
	$a = array("status"=>$err_code);
	die(json_encode($a));
	
  }
  
  private function load_tpl_vars() {
	
    $this->load->vars('theme',$this->config->item('theme'));
    $this->load->vars('org_name',$this->config->item('org_name'));
    
    $segment_array = $this->uri->segment_array();
    foreach ($segment_array as $k=>$v) {
      $segment_array[$k] = ucwords(str_replace('_',' ',$v));
    }
    $page_name = implode(' ',$segment_array);
    $this->load->vars('page_name',$page_name);
	
  }
  
  /*
   * determine content type for AJAX request
   */
  private function get_content_type() {
	
	  $ct = $this->content_types();
	  $a = trim(reset(explode(',',$_SERVER['HTTP_ACCEPT'])));
	  
	  if (array_key_exists($a,$ct)) return $ct[$a];
	  // throw error
	  throw new Exception('Content type unknown - '.$a);
	  
  }
  
  private function content_types() {
	
	return array(
	  'application/json'=>'JSON'
	);
  }
  
}
// EOF