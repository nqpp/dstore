var App = App || {};

$(function() {

  /*
   * ======================================================================
   * LOCATION
   * ======================================================================
   */  
  
  App.Location = Backbone.Model.extend({
	
	idAttribute: 'locationID',
	
	defaults: {
	  postcode:'',
	  suburb:'New location'
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.LocationList = Backbone.Collection.extend({
	model: App.Location,
	url: '/locations',
	
	comparator:function(model) {
	  return model.get('postcode');
	}
	
  });
  
  App.Locations = new App.LocationList;
  
  App.LocationView = Backbone.View.extend({
	tagName: 'tr',
	
	attributes: function() {
	  var data = {};
	  data["data-id"] = this.model.cid
	  return data;
	},
	
	template: _.template($('#tpl-list').html()),
	
	events: {
	  "click .editable":"edit",
	  "keypress input" : "updateOnEnter",
	  "click a.save":"update",
	  "click a.delete":"clear"
	},
	
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  return this;
	},
	updateOnEnter:function(ev) {
	  
	  if (ev.keyCode == 13) {
		this.update()
	  }
	},
	update:function() {
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

	  this.$el.find('.edit').hide();
	  this.$el.find('.display').show();
	},
	edit:function(ev) {
	  this.$el.find('.edit').show();
	  this.$el.find('.display').hide();
	},
	clear: function() {
	  if (confirm('Really Delete?')) {
		this.model.clear();
		this.remove();
	  }
	}
  });


  App.LocationsView = Backbone.View.extend({
	el: $("#locationApp"),
	events: {
//	  "click a#toglDialg": "toggleDialog",
	  "keypress #new-location": "createOnEnter"
	},
	initialize: function() {
	  
	  this.input = $('#new-location');
	  
	  App.Locations.bind('reset', this.addAll, this);
	  App.Locations.bind('add', this.add, this);
	},
//	toggleDialog: function() {
//	  
//	  this.dialog.toggle();
//	  return false;
//	},
	createOnEnter: function(ev) {
  
	  if (ev.keyCode != 13) return;
	  if (!this.input.val()) return;
	  
	  var data = {}
	  data[this.input.attr('name')] = this.input.val()
	  App.Locations.create(data,{wait:true});
//	  App.Locations.create({name:this.input.val()},{wait:true});
	  
	  this.input.val('').blur();
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