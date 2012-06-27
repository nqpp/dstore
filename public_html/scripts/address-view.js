App = App || {}

$(function() {
  
  App.vent = _.extend({},Backbone.Events);
  
  App.AddressView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-address-list').html()),
	events: {
	  "keypress input" : "updateOnEnter",
	  "keyup input[name=city]" : "doTypeAhead",
	  "click a.delete": "clear",
	  "click td.editable" : "show",
	  "change select.change-update": "update",
	  "click a.save": "update"
	},
	
	initialize: function() {

	  this.recordLimit = 10;
	  this.model.bind('change', this.render, this);
	  
	  _.bindAll(this, "locationSelected");
	  App.vent.bind(this.cid+":locationSelected", this.locationSelected); // use name space

	  this.collection = new App.LocationList();
	  
	},
	
	render: function() {
	  var data = this.model.toJSON();
	  data.id = this.model.id;
	  this.$el.html(this.template(data));
	  this.setTypeAhead();
	  this.streetInput = this.$el.find('input[name=address]')[0];
	  return this;
	},
	
	setTypeAhead: function() {
	  
	  this.location = new App.LocationsView({
		el: '#typeahead-'+this.model.id,
		collection:this.collection,
		namespace:this.cid
	  });
	},
	doTypeAhead: function(ev) {

	  var input = ev.currentTarget;
	  
	  if (ev.keyCode == 8 || ev.keyCode == 46) return; // backspace or delete
	  if (input.value.length < 2) return;

	  var data = {}
	  data.filter = input.value;
	  data.limit = this.recordLimit;
	  this.collection.fetch({data:data})	  

	},
	locationSelected: function(model) {
	  var data = model.toJSON();
	  
	  data.city = data.suburb;
	  data.locationsID = data.locationID;
	  data.address = this.streetInput.value;
	  
	  this.model.set(data).save();
	  this.collection.reset();
	  this.hide();

	},
	updateOnEnter:function(ev) {
	  
	  if (ev.keyCode == 13) {
		this.update()
	  }
	},
	update: function() {
	  var data = {};

	  this.$el.find('.edit input').each(function() {
		data[this.name] = this.value
	  });
	  this.$el.find('.edit select').each(function() {
		data[this.name] = this.value
	  });
	  this.$el.find('.edit textarea').each(function() {
		data[this.name] = this.value
	  });
	  
	  this.model.set(data).save();
	  this.hide();
	},
	show:function() {
	  this.$el.find('.edit').show();
	  this.$el.find('.display').hide();
	},
	hide: function() {
	  this.$el.find('.edit').hide();
	  this.$el.find('.display').show();
	},
	clear: function() {
	  if (confirm('Really Delete?')) {
		this.model.clear();
		this.remove();
	  }
	}
  });
  
  App.AddressesView = Backbone.View.extend({
	el: $("#address"),
	events: {
	  "click .addContact": "createOne"
	},
	initialize: function() {
	  App.Addresses.bind('reset', this.addAll, this);
	  App.Addresses.bind('add', this.add, this);
	},
	createOne: function() {
	  App.Addresses.create({wait:true});
	  return false;
	},
	add: function(model) {
	  var view = new App.AddressView({model: model,vent:App.vent});
	  $('#address tbody').append(view.render().el);
	  view.setTypeAhead();
	},
	addAll: function() {
	  App.Addresses.each(this.add);
	}
  });
  
  
});