var App = App || {};


$(function() {


  /*  
   *  --------------------------------------------------------------
   *  CLIENT CONTACTS
   *  --------------------------------------------------------------
   */
  
  App.Contact = Backbone.Model.extend({
	
	urlRoot: '/client_contacts',
	
	idAttribute: 'userID',
	
	defaults: {
	  firstName:'',
	  lastName:'',
	  email:'',
	  password:'',
	  confirm_password:''
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  
  App.ContactView = Backbone.View.extend({

	el: $("#contact tbody"),
	
	template: _.template($('#tpl-contact-entity').html()),
	
	events:{
	  "click td.handle" : "openEdit",
	  "keypress input" : "updateOnEnter"
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
	update: function(ev) {
	  var data = {};
	  if (!this.checkPw(ev.target)) return false;
	  
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data).save();
	  return true;
	},
	checkPw:function(obj) {
	  if (obj.name != 'password' && obj.name != 'confirm_password') return true;
	  
	  var pw = $('#contact input[type=password][name=password]')[0];
	  var cpw = $('#contact input[type=password][name=confirm_password]')[0];
	  
	  if (pw.value == '' || cpw.value == '') return false;
	  if (pw.value !== cpw.value) {
		alert ('passwords do not match.')
		return false;
	  }
	  
	  var data = {password:pw.value,confirm_password:cpw.value};
	  this.model.set(data);
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

  var contact = new App.Contact(contactJSON);
  var view = new App.ContactView({model:contact});
  view.render();
  

  /*  
   *  =========================================================================
   *  ADDRESS
   *  =========================================================================
   */
  App.Address = Backbone.Model.extend({
	idAttribute: 'userAddressID',
	defaults: {
	  usersID: contactJSON.userID,
	  type:'Postal',
	  address:'new address',
	  city:'new city',
	  state:'new state',
	  postcode:'new postcode',
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
	  "click a.delete": "clear",
	  "click td.handle" : "edit",
	  "click a.save": "update"
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
	update: function() {
	  var data = {};
	  data.type = this.$el.find('select[name=type]').val();
	  data.address = this.$el.find('input[name=address]').val();
	  data.city = this.$el.find('input[name=city]').val();
	  data.state = this.$el.find('select[name=state]').val();
	  data.postcode = this.$el.find('input[name=postcode]').val();
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
   *  --------------------------------------------------------------
   *  PHONE	 
   *  --------------------------------------------------------------
   */
  App.Phone = Backbone.Model.extend({
	idAttribute: 'userMetaID',
	defaults: {
	  usersID: contactJSON.userID,
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
	  "click a.delete": "clear",
	  "click td.handle" : "edit",
	  "click a.save": "update"
	},
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  this.$el.html(this.template(this.model.toJSON()));
	  return this;
	},
	update: function() {
	  var data = {};
	  data.metaValue = this.$el.find('input[name=metaValue]').val();
	  data.metaKey = this.$el.find('select[name=metaKey]').val();
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

//String.prototype.nl2br = function(is_xhtml) {
//  var str = this.valueOf();
//  if (str == '') return str;
////  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '' : '<br>';
//  var breakTag = '<br>';
//  return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
//}
