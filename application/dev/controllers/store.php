<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends MM_controller {

  function __construct() {
    parent::__construct();
    $this->load->model('m_products');
    $this->load->model('m_product_metas');
  }
 
  function renderHTML() {
	
	$this->m_products->parentID = '0';
	$this->load->vars('productsJSON', $this->m_products->fetchWithImageJSON());
	$this->load->vars('js_tpl_list', $this->load->view('store/js_tpl_list','',true));
	$this->load->vars('content',$this->load->view('store/list', '', true));

	$this->jsFiles('/scripts/store-list.js');

  }
  
  function renderHTMLEntity() {
	
	$this->load->model('m_supplier_freights');
	$this->load->model('m_metas');
	
    $this->m_products->id = $this->entityID;
    $this->m_product_metas->productsID = $this->entityID;
	
	$product = $this->m_products->get();
	$this->load->vars('product', $product);
	$this->load->vars('productJSON', json_encode($product));
	$this->load->vars('subProductJSON', $this->m_products->fetchSubProductsWithQtyJSON());
	$this->load->vars('priceJSON', $this->m_product_metas->priceJSON());
	$this->load->vars('imageJSON', $this->m_product_metas->imageJSON());

	$this->m_supplier_freights->suppliersID = $product->suppliersID;
	$this->m_supplier_freights->zonesID = $this->user->zoneID();

	$this->load->vars('freightJSON', json_encode($this->m_supplier_freights->getJoined()));

	$this->load->vars('js_tpl_entity', $this->load->view('store/js_tpl_entity','',true));
	$this->load->vars('js_tpl_subproduct_list', $this->load->view('store/js_tpl_subproduct_list','',true));
	$this->load->vars('js_tpl_price_list', $this->load->view('store/js_tpl_price_list','',true));
	$this->load->vars('js_tpl_image_list', $this->load->view('store/js_tpl_image_list','',true));

	$this->m_metas->schemaName = 'tax';
	$this->load->vars('taxes', $this->m_metas->fetchKVPairObj());

	$this->load->vars('content',$this->load->view('store/entity','', true));

	$this->cssFiles('/scripts/fancybox/jquery.fancybox.css');
	$this->jsFiles('/scripts/fancybox/jquery.fancybox.js');
	$this->jsFiles('/scripts/jquery.mousewheel-3.0.6.pack.js');
	$this->jsFiles('/scripts/store-entity.js');
	
  }
}
