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
	  entityName:'Company name'
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.SupplierAddress = Backbone.Model.extend({
	
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
	tagName: 'div',
	
	attributes: {
	  class: "accordion-group"		
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
	  "keypress #new-supplier": "createOnEnter"
	},
	initialize: function() {
	  
	  this.input = $('#new-supplier');
	  
	  App.Suppliers.bind('reset', this.addAll, this);
	  App.Suppliers.bind('add', this.add, this);
	},
	createOnEnter: function(ev) {
  
	  if (ev.keyCode != 13) return;
	  if (!this.input.val()) return;
	  
	  App.Suppliers.create({name:this.input.val()},{wait:true});
	  
	  this.input.val('').blur();
	},
	add: function(model) {
	  
	  var view = new App.SupplierView({model: model});
	  $('#suppliersAccordion').append(view.render().el);
	},
	addAll: function() {
	  App.Suppliers.each(this.add);
	}

  });
  
  new App.SuppliersView;
  App.Suppliers.reset(suppliersJSON);
  

});