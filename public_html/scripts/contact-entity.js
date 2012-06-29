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
