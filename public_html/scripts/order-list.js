var App = App || {}

$(function() {
  
  /*
   * ======================================================================
   * ORDER
   * ======================================================================
   */  
  
  App.Order = Backbone.Model.extend({
	
  });
  
  App.OrderList = Backbone.Collection.extend({
	model: App.Order,
	
	comparator:function(model) {
//	  return model.get('sort');
	}
	
  });
  
  App.Orders = new App.OrderList;
  
  
});

