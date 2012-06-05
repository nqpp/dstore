var App = App || {};

$(function() {
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
	
	App.TaskList = Backbone.Collection.extend({
		model: App.Task,
		url: '/task'
	});
	
	App.Tasks = new App.TaskList;

	App.TaskView = Backbone.View.extend({
	
		tagName: 'tr',
		template: _.template($('#task-tpl').html()),
	
		events: {
			'change .completed': 'toggleCompleted',
			'click .trackTime': 'toggleTracking'
		},
	
		initialize: function() {
		
			this.model.bind('change', this.render, this);
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

	App.TasksView = Backbone.View.extend({
	
		el: $('.taskApp'),
		
		events: {
			'click .addTask': 'showAddForm',
			'submit .addTaskForm': 'create'
		},
	
		initialize: function() {

			App.Tasks.bind('reset', this.addAll, this);
			
			// WE BIND THE 'ADD' EVENT IN THE COLLECTION TO THE ADD FUNCTION IN THIS APP/VIEW, AS THIS GETS THE TEMPLATE RENDERING GOING FROM THE CREATE FUNCTION (WHICH FIRES AN 'ADD' EVENT IN THE COLLECTION)
			App.Tasks.bind('add', this.add, this);
		},
	
		add: function(task) {

			var view = new App.TaskView({model: task});
			$('.taskDay').append(view.render().el);
		},
	
		addAll: function() {
		
			App.Tasks.each(this.add);
		},
		
		showAddForm: function(ev) {

			// Instantiate a jQuery Tools toolip underneath the add button populated from a template. Shouldn't be too hard, should it?
			$(ev.target).tooltip();
		},
		
		create: function(ev) {

			var form = {};
			$.map($(ev.target).serializeArray(), function(n,i) {
				form[n['name']] = n['value'];
			});
			
			// THIS FUNCTION FIRES THREE EVENTS IN ONE:
			// (MODEL) ADD, (COLLECTION) ADD, AND (BACKBONE) SYNC
			// MAKE SURE YOU SET {wait: true} SO THAT YOU GET THE ID BACK FROM THE SERVER 
			// (MAKE SURE THE SERVER IS RETURNING A JSON OBJECT OF THE ENTITY)
			App.Tasks.create(form, {wait: true});

			return false;
		}
	});
});