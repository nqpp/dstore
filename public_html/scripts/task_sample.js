var App = App || {};

// The model & view, basically the same, sans the 'superview' and collection.
// Think of it this way: 'superviews' are bound to collections, while views are bound to models.
$(function() {
	
	// Model
	App.Task = Backbone.Model.extend({
		
		urlRoot: '/task',
	
		defaults: function() {
		
			return {
				title: 'New Task',
				client: false,
				completed: false,
				tracking: false,
				time: false
			};
		},
	
		toggleComplete: function() {
		
			this.save({completed: !this.get('completed')});
		},
	
		toggleTrack: function() {
		
			this.save({tracking: !this.get('tracking')});
		}
	});
	
	// View
	App.TaskView = Backbone.View.extend({
	
//		tagName: 'tr', // We used to use this to create a new element on render, which was then parsed to the superview...
		el: $('.selector'), // ...so we use this line out of the superview, to bind the view to an existing element.
		template: _.template($('#task-tpl').html()),
	
		events: {
			'change .completed': 'toggleCompleted',
			'click .trackTime': 'toggleTracking'
		},
	
		initialize: function() {
		
			this.model.bind('change', this.render, this); // This is pretty funky, see: http://documentcloud.github.com/underscore/#bind
		},
	
		render: function() {
			
			this.$el.html(this.template(this.model.toJSON()));
			this.$el.toggleClass('completed', this.model.get('completed'));
			this.$el.toggleClass('tracking', this.model.get('tracking'));
			return this;
		},
	
		toggleTracking: function(ev) {
			
			this.model.toggleTrack();
			return false;
		},
	
		toggleCompleted: function() {

			this.model.toggleComplete();
		}
	});
});

// Instantiation code...
$(function() {
  
	
	// There's two ways of doing this, depending on how you want to fire the 'change' event to get the render happening:
	
	var task = new App.Task({"id":"51","title":"Social Icons","client":"Horizon Financial","completed":false,"tracking":false,"time":"00:36"});
	var view = new App.View({model: task});
	view.render(); // We call this as the Task's change event fired before the view was created.
	
	// OR:
	
	var task = new App.Task();
	var view = new App.View({model: task});
	task.initialize({"id":"51","title":"Social Icons","client":"Horizon Financial","completed":false,"tracking":false,"time":"00:36"}); // We manually fire the initialize function again, populating it with data, hence firing the change event.
}
);