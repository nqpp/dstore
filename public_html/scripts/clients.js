var App = App || {};

$(function() {
  
  App.Client = Backbone.Model.extend({
	idAttribute: 'clientID',
	defaults: {
	  name:'',
	  billingName:'',
	  abn:''
	},
	clear: function() {
	  this.destroy();
	}
  });
  
  App.ClientList = Backbone.Collection.extend({
	model:Client,
	url:'/clients'
  });
  
  App.Clients = new App.ClientList;
  
  App.ClientView = Backbone.View.extend({
	tagName:'tr',
	template: _.template($('#tpl-client-list').html()),
	events: {
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	add: function(client) {
	  var view = new App.ClientView({model: client});
	  $('#clients').append(this.render().el);
	},
	render: function() {
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
	update: function(ev) {
	  var data = {}; 
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data).save();
	}

  });
  
  var ClientsView = Backbone.View.extend({
	el: $("#clientApp"),
	events: {
	  "keypress #new-client": "createOnEnter"
	},
	initialize: function() {
	  
	  this.input = $('#new-client');
	  
	  Clients.bind('reset', this.addAll, this);
	  Clients.bind('add', this.add, this);
	},
	createOne: function() {
	  Clients.create({wait:true});
	  return false;
	},
	createOnEnter: function(ev) {
  
	  if (ev.keyCode != 13) return;
	  if (!this.input.val()) return;
	  
	  var data = {}
	  data[this.input.attr('name')] = this.input.val()
	  App.Zones.create(data,{wait:true});
	  
	  this.input.val('').blur();
	},
	add: function(model) {
	  var view = new ClientView({model: model});
	  $('#clients tbody').append(view.render().el);
	},
	addAll: function() {
	  Clients.each(this.add);
	}
  });
   
  new ClientsView;
  Clients.reset(clientsJSON);

});
