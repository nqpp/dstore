<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends MM_controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_products');
	$this->load->model('m_product_metas');
	$this->load->model('m_supplier_addresses');
  }

  function renderHTML() {

	$this->m_products->parentID = '0';
	$this->load->vars('productsJSON', $this->m_products->fetchWithImageJSON());
	$this->load->vars('js_tpl_list', $this->load->view('store/js_tpl_list','',true));

	$this->jsFiles('/scripts/store-list.js');

	$this->load->vars('js_tpl_cart', $this->load->view('store/js_tpl_cart','',true));
	$this->jsFiles('/scripts/cart.js');
	$this->jsFiles('/scripts/jquery-ui-1.8.21.min.js');

	$this->load->vars('content',$this->load->view('store/list', '', true));
  }

  function renderHTMLEntity() {

//	$this->load->model('m_supplier_freights');
	$this->load->model('m_metas');
	$this->load->model('m_carts');

	$this->m_products->id = $this->entityID;
	$this->m_product_metas->productsID = $this->entityID;
	
	$product = $this->m_products->get();
	
	$this->m_supplier_addresses->suppliersID = $product->suppliersID;
	$dispatchLocation = $this->m_supplier_addresses->getSupplierDispatchLocation();

	$this->load->vars('dispatchLocation', $dispatchLocation);
	$this->load->vars('product', $product);
	$this->load->vars('prices', $this->m_product_metas->prices());
	$this->load->vars('images', $this->m_product_metas->images());

	$this->m_carts->productsID = $this->entityID;
	$this->load->vars('cartJSON', $this->m_carts->fetchUserCartTotalledJSON());
	$this->load->vars('subProductJSON', $this->m_products->fetchSubProductsWithQtyJSON());

	$this->load->vars('js_tpl_subproduct_list', $this->load->view('store/js_tpl_subproduct_list','',true));
	$this->load->vars('js_tpl_cart', $this->load->view('store/js_tpl_cart','',true));

	$this->jsFiles('/scripts/cart.js');
	$this->jsFiles('/scripts/jquery-ui-1.8.21.min.js');
	$this->cssFiles('/scripts/fancybox/jquery.fancybox.css');
	$this->jsFiles('/scripts/fancybox/jquery.fancybox.js');
	$this->jsFiles('/scripts/jquery.mousewheel-3.0.6.pack.js');

	$this->jsFiles('/scripts/store-entity.js');

	$this->load->vars('content',$this->load->view('store/entity','', true));
  }
}
