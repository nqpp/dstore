var App = App || {};

$(function() {

  /*
   * ======================================================================
   * SUPPLIERS
   * ======================================================================
   */  
  
  App.Supplier = Backbone.Model.extend({
	
	idAttribute: 'supplierID',
	
	defaults: {
	  name:'New supplier',
	  postcode:'0000'
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.SupplierList = Backbone.Collection.extend({
	model: App.Supplier,
	url: '/suppliers',
	
	comparator:function(model) {
	  return model.get('name');
	}
	
  });
  
  App.Suppliers = new App.SupplierList;
  
  App.SupplierView = Backbone.View.extend({
	tagName: 'tr',
	
	attributes: function() {
	  var data = {};
	  data["data-id"] = this.model.cid
	  return data;
	},
	
	template: _.template($('#tpl-list').html()),
	
	events: {
	},
	
	initialize: function() {
	  this.model.bind('change', this.render, this);
	},
	render: function() {
	  var data = this.model.toJSON();
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


  App.SuppliersView = Backbone.View.extend({
	el: $("#supplierApp"),
	events: {
//	  "click a#toglDialg": "toggleDialog",
	  "keypress #new-supplier": "createOnEnter"
	},
	initialize: function() {
	  
	  this.input = $('#new-supplier');
//	  this.dialog = $('#new-dialog');
	  
	  App.Suppliers.bind('reset', this.addAll, this);
	  App.Suppliers.bind('add', this.add, this);
	},
//	toggleDialog: function() {
//	  
//	  this.dialog.toggle();
//	  
//	  if (this.dialog.is(':visible')) {
//		this.input.focus();
//	  }
//	  
//	  return false;
//	},
	createOnEnter: function(ev) {
  
	  if (ev.keyCode != 13) return;
	  if (!this.input.val()) return;
	  
	  App.Suppliers.create({name:this.input.val()},{wait:true});
	  
	  this.input.val('').blur();
//	  this.toggleDialog();
//	  return false;
	},
	add: function(model) {
	  
	  var view = new App.SupplierView({model: model});
	  $('#suppliers tbody').append(view.render().el);
	},
	addAll: function() {
	  App.Suppliers.each(this.add);
	}

  });
  
  new App.SuppliersView;
  App.Suppliers.reset(suppliersJSON);
  

});