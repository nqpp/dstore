var App = App || {};

$(function(){
  
  /*
   * ======================================================================
   * PRODUCT
   * ======================================================================
   */
  
  App.Product = Backbone.Model.extend({
	
	urlRoot: '/products',
	
	defaults: {
	  qtyTotal:0,
	  itemPrice:0,
	  freightTotal:0,
	  taxRate: (gst / 100)
	}

  });
  
  
  App.ProductView = Backbone.View.extend({

	el: $("#product"),
	
	template: _.template($('#tpl-entity').html()),
	
	initialize:function() {
	  this.model.bind('change', this.render, this);
	},
	
	render: function() {
	  
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	}
	
  });

  var product = new App.Product(productJSON);
  var view = new App.ProductView({model:product});
  view.render();
  
  /*
   * ======================================================================
   * IMAGE
   * ======================================================================
   */  
  
  App.Image = Backbone.Model.extend({
	
	idAttribute: 'productMetaID',
	
//	defaults: {
//	  productsID: productJSON.productID,
//	  schemaName:'image',
//	  metaKey:'',
//	  metaValue:'',
//	  sort:'1',
//	  imgDir:''
//	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.ImageList = Backbone.Collection.extend({
	model: App.Image,
	
	comparator:function(model) {
	  return model.get('sort');
	}
	
  });
  
  App.Images = new App.ImageList;
  
  App.ImageView = Backbone.View.extend({
	tagName: 'li',
	attributes: {
	  class:"span2"
	},
	template: _.template($('#tpl-image-list').html()),
	events: { 
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  return this;
	},
	clear: function() {
	}
  });

  App.ImagesView = Backbone.View.extend({
	el: $("#image"),
	events: {},
	initialize: function() {
	  App.Images.bind('reset', this.addAll, this);
	  App.Images.bind('add', this.add, this);
	  
	},
	gallery:function() {
	  $(".fb").fancybox();
	},
	add: function(model) {
	  var view = new App.ImageView({model: model});
	  var elem = view.render().el
	  $('#image').append(elem);
	},
	addAll: function() {
	  App.Images.each(this.add);
	  this.gallery();
	}
  });
  
  var imagesView = new App.ImagesView;
  App.Images.reset(imageJSON);
  
  
  

  /*
   * ======================================================================
   * PRICE DISPLAY
   * ======================================================================
   */  
  
  App.Price = Backbone.Model.extend({
	
	clear:function() {
	}
	
  });
  
  App.PriceList = Backbone.Collection.extend({
	model: App.Price,
	
	comparator:function(model1,model2) {

	  var val1 = parseInt(model1.get('metaKey'));
	  var val2 = parseInt(model2.get('metaKey'));
	  
	  if (val1 > val2) return 1;
	  else if (val2 > val1) return -1;
	  else return 0;
	  
	}
	
  });
  
  App.Prices = new App.PriceList;
  
  App.PriceView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-price-list').html()),
	events: {
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  var data = this.model.toJSON();
	  data['id'] = this.model.cid;
	  this.$el.html(this.template(data));
	  return this;
	}
  });

  App.PricesView = Backbone.View.extend({
	el: $("#price"),
	initialize: function() {
	  App.Prices.bind('reset', this.addAll, this);
	  App.Prices.bind('add', this.add, this);
	},
	add: function(model) {
	  var view = new App.PriceView({model: model});
	  $('#price tbody').append(view.render().el);
	},
	addAll: function() {
	  App.Prices.each(this.add);
	}

  });
  
  new App.PricesView;
  App.Prices.reset(priceJSON);

  
  
  /*
   * ======================================================================
   * ADD TO CART
   * ======================================================================
   */  
  
  App.CartPrep = Backbone.Model.extend({
	
	idAttribute: 'productID',
	
	defaults: {
	  qty:''
	},
	
	clear:function() {
//	  this.destroy();
	}
	
  });
  
  App.CartPrepList = Backbone.Collection.extend({
	model: App.CartPrep,
	
	comparator:function(model) {
	  return model.get('code');
	}	
  });
  
  App.CartPreps = new App.CartPrepList;
  
  App.CartPrepView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-subproduct-list').html()),
	events: {
	  "change input":"update"
	},
	
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  return this;
	},
	update:function(ev) {
	  var data = {};
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data);
	  return true;
	},
	clear: function() {
	}
  });

  App.CartPrepsView = Backbone.View.extend({
	el: $("#cart_prep"),
	events: {
	  "change input":"calcTotal",
	  "click button#add_to_cart":"toCart"
	},
	initialize: function() {
	  
	  this.resetTotals();
	  
	  App.CartPreps.bind('reset', this.addAll, this);
	  App.CartPreps.bind('add', this.add, this);
	},
	calcTotal:function() {
	  this.resetTotals();
	  var self = this;
	  
	  App.CartPreps.each(function(m) {
		m.set({productsID:m.get('productID')});

		var qty = parseInt(m.get('qty'));
		if (!isNaN(qty)) {
		  self.requestQty += qty;		  
		}
	  });
	  
	  App.Prices.each(function(m) {
		var testQty = parseInt(m.attributes.metaKey.replace(',',''))
		var testPrice = parseFloat(m.attributes.metaValue)

		if (testQty == self.requestQty) {
		  self.priceEa = testPrice;
		  self.pricePtQty = testQty; // comparation figure to help identify price point
		  return;
		}
		if (testQty < self.requestQty && testQty > self.pricePtQty) {
		  self.priceEa = testPrice;
		  self.pricePtQty = testQty; // comparation figure to help identify price point
		}
		
	  });

	  this.calcFreight();
	  this.calcTotals();
	  this.displayTotals();
	  
	},
	resetTotals:function() {
	  this.requestQty = 0;
	  this.pricePtQty = 0;
	  this.priceEa = 0;
	  this.subTotal = 0;
	  this.priceTotal = 0;
	  this.freight = 0;
	  this.gstTotal = 0;
	  
	  this.$el.find('.display').html('');
	  this.$el.find("#add_to_cart").attr('disabled',true);
	},
	displayTotals:function() {
	  if (this.requestQty == 0) return;
	  this.$el.find('#qty_total').html(this.requestQty);
	  
	  if (this.pricePtQty == 0) return;
	  this.$el.find('#price_ea').html(this.priceEa.toFixed(2));
	  this.$el.find('#sub_total').html(this.subTotal.toFixed(2));
	  this.$el.find('#freight').html(this.freight.toFixed(2));
	  this.$el.find('#gst_total').html(this.gstTotal.toFixed(2));
	  this.$el.find('#price_total').html(this.priceTotal.toFixed(2));
	  
	  this.$el.find("#add_to_cart").attr('disabled',false);
	},
	calcTotals:function() {
	  if (this.requestQty == 0) return;
	  this.subTotal = this.priceEa * this.requestQty;
	  this.gstTotal = (this.subTotal + this.freight ) * gst / 100;
	  this.priceTotal = this.subTotal + this.gstTotal;

	  product.set({productsID:product.get('productID'),qtyTotal:this.requestQty,itemPrice:this.priceEa,freightTotal:this.freight});
	},
	calcFreight:function() {
	  if (this.requestQty == 0) return;
	  var cw = product.get('cubicWeight');
	  var dw = product.get('deadWeight');
	  var weight = (cw > dw ? cw : dw) * this.requestQty;
	  var freight = weight * parseFloat(freightJSON.kgRate) + parseFloat(freightJSON.baseRate)
	  var min = parseFloat(freightJSON.minCharge);
	  
	  if (freight > min) this.freight = freight;
	  else this.freight = min;

	},
	toCart:function() {
	  if (this.pricePtQty == 0) return;
	  
	  var cart = new App.Cart(product.toJSON());
	  cart.save({},{wait:true,success:this.toCartItem});
	  
	  this.resetTotals();
	},
	toCartItem:function(model,response) {

	  App.CartPreps.each(function(m) {
		m.set({cartsID:response.cartID});

		if (!isNaN(m.get('qty')) && m.get('qty') > 0) {
		  
		  var cartItem = new App.CartItem(m.toJSON());
		  cartItem.save();
		}
		m.set({cartsID:'',qty:''});
	  });
	  
	},
	add: function(model) {
	  var view = new App.CartPrepView({model: model});
	  $('#cart_prep tbody').append(view.render().el);	  
	},
	addAll: function() {
	  App.CartPreps.each(this.add);
	}
  });
  
  new App.CartPrepsView;
  App.CartPreps.reset(subProductJSON);


  /*
   * ======================================================================
   * CART
   * ======================================================================
   */  
  
  App.Cart = Backbone.Model.extend({
	
	urlRoot: '/carts',
	idAttribute: 'cartID',
	defaults:{
	  cartID:0
	}
  });
  

  /*
   * ======================================================================
   * CART ITEM
   * ======================================================================
   */  
  
  App.CartItem = Backbone.Model.extend({
	
	urlRoot: '/cart_items',
	idAttribute: 'cartItemID'
	
  });
  
});
