var App = App || {};

$(function(){
  
  /*
   * ======================================================================
   * SUPPLIER
   * ======================================================================
   */
  
  App.Supplier = Backbone.Model.extend({
	
	urlRoot: '/suppliers',
	
	idAttribute: 'supplierID',
	
	defaults: {
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  
  App.SupplierView = Backbone.View.extend({

	el: $("#supplier"),
	
	template: _.template($('#tpl-entity').html()),
	
	events:{
//	  "click .handle" : "openEdit",
	  "keypress input" : "updateOnEnter",
	  "change input" : "updateOnChange",
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
		this.update(ev)
	  }
	},
	updateOnChange:function(ev) {
	  this.update(ev)
	},
	updateOnBlur:function(ev) {
	  this.update(ev)
	},
	update: function(ev) {
	  var data = {};
	  
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data).save();
	  return true;
	}
//	openEdit: function(ev) {
//	  $(ev.currentTarget).removeClass('handle');
//	  $(ev.currentTarget).children('.edit').show().children('input').focus();
//	  $(ev.currentTarget).children('.display').hide();
//	},
//	
//	closeEdit:function(ev) {
//	  $(ev.target).parent().hide();
//	  $(ev.target).parent().siblings('.display').show();
//	  $(ev.target).parent().parent().addClass('handle');
//	}

  });

  var supplier = new App.Supplier(supplierJSON);
  var view = new App.SupplierView({model:supplier});
  view.render();


/*
 * ==========================================================================
 * PHONES
 * ==========================================================================
 */
  
  App.Phone = Backbone.Model.extend({
	idAttribute: 'supplierMetaID',
	defaults: {
	  suppliersID:supplierJSON.supplierID,
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
	url: '/supplier_metas'
  });
  
  App.Phones = new App.PhoneList;

  new App.PhonesView;
  App.Phones.reset(phoneJSON);



/*
 * ==========================================================================
 * PHONES
 * ==========================================================================
 */
  
  App.Address = Backbone.Model.extend({
	
	idAttribute: 'supplierAddressID',
	defaults: {
	  supplierAddressID:'',
	  suppliersID:supplierJSON.supplierID,
	  type:'',
	  locationsID:'',
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
	url: '/supplier_addresses'
  });
  
  App.Addresses = new App.AddressList;
  
  new App.AddressesView;
  App.Addresses.reset(addressJSON);
  

});