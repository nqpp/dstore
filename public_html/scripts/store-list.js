var App = App || {};

$(function() {

  /*
   * ======================================================================
   * PRODUCTS
   * ======================================================================
   */  
  
  App.Product = Backbone.Model.extend({
	
	idAttribute: 'productID',
	
	defaults: {
	  name:'New product',
	  description:'This is a new product',
	  sort:'',
	  img:'no_image.png',
	  imgDir:'no_image'
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.ProductList = Backbone.Collection.extend({
	model: App.Product,
	url: '/products',
	
	comparator:function(model1,model2) {
	  var cat1 = model1.get('category');
	  var cat2 = model2.get('category');
	  var sort1 = model1.get('sort');
	  var sort2 = model2.get('sort');
	  
	  if (cat1 > cat2) return -1;
	  else if (cat1 < cat2) return 1;
	  
	  if (sort1 > sort2) return 1;
	  else if (sort1 < sort2) return -1;
	  else return 0;
	}
	
  });
  
  App.Products = new App.ProductList;
  
  App.ProductView = Backbone.View.extend({
	tagName: 'li',
	
	attributes: function() {
	  var data = {
		class:'span3'
	  };

	  data["data-id"] = this.model.cid
	  return data;
	},
	
	template: _.template($('#tpl-list').html()),
	
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
	  if (confirm('Really Delete?')) {
		this.model.clear();
		this.remove();
	  }
	}
  });


  App.ProductsView = Backbone.View.extend({
	el: $("#productApp"),
	events: {
	},
	initialize: function() {
	  
	  App.Products.bind('reset', this.addAll, this);
	  App.Products.bind('add', this.add, this);
	},
	add: function(model) {
	  var view = new App.ProductView({model: model});
	  $('#products').append(view.render().el);
	},
	addAll: function() {
	  App.Products.each(this.add);
	}

  });
  
  new App.ProductsView;
  App.Products.reset(productsJSON);
  
});