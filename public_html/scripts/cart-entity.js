var App = App || {};

$(function() {
  
  /*
   * ======================================================================
   * CART
   * ======================================================================
   */  
  
  App.Cart = Backbone.Model.extend({
	
	urlRoot: '/carts',
	idAttribute: 'cartID'
	
  });
  
  App.CartList = Backbone.Collection.extend({
	
	model: App.Cart
	
  });
  
  App.Carts = new App.CartList;
  
  App.CartView = Backbone.View.extend({
	
  });
  
  App.CartViews = Backbone.View.extend({
	
  });


  /*
   * ======================================================================
   * CART ITEM
   * ======================================================================
   */  
  
  App.CartItem = Backbone.Model.extend({
	
	idAttribute: 'cartItemID'
	
  });
  
  App.CartItemList = Backbone.Collection.extend({
	
	model: App.CartItem
	
  });
  
  App.CartItems = new App.CartItemList;
  
  App.CartItemView = Backbone.View.extend({
	
  });
  
  App.CartItemViews = Backbone.View.extend({
	
  });
  
});