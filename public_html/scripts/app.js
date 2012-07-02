var App = App || {}

App.CurrentUser = Backbone.Model.extend({
  urlRoot: '/users?current',
  idAttribute: 'userID'
  
});

$(function() {
  App.User = new App.CurrentUser;
  App.User.fetch();

})