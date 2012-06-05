var App = App || {}

$(function() {
  
  /*
   * ======================================================================
   * CLIENT
   * ======================================================================
   */
  
  App.Client = Backbone.Model.extend({
	
	urlRoot: '/clients',
	
	idAttribute: 'clientID',
	
	defaults: {
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  
  App.ClientView = Backbone.View.extend({

	el: $("#client"),
	
	template: _.template($('#tpl-entity').html()),
	
	events:{
	  "click .client-delete" : "clear",
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

  var client = new App.Client(clientJSON);
  var view = new App.ClientView({model:client});
  view.render();
  
  
  
  /*
   * ======================================================================
   * CONTACTS
   * ======================================================================
   */
  
  App.Contact = Backbone.Model.extend({
	idAttribute: 'clientContactID',
	defaults: {
	  clientsID: clientJSON.clientID,
	  firstName:'',
	  lastName:'',
	  email:'',
	  contactPhones:[]
	},
	initialize: function() {
	  var contactPhones = contactPhonesJSON[this.get('usersID')] || [];
	  var coll = new Backbone.Collection(contactPhones);
	  this.set('contactPhones', coll);
	},
	clear: function() {
	  this.destroy();
	}
	
  });
  
  App.ContactList = Backbone.Collection.extend({
	model: App.Contact,
	url: '/client_contacts',
	
	comparator:function(model1,model2) {
	  var l1 = model1.get('lastName');
	  var l2 = model2.get('lastName');
	  var f1 = model1.get('firstName');
	  var f2 = model2.get('firstName');
	  
	  if (l1 > l2) return 1;
	  else if (l1 < l2) return -1;
	  
	  if (f1 > f2) return 1;
	  else if (f1 < f2) return -1;
	  else return 0;
	}
	
  });
  
  App.Contacts = new App.ContactList;
  
  App.ContactView = Backbone.View.extend({
	tagName: 'div',
	template: _.template($('#tpl-contact').html()),
	events: {
	  "click .contact-delete": "clear"
	},
	attributes: {
	  class: 'accordion-group'
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  var data = this.model.toJSON();
	  data['id'] = this.model.cid;
	  this.$el.html(this.template(data));
	  return this;
	},
	clear: function() {
	  if (confirm('Really Delete?')) {
		this.model.clear();
		this.remove();
	  }
	}
	
  });
  
  App.ContactsView = Backbone.View.extend({
	el: $("#contacts"),
	events: {
	  "keypress #new-contact": "createOnEnter"
	},
	initialize: function() {
	  
	  this.input = $('#new-contact');
	  
	  App.Contacts.bind('reset', this.addAll, this);
	  App.Contacts.bind('add', this.add, this);
	},
	createOnEnter: function(ev) {
  
	  if (ev.keyCode != 13) return;
	  if (!this.input.val()) return;
	  
	  var arr = this.input.val().split(' ');
	  var firstName = arr.shift();
	  var lastName = arr.join(' ');
	  var data = {"firstName":firstName, "lastName": lastName}
//	  data[this.input.attr('name')] = this.input.val()
	  App.Contacts.create(data,{wait:true});
	  
	  this.input.val('').blur();
	},
	add: function(model) {
	  var view = new App.ContactView({model: model});
//console.log(view);
	  $('#contactsAccordion').append(view.render().el);
//	  var phView = new App.ContactPhonesView;
	},
//	addContactPhones:function(model) {
//	  
//	},
	addAll: function() {
	  App.Contacts.each(this.add);
	}
	
  });
  
  new App.ContactsView;
  App.Contacts.reset(contactsJSON);



  /*
   * ======================================================================
   * CONTACT PHONE META
   * ======================================================================
   */
  
  App.ContactPhone = Backbone.Model.extend({
	defaults: {
	  usersID: '',
	  schemaName:'phone',
	  metaKey:'',
	  metaValue:'',
	  sort:1
	},
	clear: function() {
	  this.destroy();
	}
	
  });
  
  App.ContactPhoneList = Backbone.Collection.extend({
	model: App.ContactPhone,
	url: '/contact_metas'
	
  });
  
  App.ContactPhones = new App.ContactPhoneList;

  App.ContactPhoneView = Backbone.View.extend({
	tagName: 'div',
	attributes: {
	  class:'row'
	},
	template: _.template($('#tpl-contact-phonelist').html()),
	events: {
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
	clear: function() {
	}	
  });
  
  App.ContactPhonesView = Backbone.View.extend({
//	el: $("#address"),
	events: {
	},
	initialize: function() {
	  App.ContactPhones.bind('reset', this.addAll, this);
	  App.ContactPhones.bind('add', this.add, this);
	},
	add: function(model) {
	  var view = new App.ContactPhoneView({model: model});
	  this.el.append(view.render().el);
	},
	addAll: function() {
	  App.ContactPhones.each(this.add);
	}
	
  });

  /*
   * ======================================================================
   * ADDRESS
   * ======================================================================
   */
  
  App.Address = Backbone.Model.extend({
	
	idAttribute: 'clientAddressID',
	defaults: {
	  clientsID:clientJSON.clientID,
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
	url: '/client_addresses'
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
	idAttribute: 'clientMetaID',
	defaults: {
	  clientsID:clientJSON.clientID,
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
	url: '/client_metas'
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
