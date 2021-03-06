var App = App || {};

$(function() {

  /*
   * ======================================================================
   * LOCATION
   * ======================================================================
   */  

  App.Zone = Backbone.Model.extend({
	
	urlRoot: '/zones',
	
	idAttribute: 'zoneID',
	
	defaults: {
	  postcode:'0000',
	  suburb:'new'
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  
  App.ZoneView = Backbone.View.extend({

	el: $("#zone"),
	
	template: _.template($('#tpl-entity').html()),
	
	events:{
	  "click .handle" : "openEdit",
	  "keypress input" : "updateOnEnter",
	  "blur textarea" : "updateOnBlur"
	},
	
	initialize:function() {
	  this.model.bind('change', this.render, this);
	},
	
	render: function() {
	  
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
	
	updateOnEnter: function(ev) {
	  if (ev.keyCode == 13) {
		if (this.update(ev)) {
		  this.closeEdit(ev);
		}
		
	  }
	},
	updateOnBlur:function(ev) {
	  if (this.update(ev)) {
		this.closeEdit(ev);
	  }
	  
	},
	update: function(ev) {
	  var data = {};
	  
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data).save();
	  return true;
	},
	openEdit: function(ev) {
	  $(ev.currentTarget).removeClass('handle');
	  $(ev.currentTarget).children('.edit').show().children('input').focus();
	  $(ev.currentTarget).children('.display').hide();
	},
	
	closeEdit:function(ev) {
	  $(ev.target).parent().hide();
	  $(ev.target).parent().siblings('.display').show();
	  $(ev.target).parent().parent().addClass('handle');
	}

  });

  var zone = new App.Zone(zoneJSON);
  var view = new App.ZoneView({model:zone});
  view.render();
  
});
