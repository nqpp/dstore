<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * MM_Controller Class
 * 
 * Controller extender that provides RESTful service, page variables as well as
 * login and permissions layer
 * 
 * TODO: implement unsecure usage.
 * abstract out auth and permission areas so that the controller may also be 
 * used for unsecure purposes perhaps by an explicit setting of a flag from
 * the overriding constructor.
 * 
 */
class MM_Controller extends CI_Controller {
  
  protected $entityID;
  protected $method;
  protected $access;
  protected $methodCall;
  protected $pageTemplate;
  protected $cssFiles = array();
  protected $jsFiles = array();
  protected $jsScripts = array();

  function __construct() {
    
    parent::__construct();
	
	try {
	  $this->check_login();
	  $this->check_rest_permission();
	  $this->load_tpl_vars();
	  $this->userPrefs();
	  $this->userNav();
	  $this->pageTemplate();
	  $this->clientAutoload();
	  $this->jsSetUserID();
	}
	catch (Exception $e) {
	  die ($e->getMessage().'<br>'.$e->getFile().'<br>'.$e->getLine());
	}
	
  }
  
  function _remap($id = false) {

	if ($id == 'index') $id = false;
	$this->entityID = $id;
	
	$req = $_SERVER['REQUEST_METHOD'];
	$qs = false;
<<<<<<< .merge_file_VCdsiJ
	
	$_qsarray = explode('&',$_SERVER['QUERY_STRING']);
	
=======
	
	$_qsarray = explode('&',$_SERVER['QUERY_STRING']);
	
>>>>>>> .merge_file_LUbGkC
	if (count($_qsarray)) {
	  // if there are name value pairs present is not an extension
	  $qs = (strpos($_qsarray[0],'=') !== false) ? false: $_qsarray[0];
	}

	$this->method = array(
		'pre'=>'',
		'type'=>'HTML',
		'extension'=>''
	);
	
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	  $this->method['type'] = $this->get_content_type();
	}
	
	switch($req) {
	  case 'GET':
		// instance
		if ($id) {
		  $this->access = 'GET';
		  $this->method['pre'] = 'render';
		  $this->method['extension'] = 'Entity';
		  
		}
		// list
		else {
		  $this->method['pre'] = 'render';
		}
		break;
	  case 'POST':
		  $this->access = 'POST';
		  $this->method['pre'] = 'form';
		  $this->method['extension'] = 'New';

		  if ($id) {
			$this->access = 'PUT';
			$this->method['extension'] = 'Entity';
		  }
			
		break;
	  case 'PUT':
		  $this->access = 'PUT';
		  $this->method['pre'] = 'form';
		  $this->method['extension'] = 'Entity';
		  
		  if (!$id) {
			$this->access = 'POST';
			$this->method['extension'] = 'New';
		  }
		  
		break;
	  case 'DELETE':
		  $this->access = 'DELETE';
		  $this->method['pre'] = 'render';
		  $this->method['extension'] = 'Delete';
		break;
	}

	if ($qs) {
	  $_qs = strtolower($qs);
	  if (strpos($_qs, 'delete') !== false) {
		$this->access = 'DELETE';
	  }
	  else if (strpos($_qs, 'new') !== false) {
		$this->access = 'POST';		
	  }

	  $this->method['extension'] = ucfirst($qs);  
	}


	$this->methodCall = implode('',$this->method);

//print $this->methodCall; exit;

	if (method_exists($this, $this->methodCall) == false) {
	  $this->{$this->method['type'].'Error'}();
	  exit;
	}

	try {
	  $this->{$this->methodCall}();
	}
	catch (Exception $e) {
	  
	  switch ($this->method['type']) {
		case 'JSON':
		  header("HTTP/1.0 500 Internal Server Error");
		  print $e->getMessage();
		  break;
		case 'HTML':
		default:
		  $data->error = $e->getMessage();
		  $this->load->vars('content', $this->load->view('errors/entity',$data,true));
		  break;
	  }
	  
	}

