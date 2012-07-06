var Cart = Cart || {};

$(function() {
	
	Cart.Item = Backbone.Model.extend();
	
	Cart.ItemList = Backbone.Collection.extend({
		
		url: '/carts',
		model: Cart.Item
	});
	
	Cart.List = new Cart.ItemList;
	
	Cart.ItemView = Backbone.View.extend({
		
		template: _.template($('#cart-item-tpl').html()),
		
		render: function() {
			
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		}
	}),
	
	Cart.View = Backbone.View.extend({
		
		el: $('#cart-contain'),
		template: _.template($('#cart-tpl').html()),
		initial: true,
		
		initialize: function() {
			
			this.$el.html(this.template());
			Cart.List.bind('reset', this.render, this);
		},
		
		render: function() {
			

			$('#cart').html('');
			Cart.List.each(this.add);
			
			if(Cart.List.length > 0) {
				
				this.$el.find('.dropdown-toggle').removeClass('disabled');
			} else {
				
				this.$el.find('.dropdown-toggle').addClass('disabled');
			}
			
			if(!this.initial) this.changed();
			if(this.initial) this.initial = false;
			
			return this;
		},
		
		changed: function() {
			
			this.$el.find('.btn').addClass('btn-success');
			var self = this;
			setTimeout(function() {
				self.$el.find('.btn').removeClass('btn-success', 'normal');
			}, 1000);
			
			return this;
		},
		
		add: function(item) {

			view = new Cart.ItemView({model: item});
			$('#cart').append(view.render().el);
			return this;
		}
	});
	
	new Cart.View;
	Cart.List.fetch();
});