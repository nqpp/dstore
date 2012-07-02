var App = App || {};

$(function() {
	
  App.Cart = Backbone.Model.extend({
		
	urlRoot: '/check_cart',
	idAttribute: 'cartID',
	defaults: {
	  qtyTotal: '',
	  itemPrice: '',
	  subtotal: '',
	  freightTotal: '',
	  gst: '',
	  total: '',
	  moqReached: false
	}
  });
	
  App.CartItem = Backbone.Model.extend({
		
	urlRoot: '/cart_items',
	idAttribute: 'cartItemID'
  });
	
  App.CartItemList = Backbone.Collection.extend({
		
	model: App.CartItem,
	comparator:function(model) {
	  return model.get('sort');
	}
  });
	
  App.CartItems = new App.CartItemList;
	
  App.CartItemView = Backbone.View.extend({
		
	tagName: 'tr',
	template: _.template($('#tpl-subproduct-list').html()),
	events: {
	  'change input': 'update'
	},
		
	render: function() {
			
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
		
	update: function(ev) {
			
	  this.model.set({qty: $(ev.target).val()});
	}
  });
	
  App.CartView = Backbone.View.extend({
		
	el: $('#cart_prep'),
	template: _.template($('#tpl-subproduct').html()),
	events: {
	  'click #add_to_cart': 'update'
	},
		
	initialize: function() {
			
	  this.model.set({
		productsID: productsID
	  });

	  App.CartItems.bind('reset', this.render, this);
	  App.CartItems.bind('change', this.check, this);
	  this.model.bind('change', this.render, this);
	},
		
	render: function() {

	  this.$el.html(this.template(this.model.toJSON()));
	  this.addAll();

	  if(this.model.id) this.$el.find('#add_to_cart').html('Update');
	  if(this.model.get('moqReached')) this.$el.find('#add_to_cart').attr('disabled', false);
	},
		
	addAll: function() {

	  App.CartItems.each(this.add);
	  return this;
	},
		
	add: function(item) {

	  var view = new App.CartItemView({
		model: item
	  });
	  $('#cart_prep').append(view.render().el);
	  return this;
	},
		
	update: function() {
			
	  // Remove checking event
	  App.CartItems.unbind('change', this.check, this);
			
	  this.model.urlRoot = '/carts';
	  this.model.save({},{
		wait: true, 
		success: function() {
				
		  App.CartItems.each(function(item) {

			if(item.get('qty') !== null) item.save({
			  silent: true
			});
		  });
		}
	  });
			
	  return this.render();
	},
		
	check: function() {
			
	  var qty = 0;

	  App.CartItems.each(function(item) {

		itemQty = parseInt(item.get('qty'));
		if(!isNaN(itemQty)) qty += itemQty;
	  });

	  this.model.save({
		qtyTotal: qty
	  }, {
		wait: true
	  });			
	}
  });
	
	
});

$(function() {
	
  new App.CartView({
	model: new App.Cart(cartJSON)
  });
  App.CartItems.reset(subProductJSON);
  $(".fb").fancybox();
});