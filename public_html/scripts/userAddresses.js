App = App || {};

$(function() {
	
	App.UserAddressesView = Backbone.View.extend({
		
		el: $('#deliveryAddressID'),
		
		render: function() {
			
			this.$el.html('');
			this.delegateEvents({'change' : 'updateUserAddressID'});
			this.addAll();
			return this;
		},
		
		add: function(item) {
			
			item.set({'selectedID': userAddressID}, {silent: true});

			var tpl = _.template('<option value="<%=userAddressID %>"<% if(selectedID == userAddressID) { %> selected="selected"<% } %>><%=address %>, <%=suburb %> <%=state %> <%=postcode %></option>');
			this.$el.append(tpl(item.toJSON()));
			return this;
		},
		
		addAll: function() {
			
			App.UserAddresses.each(this.add, this);
			return this;
		},
		
		updateUserAddressID: function(ev) {
			
			userAddressID = $(ev.target).val();
			App.UserAddresses.each(function(item) {
				
				item.set({'selectedID': userAddressID}, {silent: true});

			}, this);
			
			App.UserAddresses.trigger('change');
			
			return this;
		}
	});
	
	App.UserAddressItemsView = new App.UserAddressesView();
	App.UserAddresses = new Backbone.Collection(userAddresses);
});