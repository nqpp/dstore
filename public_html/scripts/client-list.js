var App = App || {}

$(function() {
  
  App.Client = Backbone.Model.extend({
	
	idAttribute: 'clientID',
	
	defaults: {
	  name:'New client'
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.ClientList = Backbone.Collection.extend({
	model: App.Client,
	url: '/clients',
	
	comparator:function(model) {
	  return model.get('name');
	}
	
  });
  
  App.Clients = new App.ClientList;
  
  App.ClientView = Backbone.View.extend({
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


  App.ClientsView = Backbone.View.extend({
	el: $("#clientApp"),
	events: {
	  "keypress #new-client": "createOnEnter"
	},
	initialize: function() {
	  
	  this.input = $('#new-client');
	  
	  App.Clients.bind('reset', this.addAll, this);
	  App.Clients.bind('add', this.add, this);
	},
	createOnEnter: function(ev) {
  
	  if (ev.keyCode != 13) return;
	  if (!this.input.val()) return;
	  
	  var data = {}
	  data[this.input.attr('name')] = this.input.val()
	  App.Clients.create(data,{wait:true});
	  
	  this.input.val('').blur();
	},
	add: function(model) {
	  var view = new App.ClientView({model: model});
	  $('#clients tbody').append(view.render().el);
	},
	addAll: function() {
	  App.Clients.each(this.add);
	}

  });
  
  new App.ClientsView;
  App.Clients.reset(clientsJSON);
  
});