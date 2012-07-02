<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_permissions extends MM_Model {

//  var $index = false;
  private $makeFilePath = false;
  private $curClass = false;
  private $classMethods = array();
  public $adminGroup = false;

  function __construct() {
	$this->pk = 'permissionID';
    parent::__construct();
  }

  // db field names
  function fields() {
    return array(
      'parentID',
      'page_uri',
      'bit'
    );
  }

  function fetch() {

	$this->setSort('page_uri');
	return parent::fetch();
  }

  function fetchAllowedPages() {

    if (!$this->adminGroup) throw new Exception('No adminGroup nominated.[m_permission:allowedPages]');

    $this->db->where("bit & $this->adminGroup", '', false);
    return $this->fetchIndexed();
//    return $this->fetch();
  }

  function fetchAllowedUris() {

    $pages = $this->fetchAllowedPages();

    if (!count($pages)) return array();

    $u = array();
    foreach ($pages as $page) {
	  $uri = '';
	  
	  if ($page->parentID > 0) {
		$uri = $pages[$page->parentID]->page_uri.':';
	  }
	  $uri .= $page->page_uri;
	  
      $u[] = $uri;
    }

    return $u;
  }
  
  function new_make() {
	
	$this->config->load('permissions');
	$permissions = $this->config->item('permission_schema');
	$this->index = 'parentID';
	$result = $this->fetchGrouped();
	$this->index = false;
	
	$current = array();
	if (count($result) && isset($result[0])) {
	  foreach ($result[0] as $p) {
		$current[$p->page_uri][0] = $p;
		if (isset($result[$p->permissionID])) {
		  foreach ($result[$p->permissionID] as $c) {
			$current[$p->page_uri][$c->page_uri] = $c;
			
		  }
		  
		}
	  }
	  
	}
	
	$fresh = array();
	foreach ($permissions as $k=>$v) {
	  if (isset($current[$k])) {
		// do translation
		$fresh[$k][0] = array('parentID'=>0,'page_uri'=>$k,'bit'=>$current[$k]->bit);
		if (count($v)) {
		  foreach ($v as $a) {
			if (isset($current[$k][$a])) {
			  $fresh[$k][] = array('page_uri'=>$a,'bit'=>$current[$k][$a]->bit);

			}
			else {
			  $fresh[$k][] = array('page_uri'=>$a,'bit'=>1);
			  
			}
			
		  }
		  
		}
		
	  }
	  else {
		$fresh[$k][0] = array('parentID'=>0,'page_uri'=>$k,'bit'=>1);
		if (count($v)) {
		  foreach ($v as $a) {
			$fresh[$k][] = array('page_uri'=>$a,'bit'=>1);
			
		  }
		  
		}
	  }
	}
	
	$this->install_fresh($fresh);
  }
  
  private function install_fresh($fresh) {
	
	  $sql = 'TRUNCATE TABLE permissions';
	  $this->db->query($sql);
	  
	  foreach ($fresh as $k=>$v) {
		$parentID = 0;
		foreach ($v as $a) {
		  $this->db->set('page_uri',$a['page_uri']);
		  $this->db->set('parentID',$parentID);
		  $this->db->set('bit',$a['bit']);
		  $this->db->insert('permissions');
		  
		  $parentID = $parentID == 0 ? $this->db->insert_id(): $parentID;
		}
		
	  }
  }

  // populate db table with required page uris
  function make() {
    
	$this->index = 'page_uri';
	$result = $this->fetchIndexed();
	$this->index = false;
	
    $path = APPPATH.'controllers/';
    $files = scandir($path);

    if (!$files) return array();
    // iterate through controllers
    foreach ($files as $f) {
      if ($f == '.' || $f == '..' || $f == 'index.html') continue;
      
      $a = explode('.',$f);
      $this->curClass = $a[0];
      $this->classMethods[$this->curClass] = array();
      $this->makeFilePath = $path.$f;
	  // get methods for current class and add as sub array items
      $this->determineMethods(); 
    }

    foreach ($this->classMethods as $cls=>$methods) {
	  $unset = false;
	  
	  if (isset($result[$cls])) {
		$unset = true; // flag to unset parent item after current iteration
		$parentID = $result[$cls]->permissionID;
	  }
	  else {
		$parentID = 0;
		$this->db->set('parentID',$parentID);
		$this->db->set('page_uri',$cls);
		$this->db->set('bit',1);
		$this->db->insert('permissions');

		$parentID = $this->db->insert_id();
	  }

      if (!count($methods)) {
		unset($result[$cls]);
		continue;
	  }
      
      foreach ($methods as $m) {
		if (isset($result[$cls.'/'.$m])) {
		  // unset result[cls/m]
		  unset($result[$cls.'/'.$m]);
		  continue;
		}
		
        $this->db->set('parentID',$parentID);
        $this->db->set('page_uri',$cls.'/'.$m);
        $this->db->set('bit',1);
        $this->db->insert('permissions');
		
      }
		// unset result[cls]
      if ($unset) {
		unset($result[$cls]);
	  }
		
    }
	
	if (!count($result)) return; // all current rows still exist

	// kill off any rows that no longer have controllers
	$page_uris = array_keys($result);
	$this->db->where_in('page_uri',$page_uris);
	$this->db->delete('permissions');
	
	return $this->fetchAllowedUris();
  }

  // determine methods in a class
  private function determineMethods() {
    include_once($this->makeFilePath);
    $parent = get_parent_class(ucfirst($this->curClass));

    // only secured classes
    if ($parent != 'MM_Controller') {
      unset($this->classMethods[$this->curClass]);
      return;
    }

    // get methods
    $class_methods = get_class_methods(ucfirst($this->curClass));
    
    if (!count($class_methods)) return;

    foreach ($class_methods as $m)  {
      if ($m == '__construct' || $m == 'index' || $m == 'get_instance' || strpos($m,'_') === 0) continue;

      $this->classMethods[$this->curClass][] = $m;
    }

  }
  
}
// EOF