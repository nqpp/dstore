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
	  "click .handle" : "openEdit",
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
		if (this.update(ev)) {
		  this.closeEdit(ev);
		}
		
	  }
	},
	updateOnChange:function(ev) {
	  if (this.update(ev)) {
		this.closeEdit(ev);
	  }
	  
	},
	updateOnBlur:function(ev) {
	  if (this.update(ev)) {
		this.closeEdit(ev);
	  }
	  
	},
	update: function(ev) {
	  var data = {};
	  
	  data[ev.target.name] = ev.target.value;
	  this.model.set(data).save();
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

  var supplier = new App.Supplier(supplierJSON);
  var view = new App.SupplierView({model:supplier});
  view.render();
  
  
  /*
   * ======================================================================
   * SUPPLIER FREIGHT
   * ======================================================================
   */
  
  App.Freight = Backbone.Model.extend({
	
	idAttribute: 'supplierFreightID',
	
	defaults: {
	},
	
	clear:function() {
	  this.destroy();
	}
	
  });
  
  App.FreightList = Backbone.Collection.extend({
	model: App.Freight,
	url: '/supplier_freights',
	
	comparator:function(model) {
	  return model.get('code');
	}	
  });
  
  App.Freights = new App.FreightList;
  
  App.FreightView = Backbone.View.extend({
	tagName: 'tr',
	template: _.template($('#tpl-supplier_freights').html()),
	events: {
	  "click td.editable" : "edit",
	  "click a.save": "update"
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
	}
  });

  App.FreightsView = Backbone.View.extend({
	el: $("#freight"),
	events: {
	},
	initialize: function() {
	  App.Freights.bind('reset', this.addAll, this);
	  App.Freights.bind('add', this.add, this);
	},
	add: function(model) {
	  var view = new App.FreightView({model: model});
	  $('#freight tbody').append(view.render().el);
	},
	addAll: function() {
	  App.Freights.each(this.add);
	}
  });
  
  new App.FreightsView;
  App.Freights.reset(supplierFreightsJSON);


});