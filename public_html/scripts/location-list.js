var App = App || {};

$(function() {
  
  /*
   * ======================================================================
   * TABLE PAGINATION
   * ======================================================================
   */  
  
  App.Location = Backbone.Model.extend({

  });

  App.LocationList = Backbone.Collection.extend({

	model:App.Location
  });

  App.Locations = new App.LocationList;

  App.LocationView = Backbone.View.extend({

	tagName: 'tr',
	template: _.template($('#tpl-list').html()),

	initialize: function() {
	  this.model.bind('change', this.render, this);
	},

	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  return this;
	}

  });
	
  App.LocationsView = Backbone.View.extend({
	  
	el: $("#locationApp"),
	events: {
	},
	initialize: function() {
	  
	  App.Locations.bind('reset', this.addAll, this);
	  App.Locations.bind('add', this.add, this);
	},
	add: function(model) {
	  var view = new App.LocationView({model: model});
	  $('#locations tbody').append(view.render().el);
	},
	addAll: function() {
	  App.Locations.each(this.add);
	}

  });
	
  new App.LocationsView;
  App.Locations.reset(locationsJSON);
	
  
});