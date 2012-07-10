var App = App || {}

$(function() {
  
  /*
   * ======================================================================
   *  EMPLOYMENT DATA
   * ======================================================================
   */
  
  App.EmploymentDataView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-employment-data-list').html()),
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

  App.EmploymentDatasView = Backbone.View.extend({
	el: $("#employment-data"),
	events: {
	  "click .addContact": "createOne"
	},
	initialize: function() {
	  App.EmploymentDatas.bind('reset', this.addAll, this);
	  App.EmploymentDatas.bind('add', this.add, this);
	},
	createOne: function() {
	  App.EmploymentDatas.create({wait:true});
	  return false;
	},
	add: function(model) {
	  var view = new App.EmploymentDataView({model: model});
	  $('#employment-data tbody').append(view.render().el);
	},
	addAll: function() {
	  App.EmploymentDatas.each(this.add);
	}
  });
  

})
