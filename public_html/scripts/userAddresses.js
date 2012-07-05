App = App || {};

$(function() {
	App.UserAddresses = new Backbone.Collection();
	
	App.UserAddressesView = Backbone.View.extend({
		
		el: $('#deliveryAddressID'),
		
		render: function() {

			this.$el.html('');
			this.addAll();
			return this;
		},
		
		add: function(item) {

			var tpl = _.template('<option value="<%=userAddressID %>"><%=address %>, <%=suburb %> <%=state %> <%=postcode %></option>');
			this.$el.append(tpl(item.toJSON()));
			return this;
		},
		
		addAll: function() {

			App.UserAddresses.each(this.add, this);
			return this;
		}
	});
	
	App.UserAddressItemsView = new App.UserAddressesView();
	
	App.UserAddresses.reset(userAddresses);
});