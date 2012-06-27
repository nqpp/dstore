var App = App || {};

$(function() {
  
  /*
   * ======================================================================
   * STORE CART
   * ======================================================================
   */  

  App.Cart = Backbone.Model.extend({
	
	idAttribute: 'cartID',
	
	cartItems: function() {
	  return new App.CartItemList(this.get('items'));
	}
	
  });

  App.CartItems = Backbone.Model.extend({
	
	idAttribute: 'cartItemID'
  });
  
  App.CartList = Backbone.Collection.extend({
	
	url: '/carts',	
	model: App.Cart
  });

  App.CartItemList = Backbone.Collection.extend({
		
	url: '/cart_items',
	model: App.CartItems
  });
  
  App.Carts = new App.CartList;
  
  App.CartView = Backbone.View.extend({
	
	el: $('#cart'),
	initialize: function() {
			
	  App.Carts.bind('reset', this.addAll, this);
	  App.Carts.bind('add', this.add, this);
	},
	add: function(item) {
			
	  var view = new App.CartItemView({model: item});
	  
	  $('#cart').append(view.render().el);
	  
	},
	addAll: function() {
			
	  App.Carts.each(this.add);
	}
  });

  App.CartItemView = Backbone.View.extend({
		
	tagName: 'tr',
	template: _.template($('#tpl-cart-item').html()),
	events: {
	  'click a.remove': 'remove'
	},
	initialize: function() {
		
	  this.model.bind('change', this.render, this);
	},
	
	render: function() {
			
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
		
	remove: function() {

	  this.model.destroy();
	  return false; // Needed?
	},
		
	add: function(item) {

	  var view = new App.CartSubItemsView({model: item});
	  this.$el.find('table').append(view.render().el);
	},
		
	addAll: function(parent) {

	  parent.cartItems().each(this.add);
	}
  });
	
  App.CartSubItemsView = Backbone.View.extend({
		
	tagName: 'tr',
	template: _.template($('#tpl-cart-item-item').html()),
	render: function() {
			
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	}
  });
});

// Temporary test instantiation code
$(function() {
	
  new App.CartView;
  App.Carts.reset(cartJSON);
});