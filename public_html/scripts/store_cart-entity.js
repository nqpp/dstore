var App = App || {};

$(function() {
  
  /*
   * ======================================================================
   * STORE CART
   * ======================================================================
   */  

  App.Cart = Backbone.Model.extend({
	
	idAttribute: 'cartID',
	initialize: function() {
	  this.cartItems = new App.CartItemList(this.items);
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
		
		
  });

  App.CartItemsView = Backbone.View.extend({
		
	tagName: 'tr',
	template: _.template($('#tpl-cart-item').html()),
  });
	
  App.CartSubItemsView = Backbone.View.extend({
		
	});
});

// Temporary test instantiation code
$(function() {
  App.Carts.reset(cartJSON);
  console.log(App.Carts);
});