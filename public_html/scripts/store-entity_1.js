var App = App || {};

$(function(){
  
  /*
   * ======================================================================
   * PRODUCT
   * ======================================================================
   */
  
  App.Product = Backbone.Model.extend({
	
	urlRoot: '/products'

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
	
	defaults: {
	  productsID: productJSON.productID,
	  schemaName:'image',
	  metaKey:'',
	  metaValue:'',
	  sort:'1',
	  imgDir:''
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.ImageList = Backbone.Collection.extend({
	model: App.Image,
//	url: '/product_images',
	
	comparator:function(model) {
	  return model.get('sort');
	}
	
  });
  
  App.Images = new App.ImageList;
  
  App.ImageView = Backbone.View.extend({
	tagName: 'li',
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
	
//	urlRoot: '/product_metas',
	
//	idAttribute: 'productMetaID',
	
	defaults: {
//	  productsID: productJSON.productID,
//	  schemaName:'price',
//	  metaKey:'',
//	  metaValue:'',
//	  sort:'1'
	},
	
	clear:function() {
//	  this.destroy();
	}
	
  });
  
  App.PriceList = Backbone.Collection.extend({
	model: App.Price,
//	url: '/product_metas',
	
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
//	el: $("#prices"),
	el: $("#price"),
	events: {
//	  "change input.item_qty": "calcTotal"
	},
	initialize: function() {
	  
//	  this.orderTotal = 0;
	  
	  App.Prices.bind('reset', this.addAll, this);
	  App.Prices.bind('add', this.add, this);
	},
//	calcTotal:function(ev) {
//	  var reqQty = 0, pricePtQty = 0, priceEa = 0, priceTotal = 0, gstTotal = 0;
//	  
////console.log(ev)
//	  $("table#subproduct tbody input[type=text]").each(function() {
//		
//		if (this.value != "") {
//		  reqQty += parseFloat(this.value);
//		}
//		
//	  });
//	  
//	  App.Prices.each(function(m) {
//		var testQty = parseInt(m.attributes.metaKey.replace(',',''))
//		var testPrice = parseFloat(m.attributes.metaValue)
//////console.log(m)
//////console.log(testQty)
//////console.log(targetQty)
////
//		if (testQty == reqQty) {
//		  priceEa = testPrice;
////		  comp = testQty;
//		  return;
//		}
//		if (testQty < reqQty && testQty > pricePtQty) {
//		  priceEa = testPrice;
//		  pricePtQty = testQty; // comparation figure to help identify price point
//		}
//		
//	  })
//	  
////console.log(reqQty);
//	  priceTotal = priceEa * reqQty;
//	  gstTotal = priceTotal * gst / 100;
//	  
//	  $("#qty_total").html(reqQty);
//	  $("#price_ea").html(priceEa.toFixed(2));
//	  $("#price_total").html((priceTotal + gstTotal).toFixed(2));
////console.log(comp)
//	},
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
   * SUB PRODUCT
   * ======================================================================
   */  
  
  App.SubProduct = Backbone.Model.extend({
	
	idAttribute: 'productID',
	
	defaults: {
//	  parentID: productJSON.productID,
//	  code:'code',
//	  name:'new colour'
	},
	
	clear:function() {
//	  this.destroy();
	}
	
  });
  
  App.SubProductList = Backbone.Collection.extend({
	model: App.SubProduct,
	url: '/products',
	
	comparator:function(model) {
	  return model.get('code');
	}	
  });
  
  App.SubProducts = new App.SubProductList;
  
  App.SubProductView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-subproduct-list').html()),
	events: {
	  "click a.delete": "clear",
	  "click td.handle" : "edit",
	  "click a.save": "update"
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  var data = this.model.toJSON();
	  data['id'] = this.model.cid;
	  this.$el.html(this.template(data));
	  return this;
	},
	update: function() {
//	  var data = {};
//	  data.code = this.$el.find('input[name=code]').val();
//	  data.name = this.$el.find('input[name=name]').val();
//	  this.model.set(data).save();
//	  this.$el.find('.edit').hide();
//	  this.$el.find('.display').show();
	},
	edit:function() {
	  this.$el.find('.edit').show();
	  this.$el.find('.display').hide();
	},
	clear: function() {
	  if (confirm('Really Delete?')) {
		this.model.clear();
		this.remove();
	  }
	}
  });

  App.SubProductsView = Backbone.View.extend({
	el: $("#subproduct"),
	events: {
	  "click .addOne": "createOne"
	},
	initialize: function() {
	  App.SubProducts.bind('reset', this.addAll, this);
	  App.SubProducts.bind('add', this.add, this);
	},
	createOne: function() {
	  App.SubProducts.create({wait:true});
	  return false;
	},
	add: function(model) {
	  var view = new App.SubProductView({model: model});
	  $('#subproduct tbody').append(view.render().el);
	},
	addAll: function() {
	  App.SubProducts.each(this.add);
	}
  });
  
  new App.SubProductsView;
  App.SubProducts.reset(subProductJSON);



  
});
