var App = App || {};

$(function(){
  
  /*
   * ======================================================================
   * PRODUCT
   * ======================================================================
   */
  
  App.Product = Backbone.Model.extend({
	
	urlRoot: '/products',
	
	idAttribute: 'productID',
	
	defaults: {
	  parentID:'0',
	  category:'Community',
	  name:'New product',
	  description:'This is a new product',
	  sort:''
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  
  App.ProductView = Backbone.View.extend({

	el: $("#product"),
	
	template: _.template($('#tpl-entity').html()),
	
	events:{
//	  "click .handle" : "openEdit",
	  "click .editable" : "openEdit",
	  "keypress input" : "updateOnEnter",
	  "change select" : "updateOnChange",
	  "blur textarea" : "updateOnBlur"
	},
	
	initialize:function() {
	  this.model.bind('change', this.render, this);
	},
	
	render: function() {
	  
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
	
	updateOnEnter: function(ev) {
	  if (ev.keyCode == 13) {
		if (this.update(ev)) {
		  this.closeEdit(ev);
		}
		
	  }
	},
	updateOnChange:function(ev) {
	  if (this.update(ev)) {
		this.closeEdit(ev);
	  }
	  
	},
	updateOnBlur:function(ev) {
	  if (this.update(ev)) {
		this.closeEdit(ev);
	  }
	  
	},
	update: function(ev) {
	  var data = {};
	  
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data).save();
	  return true;
	},
	openEdit: function(ev) {
	  $(ev.currentTarget).removeClass('editable');
	  $(ev.currentTarget).children('.edit').show().children('input').focus();
	  $(ev.currentTarget).children('.display').hide();
	},
	
	closeEdit:function(ev) {
	  $(ev.target).parent().hide();
	  $(ev.target).parent().siblings('.display').show();
	  $(ev.target).parent().parent().addClass('editable');
	}

  });

  var product = new App.Product(productJSON);
  var view = new App.ProductView({model:product});
  view.render();
  
  
  /*
   * ======================================================================
   * SUB PRODUCT
   * ======================================================================
   */  
  
  App.SubProduct = Backbone.Model.extend({
	
	idAttribute: 'productID',
	
	defaults: {
	  parentID: productJSON.productID,
	  code:'code',
	  name:'new colour'
	},
	
	clear:function() {
	  this.destroy();
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
	  "click td.editable" : "edit",
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
	  var data = {};
	  data.code = this.$el.find('input[name=code]').val();
	  data.name = this.$el.find('input[name=name]').val();
	  this.model.set(data).save();
	  this.$el.find('.edit').hide();
	  this.$el.find('.display').show();
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


  /*
   * ======================================================================
   * PRICE
   * ======================================================================
   */  
  
  App.Price = Backbone.Model.extend({
	
	urlRoot: '/product_metas',
	
	idAttribute: 'productMetaID',
	
	defaults: {
	  productsID: productJSON.productID,
	  schemaName:'price',
	  metaKey:'',
	  metaValue:'',
	  sort:'1'
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.PriceList = Backbone.Collection.extend({
	model: App.Price,
	url: '/product_metas',
	
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
	  "click a.delete": "clear",
	  "click td.editable" : "edit",
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
	  var data = {};
	  
	  this.$el.find('input').each(function(){
		data[this.name] = this.value;
	  });
	  this.model.set(data).save();
	  this.$el.find('.edit').hide();
	  this.$el.find('.display').show();
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

  App.PricesView = Backbone.View.extend({
	el: $("#price"),
	events: {
	  "click .addOne": "createOne"
	},
	initialize: function() {
	  App.Prices.bind('reset', this.addAll, this);
	  App.Prices.bind('add', this.add, this);
	},
	createOne: function() {
	  App.Prices.create({wait:true});
	  return false;
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
	url: '/product_images',
	
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
	  "dblclick img": "clear"
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
	clear: function() {
	  if (confirm('Really Delete?')) {
		this.model.clear();
		this.remove();
	  }
	}
  });

  App.ImagesView = Backbone.View.extend({
	el: $("#image"),
	events: {},
	initialize: function() {
	  App.Images.bind('reset', this.addAll, this);
	  App.Images.bind('add', this.add, this);
	  
	},
	createOne: function(d) {
	  App.Images.create(d,{wait:true});
	  this.dragSort();
	  return false;
	},
	dragSort:function() {
	  
	  var $el = this.$el;
	  $el.dragsort('destroy');
	  $el.dragsort({
		
		dragEnd: function() {
		  
		  $el.find('img').each(function(i) {
			var id = $(this).attr('data-id');
			var item = App.Images.getByCid(id);
			if (item.get('sort') != i+1) item.set({sort:i+1},{silent:true}).save();
			
		  });

		}
	  });
	},
	add: function(model) {
	  var view = new App.ImageView({model: model});
	  var elem = view.render().el
	  $('#image').append(elem);
	},
	addAll: function() {
	  App.Images.each(this.add);
	  this.dragSort();
	}
  });
  
  var imagesView = new App.ImagesView;
  App.Images.reset(imageJSON);

  App.ImageUploader = new qq.FileUploader({
	
	element: $('#image-uploader')[0],
	action: '/fileUploader.php?dir=images',
	debug: false,
	
	onComplete:function(id, filename, responseJSON){
	  var imgDir = filename.split('.').slice(0,-1)
	  var data = {metaKey:filename,imgDir:imgDir};
	  imagesView.createOne(data)
	  
	}
	
  });


});
