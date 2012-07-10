var App = App || {}

$(function() {
  
  /*
   * ======================================================================
   *  PHONE	 
   * ======================================================================
   */
  
  App.PhoneView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-phone-list').html()),
	events: {
	  "click .editable" : "edit",
	  "click a.delete": "clear",
	  "keypress input" : "updateOnEnter",
	  "change select.change-update": "update",
	  "click a.save": "update"
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
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
	  this.$el.find('.edit').hide();
	  this.$el.find('.display').show();
	},
	edit:function() {
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

  App.PhonesView = Backbone.View.extend({
	el: $("#phone"),
	events: {
	  "click .addContact": "createOne"
	},
	initialize: function() {
	  App.Phones.bind('reset', this.addAll, this);
	  App.Phones.bind('add', this.add, this);
	},
	createOne: function() {
	  App.Phones.create({wait:true});
	  return false;
	},
	add: function(phone) {
	  var view = new App.PhoneView({model: phone});
	  $('#phone tbody').append(view.render().el);
	},
	addAll: function() {
	  App.Phones.each(this.add);
	}
  });
  

})
