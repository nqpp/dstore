
function productEdit() {

  var fields = [{name:'name',elem:'input',event:'change'},{name:'description',elem:'textarea',event:'change'}];
  var data = {
	url: '/products/a',
	method: 'put'	
  }
  
  for (var f in fields) {
	var j,o,e;

	j = $(fields[f].elem + '[name=' + fields[f].name + ']')[0];
	e = fields[f].event;
	o = new FieldObj;
	o.initialize(j,data,e);
//console.log(o);
  
  }
}

var FieldObj = function(){}

FieldObj.prototype = {
  
  node:false,
  m: false,
  
  initialize: function(node,opt,ev) {
	this.node = node;
	this.m = new App.Model();
	this.m.set(opt);
//	this[ev](this.m);
	this[ev]();
  },
  
  bindEvents: function() {
	
  },
  
  change: function() {
	var m = this.m;
	
	$(this.node).unbind('change');
	$(this.node).change(function() {
console.log(this);
	  
	});

  },
  
  blur: function() {
console.log('blur event');
  },
  
  focus: function() {
console.log('focus event');
  }
  
};













$(function(){
  if ($('#edit').size()) productEdit();
});

