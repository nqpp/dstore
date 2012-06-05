var App = App || {}

$(function() {
  
  /*
   * ======================================================================
   * CONTACT
   * ======================================================================
   */
  
  App.Contact = Backbone.Model.extend({
	
	urlRoot: '/contacts',
	
	idAttribute: 'userID',
	
	defaults: {
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  
  App.ContactView = Backbone.View.extend({

	el: $("#contact"),
	
	template: _.template($('#tpl-entity').html()),
	
	events:{
	  "click .contact-delete" : "clear",
	  "keypress input" : "updateOnEnter",
	  "change select" : "updateOnChange",
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
		this.update(ev);
	  }
	},
	updateOnChange:function(ev) {
	  this.update(ev);
	},
	updateOnBlur:function(ev) {
	  this.update(ev);
	},
	update: function(ev) {
	  var data = {};
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data).save();
	  
	  return true;
	},
	clear:function() {
	  return confirm('Really Delete?');
	}

  });

  var contact = new App.Contact(contactJSON);
  var view = new App.ContactView({model:contact});
  view.render();
  

  /*
   * ======================================================================
   * ADDRESS
   * ======================================================================
   */
  
  App.Address = Backbone.Model.extend({
	
	idAttribute: 'userAddressID',
	defaults: {
	  usersID:contactJSON.userID,
	  type:'',
	  address:'',
	  city:'',
	  state:'',
	  postcode:'',
	  sort:'1'
	},
	clear: function() {
	  this.destroy();
	}
	
  });
  
  App.AddressList = Backbone.Collection.extend({
	model: App.Address,
	url: '/contact_addresses'
  });
  
  App.Addresses = new App.AddressList;
  
  App.AddressView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-address-list').html()),
	events: {
	  "keypress input" : "updateOnEnter",
	  "click a.delete": "clear",
	  "click td.editable" : "edit",
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
//console.log(this)
	  App.Addresses.create({wait:true});
	  return false;
	},
	add: function(address) {
	  var view = new App.AddressView({model: address});
	  $('#address tbody').append(view.render().el);
	},
	addAll: function() {
	  App.Addresses.each(this.add);
	}
  });
  
  new App.AddressesView;
  App.Addresses.reset(addressJSON);
  
  
  /*
   * ======================================================================
   *  PHONE	 
   * ======================================================================
   */
  App.Phone = Backbone.Model.extend({
	idAttribute: 'userMetaID',
	defaults: {
	  usersID:contactJSON.userID,
	  schemaName:'phone',
	  metaKey:'',
	  metaValue:'',
	  sort:'1'
	},
	clear: function() {
	  this.destroy();
	}
  });
  
  App.PhoneList = Backbone.Collection.extend({
	model: App.Phone,
	url: '/contact_metas'
  });
  
  App.Phones = new App.PhoneList;
  
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
  
  new App.PhonesView;
  App.Phones.reset(phoneJSON);
  
});

