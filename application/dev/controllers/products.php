<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_products');
    $this->load->model('m_product_metas');
  }
  
  function renderHTML() {

	$this->m_products->parentID = '0';
	$this->load->vars('productsJSON', $this->m_products->fetchWithImageJSON());
	$this->load->vars('js_tpl_list', $this->load->view('products/js_tpl_list','',true));
	$this->load->vars('content',$this->load->view('products/list', '', true));

	$this->jsFiles('/scripts/product-list.js');
	$this->jsFiles('/scripts/jquery.dragsort-0.5.1.js');

  }
  
  function renderHTMLEntity() {
	
	$this->load->model('m_metas');
	$this->load->model('m_suppliers');
	
    $this->m_products->id = $this->entityID;
    $this->m_product_metas->productsID = $this->entityID;

//	$freight = $this->freights();
//	$this->load->vars('freightJSON', json_encode($freight));
//	$this->m_freight_locations->freightsID = $freight->freightID;
//	$this->load->vars('freightLocationsJSON', json_encode($this->m_freight_locations->fetchJoined()));
	$this->load->vars('productJSON', $this->m_products->getJoinedJSON());
	$this->load->vars('subProductJSON', $this->m_products->fetchSubProductsJSON());
	$this->load->vars('priceJSON', $this->m_product_metas->priceJSON());
	$this->load->vars('imageJSON', $this->m_product_metas->imageJSON());

	$this->m_suppliers->setSort('name');
	$this->load->vars('suppliers', $this->m_suppliers->fetch());

	$this->m_metas->schemaName = 'productQuantity';
	$this->load->vars('productQuantities', $this->m_metas->fetch());

	$this->load->vars('js_tpl_entity', $this->load->view('products/js_tpl_entity','',true));
	$this->load->vars('js_tpl_price_list', $this->load->view('products/js_tpl_price_list','',true));
	$this->load->vars('js_tpl_subproduct_list', $this->load->view('products/js_tpl_subproduct_list','',true));
	$this->load->vars('js_tpl_image_list', $this->load->view('products/js_tpl_image_list','',true));
//	$this->load->vars('js_tpl_freight', $this->load->view('products/js_tpl_freight','',true));
//	$this->load->vars('js_tpl_freightlocation', $this->load->view('products/js_tpl_freightlocation','',true));

	$this->load->vars('content',$this->load->view('products/entity','', true));

	$this->cssFiles('/styles/fileuploader/fileuploader.css');
	$this->jsFiles('/scripts/jquery.dragsort-0.5.1.js');
	$this->jsFiles('/scripts/fileuploader.js');
	$this->jsFiles('/scripts/product-entity.js');

  }
  
  function renderJSONDelete() {
	
    $this->m_products->id = $this->entityID;
	$result = $this->m_products->get();

	if ($result->parentID == 0) {

	  $this->load->model('m_freights');
	  $this->m_freights->productsID = $this->m_products->id;
	  $freight = $this->m_freights->getProduct();
	  
	  if ($freight) {
		
		$this->m_freights->id = $freight->freightID;	  
		$this->m_freights->delete();	  

		$this->load->model('m_freight_locations');
		$this->m_freight_locations->freightsID = $freight->freightID;
		$this->m_freight_locations->deleteFreight();	  
		
	  }

	}
	
	$this->m_products->delete();
	
  }
  
  function formJSONEntity() {
	
    $this->m_products->id = $this->entityID;
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_products->{$k} = $v;
	}

	$this->m_products->update();
	$row = $this->m_products->getJoined();
	print json_encode($row);
	
  }
  
  function formJSONNew() {
	
	$json = json_decode(file_get_contents('php://input'));

	foreach ($json as $k=>$v) {
	  $this->m_products->$k = $v;
	}

	$this->m_products->add();
	$this->entityID = $this->m_products->id;
	$row = $this->m_products->get();

//	if ($row->parentID == 0) {
//	  $this->freights();
//	}

	print json_encode($row);
	
  }
  
//  function freights() {
//	
//	$this->load->model('m_freights');
//	$this->load->model('m_freight_locations');
//	$this->load->model('m_locations');
//   
//	$this->m_freights->productsID = $this->entityID;
//	$freight = $this->m_freights->getProduct();
//
//	if (!$freight) {
//
//	  $this->m_freights->add();
//	  $freight = $this->m_freights->get();
//	  
//	  $this->m_freight_locations->locations = $this->m_locations->fetchIndexed();
//	  $this->m_freight_locations->freightsID = $this->m_freights->id;
//	  $this->m_freight_locations->addFreight();
//
//	}
//	
//	return $freight;
//  }
  
  // dev method for cleaning up images
  function imageMaint() {
	
 //$path = realpath(APPPATH).'/libraries/IMagick_Image.php';
//require_once($path);
////$path2 = realpath($_SERVER['DOCUMENT_ROOT']).'/images/';
//$path2 = realpath($_SERVER['DOCUMENT_ROOT']).'/images/LL9138TR_frisbeeRed/';
//$util = new ImageUtil();
////$util->imageToDir($path2);
////$util->cleanUp($path2);
//$util->removeDir($path2);
//print $util->log();
//exit;
 }

}