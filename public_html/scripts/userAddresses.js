App = App || {};

$(function() {
	
	App.UserAddressesView = Backbone.View.extend({
		
		el: $('#deliveryAddressID'),
		
		render: function() {

			this.$el.find('.dropdown-menu').html('');
			this.delegateEvents({'click .dropdown-menu a' : 'updateUserAddressID'});
			this.addAll();
			return this;
		},
		
		add: function(item) {
			
			item.set({'selectedID': userAddressID}, {silent: true});
			
			if(item.get('userAddressID') == userAddressID) {

				var tpl = _.template('<%=address %><br /><%=suburb %> <%=state %> <%=postcode %>');
				this.$el.find('.dropdown-toggle span').html(tpl(item.toJSON()));
			}
			
			var tpl = _.template('<li><a href="#" rel="<%=userAddressID %>"><%=address %><br /><%=suburb %> <%=state %> <%=postcode %></a></li>');
			this.$el.find('.dropdown-menu').append(tpl(item.toJSON()));
			return this;
		},
		
		addAll: function() {
			
			App.UserAddresses.each(this.add, this);
			return this;
		},
		
		updateUserAddressID: function(ev) {

			userAddressID = $(ev.target).attr('rel');
			App.UserAddresses.each(function(item) {
				
				item.set({'selectedID': userAddressID}, {silent: true});

			}, this);
			
			App.UserAddresses.trigger('change');
			
			this.render();
		}
	});
	
	App.UserAddressItemsView = new App.UserAddressesView();
	App.UserAddresses = new Backbone.Collection(userAddresses);
});