	if ($this->method['type'] == 'HTML') {
	  
	  $this->load->vars('cssFiles', $this->cssFiles());
	  $this->load->vars('jsFiles', $this->jsFiles());
	  $this->load->vars('jsScripts', $this->jsScripts());
	  $this->load->view('system/'.$this->pageTemplate);

	}

  }
  
  private function HTMLError($err_code = '404') {
	
	die(header('Location: /error.html?'.$err_code));
	
  }
  
  private function JSONError($err_code = '404') {
	
	$httpErrors = $this->httpErrors();
	$err = $httpErrors[$err_code];
	die (header($err));
	
  }
  
  private function check_login() {
	
    $redirect = $this->uri->uri_string();

    if ($_SERVER['QUERY_STRING']) {
      $redirect .= '?'.$_SERVER['QUERY_STRING'];
    }

	// not logged in
    if(!$this->user->userID()) {
	  // request from ajax
	  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
		die(header("HTTP/1.0 401 Unauthorized"));
	  }
	  // standard page request
      die(header('Location: /login.html?redirect='.$redirect));
	}
  }
  
  private function check_rest_permission() {

    if($this->user->adminGroup() & 1) return; // sysadmin always ok
	
	$errorMethod = $this->method['type'].'Error';
	$uri = $this->uri->segment(1).$this->access;
	
	if ($this->user->allowedPages() && !in_array($uri,$this->user->allowedPages())) $this->{$errorMethod}('403');
	
  }
  
  private function pageTemplate() {
	
    $this->load->model('m_metas');
	$this->m_metas->schemaName = 'adminGroupTemplate';
	$this->m_metas->metaKey = $this->user->adminGroup();
	$meta = reset($this->m_metas->fetch());
	$this->m_metas->reset();
	
	if ($meta && count($meta)) {
	  $this->pageTemplate = $meta->metaValue;	  
	}
	else {
	  $this->pageTemplate = 'page_bootstrap';
	}
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
  
  private function userPrefs() {
	
    $this->load->model('m_user_prefs');
	$this->m_user_prefs->usersID = $this->user->userID();
	$this->m_user_prefs->index = 'prefName';
	$userPref = $this->m_user_prefs->fetchJoined();
    $this->userpref->init($userPref);
	
  }
  
  private function userNav() {
	
	switch ($this->user->pseudoAdminGroup()) {
	  case 1:
		$nav = 'nav_admin';
		break;
	  case 16:
		$nav = 'nav_manager';
		break;
	  case 32:
		$nav = 'nav_client';
		break;
	  default:
		$nav = 'nav_client';
		break;
	}

	$this->load->vars('nav', $this->load->view('system/'.$nav,'',true));
	
  }
  
  /*
   * determine content type for AJAX request
   */
  private function get_content_type() {

	  $ct = $this->content_types();
	  $a = trim(reset(explode(',',$_SERVER['HTTP_ACCEPT'])));
	  
		header('Content-type:'.$a); // Set header
	
	  if (array_key_exists($a,$ct)) return $ct[$a];
	  // throw error
	  throw new Exception('Content type unknown - '.$a);
	  
  }
  
  private function content_types() {
	
	return array(
	  'application/json'=>'JSON'
	);
  }
  
  private function httpErrors() {
	return array(
	  "400"=>"HTTP/1.0 400 Bad Request",
	  "401"=>"HTTP/1.0 401 Unauthorized",
	  "403"=>"HTTP/1.0 403 Forbidden",
	  "404"=>"HTTP/1.0 404 Not Found",
	);
  }
  
  // add jsFiles and cssFiles to ppropriate method from config file
  function clientAutoload() {
	
	foreach ($this->config->item('client_autoload') as $call=>$fileNames) {
	  if (method_exists($this,$call) && count($fileNames)) {
		foreach ($fileNames as $f) {
		  $this->$call($f);
		}
	  }
	}
	
  }
  
  function jsSetUserID() {
	$s = "App.userID = {$this->user->id()};\n";
	$this->jsScripts($s);
  }
  
  /*
   * store reference to css files to be included in view
   */
  function cssFiles($cssFile = false) {
	
	if ($cssFile) $this->cssFiles[] = $cssFile;
	else return $this->cssFiles;
  }
  
  /*
   * store reference to js files to be included in view
   */
  function jsFiles($jsFile = false) {
	
	if ($jsFile) $this->jsFiles[] = $jsFile;
	else return $this->jsFiles;
  }
  
  /*
   * store js script strings to be included in view
   */
  function jsScripts($jsScript = false) {
	
	if ($jsScript) $this->jsScripts[] = $jsScript;
	else return implode("\n",$this->jsScripts);
  }
  
}
// EOF