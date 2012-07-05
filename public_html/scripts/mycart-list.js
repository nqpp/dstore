var App = App || {};

$(function() {
	
	App.OrderView = Backbone.View.extend({
		
		el: $('#orderModal'),
		template: _.template($('#popCart').html()),
		events: {
			'change #deliveryAddressID': 'updateCart'
		},
		
		initialize: function() {
			
			this.model.bind('update', this.render, this);
			return this;
		},
		
		render: function() {

			this.$el.find('#cart').html(this.template(this.model.toJSON));
			
			App.UserAddressItemsView.setElement($('#deliveryAddressID'));
			App.UserAddressItemsView.render();
			return this;
		},
		
		updateCart: function() {
			
			this.model.urlRoot('/carts');
			this.model.save('',{wait: true});
			return this;
		}
	});
	
	var view = new App.OrderView({model: new Backbone.Model()});
	view.render();
});

$(function() {
  
  $('.cart-item-delete').click(function() {
	return confirm('Really remove from cart?');
  });
});