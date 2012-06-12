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
		
		initialize: function() {
			
			this.$el.html(this.template());
			Cart.List.bind('reset', this.render, this);
		},
		
		render: function() {
			
			Cart.List.each(this.add);
			this.$el.find('.btn').addClass('btn-success', 'normal');
			this.$el.find('.btn').removeClass('btn-success', 'normal');
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