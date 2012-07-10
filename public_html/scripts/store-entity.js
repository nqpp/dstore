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
	  'click #add_to_cart': 'update',
	},
		
	initialize: function() {
			
	  this.model.set({
		productsID: productsID
	  });

		App.UserAddresses.bind('change', this.updateAddress, this);
	  App.CartItems.bind('reset', this.render, this);
	  App.CartItems.bind('change', this.check, this);
	  this.model.bind('change', this.updateTotals, this);
	},
		
	render: function() {

	  this.updateTotals();
	  this.addAll();
	},
	
	updateTotals: function() {
		
		this.$el.find('tfoot').html(this.template(this.model.toJSON()));
		
		if(this.model.id) this.$el.find('#add_to_cart').html('Update');
	  if(this.model.get('moqReached')) this.$el.find('#add_to_cart').attr('disabled', false);
	
		App.UserAddressItemsView.setElement($('#deliveryAddressID'));
		App.UserAddressItemsView.render();
		this.model.set({deliveryAddressID: userAddressID});
	
		return this;
	},
		
	addAll: function() {

	  App.CartItems.each(this.add, this);
	  return this;
	},
		
	add: function(item) {

	  var view = new App.CartItemView({ model: item });
	  this.$el.find('tbody').append(view.render().el);
	  return this;
	},
		
	update: function() {
			
	  this.model.urlRoot = '/carts';
	  this.model.save({},{
		wait: true, 
		success: function() {
				
		  App.CartItems.each(function(item) {

			if(item.get('qty') !== null) item.save({
			  silent: true
			});
		  });
			Cart.List.fetch();
		}
	  });

		this.model.urlRoot = '/check_cart'; // Change back to check_cart for calculation.
	},
	
	updateAddress: function() {

		this.model.set({deliveryAddressID: App.UserAddressesView.userAddressID});
		this.check();
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