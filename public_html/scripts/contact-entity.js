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
	  "keypress input[class!=password]" : "updateOnEnter",
	  "keypress input.password" : "updatePasswordOnEnter",
	  "change input.password" : "updatePassword",
	  "change select" : "updateOnChange",
	  "blur textarea" : "updateOnBlur"
	},
	
	initialize:function() {
	  
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  
	  this.$el.html(this.template(this.model.toJSON()));
	  
	  this.pwInput = $('input[name=password]');
	  this.cpwInput = $('input[name=confirm_password]');
	  
	  return this;
	},
	updatePasswordOnEnter:function(ev) {
	  if (ev.keyCode != 13) return;
	  
	  this.updatePassword();
	},
	updatePassword:function() {

	  if ($.trim(this.pwInput.val()) == '') return;
	  if (this.pwInput.val() !== this.cpwInput.val()) return;

	  var data = {
		password:this.pwInput.val(),
		confirm_password:this.cpwInput.val()
	  };
	  
	  this.model.set(data).save();
	  
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
  
  
<<<<<<< .merge_file_uqhOQd
=======
  
  
  App.Location = Backbone.Model.extend({});
  
  App.LocationList = Backbone.Collection.extend({

	model:App.Location,
	url: '/locations?filtered'
	
  });

  
  App.LocationView = Backbone.View.extend({

	tagName: 'li',
	template: _.template($('#tpl-typeahead-list').html()),
	events: {
	  "click a":"locationSelected"
	},

	initialize: function(options) {
	  this.model.bind('change', this.render, this);
	},
	
	locationSelected: function() {

	  App.vent.trigger(this.options.namespace+":locationSelected",this.model);
	  
	},

	render: function() {
		
	  var data = this.model.toJSON();
	  this.$el.html(this.template(data));
	  return this;
	}

  });
	
  App.LocationsView = Backbone.View.extend({
	
el: $('#typeahead-13'),
	initialize: function() {
	  
	  _(this).bindAll('add');
	  this.collection.bind('add', this.add, this);
	  this.collection.bind('reset', this.addAll, this);
	  
	},
	
	add: function(model) {
	  var data = model.toJSON();
	  var view = new App.LocationView({model:model, namespace:this.options.namespace});
	  this.$el.append(view.render().el);
	  
	},	
	addAll: function() {
	  
	  this.$el.empty();
	  
	  if (this.collection.length == 0) {
		this.$el.hide();
		return;
	  }
	  
	  this.collection.each(this.add);
	  this.$el.show();
	}
>>>>>>> .merge_file_K8VDxV

  });
	
  
  /*
   * ======================================================================
   * ADDRESS
   * ======================================================================
   */
  
  App.vent = _.extend({},Backbone.Events);
  
  App.Address = Backbone.Model.extend({
	
	idAttribute: 'userAddressID',
	defaults: {
	  usersID:contactJSON.userID,
	  userAddressID:'',
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
  
<<<<<<< .merge_file_uqhOQd
=======
  App.AddressView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-address-list').html()),
	events: {
	  "keypress input" : "updateOnEnter",
	  "keyup input[name=city]" : "doTypeAhead",
	  "click a.delete": "clear",
	  "click td.editable" : "show",
	  "change select.change-update": "update",
	  "click a.save": "update"
	},
	
	initialize: function() {

	  this.recordLimit = 10;
	  this.model.bind('change', this.render, this);
	  
	  _.bindAll(this, "locationSelected");
	  App.vent.bind(this.cid+":locationSelected", this.locationSelected); // use name space

	  this.collection = new App.LocationList();
	  
	},
	
	render: function() {
	  this.$el.html(this.template(this.model.toJSON()));

	  this.setTypeAhead();
	  this.streetInput = this.$el.find('input[name=address]')[0];

	  return this;
	},
	
	setTypeAhead: function() {
	  
	  this.location = new App.LocationsView({
		el: '#typeahead-'+this.model.get('userAddressID'),
		collection:this.collection,
		namespace:this.cid
	  });
	},
	doTypeAhead: function(ev) {

	  var input = ev.currentTarget;
	  
	  if (ev.keyCode == 8 || ev.keyCode == 46) return; // backspace or delete
	  if (input.value.length < 2) return;

	  var data = {}
	  data.filter = input.value;
	  data.limit = this.recordLimit;
	  this.collection.fetch({data:data})	  

	},
	locationSelected: function(model) {
	  var data = model.toJSON();
	  
	  data.city = data.suburb;
	  data.locationsID = data.locationID;
	  data.address = this.streetInput.value;
	  
	  this.model.set(data).save();
	  this.collection.reset();
	  this.hide();

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
	  this.hide();
	},
	show:function() {
	  this.$el.find('.edit').show();
	  this.$el.find('.display').hide();
	},
	hide: function() {
	  this.$el.find('.edit').hide();
	  this.$el.find('.display').show();
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
	add: function(model) {
	  var view = new App.AddressView({model: model,vent:App.vent});
	  $('#address tbody').append(view.render().el);
	  view.setTypeAhead();
	},
	addAll: function() {
	  App.Addresses.each(this.add);
	}
  });
>>>>>>> .merge_file_K8VDxV
  
  new App.AddressesView;
  App.Addresses.reset(addressJSON);
  
  
  /*
   * ======================================================================
   *  PHONES
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
  
  
  new App.PhonesView;
  App.Phones.reset(phoneJSON);
  
  
  /*
   * ======================================================================
   *  EMPLOYMENT DATAS
   * ======================================================================
   */
  
  App.EmploymentData = Backbone.Model.extend({
	idAttribute: 'userMetaID',
	defaults: {
	  usersID:contactJSON.userID,
	  schemaName:'employmentdata',
	  metaKey:'',
	  metaValue:'',
	  sort:'1'
	},
	clear: function() {
	  this.destroy();
	}
  });
  
  App.EmploymentDataList = Backbone.Collection.extend({
	model: App.EmploymentData,
	url: '/contact_metas'
  });
  
  App.EmploymentDatas = new App.EmploymentDataList;
  
  
  new App.EmploymentDatasView;
  App.EmploymentDatas.reset(employmentDataJSON);
  
});
