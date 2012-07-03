var App = App || {}

App.vent = _.extend({},Backbone.Events);


//App.userID = null;

//App.CurrentUser = Backbone.Model.extend({
//  urlRoot: '/users?current',
//  idAttribute: 'userID',
//  updateVars: function() {
//	App.userID = this.get('userID');
//  }
//});

//$(function() {
//  App.User = new App.CurrentUser;
//  App.User.on("success","updateVars");
//  App.User.fetch();
//
//})