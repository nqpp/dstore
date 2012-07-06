var App = App || {};

$(function() {
	
	App.OrderCartItem = Backbone.Collection.extend({
		
		url: '/mycart'
	});
	
	App.OrderCartItems = new App.OrderCartItem();
	
	App.OrderItemView = Backbone.View.extend({
		
		tagName: 'tr',
		template: _.template($('#popCartItem').html()),
		
		render: function() {
			
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		}
	});
	
	App.OrderView = Backbone.View.extend({
		
		el: $('#orderModal'),
		template: _.template($('#popCart').html()),
		events: {
			'keydown #purchaseOrder' : 'allowPost'
		},
		
		initialize: function() {

			App.UserAddresses.bind('change', this.updateCart, this);
			App.OrderCartItems.bind('reset', this.addAll, this);
			this.model.bind('change', this.updateTotals, this);
			return this;
		},
		
		addAll: function() {
			
			App.OrderCartItems.each(this.add, this);
			this.model.change();
			return this;
		},
		
		add: function(item) {

			this.model.set({freightTotal: this.model.get('freightTotal') + item.get('freightTotal')}, {silent: true});
			this.model.set({cartTotal: this.model.get('cartTotal') + item.get('total')}, {silent: true});

			var view = new App.OrderItemView({model: item});
			this.$el.find('tbody').append(view.render().el);
			return this;
		},
		
		render: function() {

			this.$el.find('#orderModalCart').html(this.template(this.model.toJSON()));
			
			App.UserAddressItemsView.setElement($('#deliveryAddressID'));
			App.UserAddressItemsView.render();
			
			return this;
		},
		
		allowPost: function(ev) {
			
			if($(ev.target).val() !== '') {
				
				this.$el.find('#doOrder').removeAttr('disabled');
			} else {
				
				this.$el.find('#doOrder').attr('disabled', true);
			}
			
			return this;
		},
		
		updateCart: function() {
			
			this.$el.find('tbody').html('');
				this.model.set({freightTotal: 0}, {silent: true});
				this.model.set({cartTotal: 0}, {silent: true});
			
			App.OrderCartItems.fetch({data: '{"deliveryAddressID":"' + userAddressID + '"}', type: 'POST', dataType: 'json', contentType: 'application/json;charset=UTF-8'});
			return this;
		},
		
		updateTotals: function() {
			
			this.$el.find('#freightTotal').html(this.model.get('freightTotal').toFixed(2));
			this.$el.find('#cartTotal').html(this.model.get('cartTotal').toFixed(2));
		}
	});
	
	var view = new App.OrderView({model: new Backbone.Model({freightTotal: 0, cartTotal: 0})});
	view.render();
	App.OrderCartItems.reset(carts);
});

$(function() {
  
  $('.cart-item-delete').click(function() {
	return confirm('Really remove from cart?');
  });

	$('#orderAction').modal({show: false});
});