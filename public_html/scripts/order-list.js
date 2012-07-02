var App = App || {}

$(function() {
  
  /*
   * ======================================================================
   * ORDER
   * ======================================================================
   */  
  
  // Models
  App.Order = Backbone.Model.extend({
	
	idAttribute: 'orderID',
	
	initialize: function() {
	  
	  this.OrderAddress = new App.OrderAddress(orderAddressesJSON[this.get('orderID')]);
	  this.OrderAddress.parent = this;

	  this.OrderProducts = new App.OrderProductList(orderProductsJSON[this.get('orderID')]);
	  this.OrderProducts.parent = this;
	  
	  this.orderTotal = 0;
	  this.productCalc();
	},
	
	productCalc: function() {
	  var self = this;
		
	  this.OrderProducts.each(function(model) {
		var data, rowTotal;
		
		data = model.toJSON();
		rowTotal = parseFloat(parseFloat(data.subTotal) + parseFloat(data.freightTotal) + parseFloat(data.gst));
		self.orderTotal += rowTotal;
		model.set('total', rowTotal.toFixed(2));
		
	  });
	  
	  self.set('orderTotal', self.orderTotal);
	}
	
  });
  
  App.OrderProduct = Backbone.Model.extend({
	
	initialize: function() {

	  this.OrderProductQuantity = new App.OrderProductQuantityList(orderProductQuantitiesJSON[this.get('orderProductID')]);
	  this.OrderProductQuantity.parent = this;
	}
	
  });
  
  App.OrderProductQuantity = Backbone.Model.extend({
	
  });
  
  App.OrderAddress = Backbone.Model.extend({
	
  });
  
  App.StatusFilter = Backbone.Model.extend({

	idAttribute: 'userPrefID',

	defaults: {
	  usersID: App.userID,
	  prefName: "orderStatusFilter",
	  metasID: "",
	  preference: "0" 
	}
  });
  
  
  // Collections
  App.OrderList = Backbone.Collection.extend({
	model: App.Order,
	url:"/orders"
	
  });
  
  App.Orders = new App.OrderList;
  
  App.OrderProductList = Backbone.Collection.extend({
	model: App.OrderProduct
	
  });
  
  
  App.OrderProductQuantityList = Backbone.Collection.extend({
	model: App.OrderProductQuantity
	
  });
  
  App.OrderAddressList = Backbone.Collection.extend({
	model: App.OrderAddress
	
  });
  
  App.StatusFilterList = Backbone.Collection.extend({
	model: App.StatusFilter,
	url: "/user_prefs"
	
  });
  
  
  
  // Views
  App.OrderView = Backbone.View.extend({
	
	tagName: 'div',
	attributes: {
	  class: 'accordion-group'
	},
	template: _.template($('#tpl-list').html()),
	events: {
	  "click ul.status li a": "updateStatus"
	},
	
	initialize: function() {
	  
	  this.model.bind('change', this.render, this);
	},
	
	updateStatus: function(ev) {
	  var a = ev.currentTarget;
	  this.model.set('status',a.title).save();

	},
	
	render: function() {
	  var data = this.model.toJSON();
	  data.cid = this.model.cid;
	  data.orderTotal = this.model.orderTotal;
	  this.$el.html(this.template(data));
	  
	  this.addProducts();
	  this.addAddress();
	  
	  return this;
	},
	
	addAddress: function() {
	  var view = new App.OrderAddressView({model:this.model.OrderAddress});
	  this.$el.find('div.orderAddress').append(view.render().el);
	  
	},
	
	addProducts: function() {
	  var self = this;
		
	  this.model.OrderProducts.each(function(model) {
		var view = new App.OrderProductView({model:model});
		self.$el.find('table.orderProducts > tbody').append(view.render().el);
		
	  });

	}
  });
  
  
  App.OrderProductView = Backbone.View.extend({
	
	tagName: 'tr',
	template: _.template($('#tpl-order-products-list').html()),
	
	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  
	  this.addQuantities();
	  
	  return this;
	},
	
	addQuantities: function() {
	  var self = this;
	  
	  this.model.OrderProductQuantity.each(function(model) {

		var view = new App.OrderProductQuantityView({model:model});
		self.$el.find('table.quantities > tbody').append(view.render().el);
		
	  });
	  
	}
	
  });
  
  App.OrderProductQuantityView = Backbone.View.extend({
	
	tagName: 'tr',
	template: _.template($('#tpl-quantities-list').html()),
	
	initialize: function() {
	  
	},
	
	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  
	  return this;
	}
	
  });
  
  
  App.OrderAddressView = Backbone.View.extend({
	
	tagName: 'p',
	template: _.template($('#tpl-order-address').html()),
	
	initialize: function() {
	  
	},
	
	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  
	  return this;
	}
	
  });
  
  App.StatusFilterView = Backbone.View.extend({
	tagName: 'a',
	attributes: function(){
	  var self = this;
	  var data = {}
	  data['href'] = '#';
	  data['class'] = function() {
		var className = 'btn pull-right';
		className += (self.model.get('preference') == '1') ? ' active':''
		return className;
	  }
	  return data;
	},
	template: _.template($("#tpl-status-filter").html()),
	events: {
	  "click span": "setPref"
	},
	
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	
	setPref: function(ev) {
	  var node,self;
	  self = this;
	  // sodding thing needs to wait for DOM to catch up.
	  setTimeout(function() {
		node = ev.target.parentNode;
		
		if ($(node).hasClass('active')) {
		  self.model.save({'preference':1},{wait:true});
		}
		else {
		  self.model.save({'preference':0},{wait:true});
		}
		
	  },10)

	},
	
	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  
	  return this;
	}
  });



  App.OrdersView = Backbone.View.extend({
	el: $("#orderAccordion"),
	
	initialize: function() {
	  
	  this.addFilters();
	  
	  App.Orders.bind('reset', this.addAll, this);
	  App.Orders.bind('add', this.add, this);
	},
	
	reload: function() {
	  App.Orders.fetch();
	},
	
	addFilters: function() {
	  var self = this;
	  
	  if (orderStatusFilterJSON.length) {
		this.StatusFilters = new App.StatusFilterList(orderStatusFilterJSON);
	  }
	  else {
		this.StatusFilters = new App.StatusFilterList(orderStatusTypesJSON);
		console.log('using fallback status filters')
	  }
	  
	  this.StatusFilters.bind('change', this.reload,this);

	  this.StatusFilters.each(function(model) {
		var view = new App.StatusFilterView({model:model});
		$('#statusFilter').append(view.render().el);
		
		// prefs not set for this user. time to set them.
		if (model.get('metasID') == '') {
		  model.save({silent:true});
		}
	  });
	  
	},
	
	add: function(model) {
	  var view = new App.OrderView({model: model});
	  $('#orderAccordion').append(view.render().el);
	},
	addAll: function() {
	  $('#orderAccordion').html('');
	  App.Orders.each(this.add);
	}

  });
  
  new App.OrdersView;
  App.Orders.reset(ordersJSON);
  
});

