var App = App || {}  
  
$(function() {
  
  App.Location = Backbone.Model.extend({});
  
  App.LocationList = Backbone.Collection.extend({

	model:App.Location,
	url: '/locations?filtered'
	
  });

  
  App.LocationView = Backbone.View.extend({

	tagName: 'li',
	template: _.template($('#tpl-typeahead-list').html()),
	events: {
	  "click a":"locationSelected"
	},

	initialize: function(options) {
	  this.model.bind('change', this.render, this);
	},
	
	locationSelected: function() {

	  App.vent.trigger(this.options.namespace+":locationSelected",this.model);
	  
	},

	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  return this;
	}

  });
	
  App.LocationsView = Backbone.View.extend({
	
	initialize: function() {
	  
	  _(this).bindAll('add');
	  this.collection.bind('add', this.add, this);
	  this.collection.bind('reset', this.addAll, this);
	  
	},
	
	add: function(model) {
	  var data = model.toJSON();
	  var view = new App.LocationView({model:model, namespace:this.options.namespace});
	  this.$el.append(view.render().el);
	  
	},	
	addAll: function() {
	  
	  this.$el.empty();
	  
	  if (this.collection.length == 0) {
		this.$el.hide();
		return;
	  }
	  
	  this.collection.each(this.add);
	  this.$el.show();
	}

  });
	
});
