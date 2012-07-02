<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends MM_controller {

  function __construct() {
	parent::__construct();
	$this->load->model('m_products');
	$this->load->model('m_product_metas');
	$this->load->model('m_supplier_addresses');
	$this->load->model('m_products');
	$this->load->model('m_chargeouts');
	$this->load->model('m_carts');
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

	$this->load->model('m_metas');
	$this->load->model('m_carts');

	$this->m_products->id = $this->entityID;
	$this->m_product_metas->productsID = $this->entityID;
	
	$product = $this->m_products->getSupplierCzone();

	$this->m_supplier_addresses->suppliersID = $product->suppliersID;
	$dispatchLocation = $this->m_supplier_addresses->getSupplierDispatchLocation();

	$this->load->vars('dispatchLocation', $dispatchLocation);
	$this->load->vars('product', $product);
	$this->load->vars('productJSON', json_encode($product));
	$this->load->vars('prices', $this->m_product_metas->prices());
	$this->load->vars('images', $this->m_product_metas->images());
	$this->load->vars('userAddresses', json_encode($this->user->alladdresses()));
	
	$this->load->library('cartcalc');
	$this->load->model('m_chargeouts');

	$this->m_products->id = $this->entityID;
	$product = $this->m_products->getSupplierCzone();

	$this->m_carts->usersID = $this->user->id();
	$this->m_carts->productsID = $product->productID;
	$cart = $this->m_carts->fetch();
	$cart = reset($cart);

	$this->m_product_metas->productsID = $product->productID;
	$this->m_product_metas->qtyTotal = $cart->qtyTotal;
	$pricePoint = $this->m_product_metas->getPricePoint();

	$this->m_metas->schemaName = "tax";
	$tax = $this->m_metas->fetchKVPairObj();
	$gst = isset($tax->GST) ? $tax->GST : 10;
	
	$product->productsID = $product->productID;
	$product->taxRate = $gst;
	$product->itemPrice = $pricePoint->metaValue;
	$product->qtyTotal = $cart->qtyTotal;

	$this->cartcalc->product($product);

	$this->m_chargeouts->xto = $this->user->czone();
	$this->m_chargeouts->xfrom = $product->czone;
	$freight = reset($this->m_chargeouts->fetch());

	$this->cartcalc->freight($freight);
	
	$this->load->vars('cartJSON', json_encode($this->cartcalc->calc()));

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
