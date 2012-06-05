
function makeClass(){
  return function(args){
    if ( this instanceof arguments.callee ) {
      if ( typeof this.init == "function" )
        this.init.apply( this, args.callee ? args : arguments );
    } else
      return new arguments.callee( arguments );
  };
}

$(function(){
  $('.jsHide').hide();
})

var App = {};

App.hostName = window.location.protocol + '//' + window.location.hostname + '/';
App.monthsShort = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

/**
 * Adds all missing properties from second obj to first obj
 */ 
App.extend = function(first, second){
  for (var prop in second){
	first[prop] = second[prop];
  }
};

var Model = App.Model = makeClass();

Model.prototype = {
  
  debug: false,
  url: false,
  entityID: false,
  method:false,
  data: {},
  serverMethod:false,
  serverResponse: false,
  resultNode: false,
  callback: false,
  callbackArg: false,
  callbackUseResult: false,
  util: {},

  init: function(o){
	App.extend(this, o);
	this.util = new App.Util();
	
  },
  
  set: function(o){
	App.extend(this, o);
	
  },
  
  reset: function(){
	for (var prop in this) {
	  this[prop] = false;
	}

  },
  
  exec: function() {
	this[this.method]();
	
	this.doAjax();
	
  },
  
  checkCallback: function(result) {
	
	if (!this.callback) return;
	
	if (this.callbackArg == 'this') this.callbackArg = this;
	
	if (this.callbackUseResult) {
	  this.callback(this.serverResponse, this.callbackArg);
	}
	else {
	  this.callback(this.callbackArg);	  
	}
	
  },
  
  doAjax: function () {
	
	if (this.serverMethod == 'error') return;
	  
	var _url, self, util;
	_url = this.util.addSlash(App.hostName) + this.util.addSlash(this.url)
	_url += this.entityID ? this.entityID : '';
	self = this;
	util = new App.Util();
	
	$.ajax({
	  url: _url,
	  type: self.serverMethod,
	  data: self.data,
	  dataType: "json",
	  
	  success:function(resultJSON) {
		resultJSON.serverMethod = self.serverMethod;
		self.serverResponse = resultJSON;
		
		if (resultJSON.status == '302') {
		  util.jsReload();
		  return;
		}
		else if (resultJSON.status == 'error') {
		  util.showFAIL(self.resultNode);
		  console.log(resultJSON.message);
		  return;
		}
		
		util.showOK(self.resultNode);
		
		if (self.method =='post') {
		  self.entityID = resultJSON.data.id;
		}
		
		self.checkCallback(resultJSON);
		
	  },
	  
	  error: function() {},
	  
	  complete: function() {}
	  
	});

  },
  
  // add new
  post: function() {
	this.serverMethod = 'POST';
	
  },
  
  // get one or fetch many
  get: function(id){
	this.serverMethod = 'GET';
	
  },
  
  // update one
  put: function(id){
	this.serverMethod = 'PUT';
	
  },
  
  // delete one
  del: function(id){
	this.serverMethod = 'DELETE';
	
  },
  
  error: function() {
	this.serverMethod = 'error';
	console.log('there was an error. server call was not made');
	
  }
  
}


/*
 * ===========================================================================
 * FORM OBJECT
 * ===========================================================================
 */
var FormObj = App.FormObj = makeClass();

FormObj.prototype = {
  
  m: {},
  formNode: false,
  event: false,
  modelData: {},
  modelParam: {},
//  hooks: {
//	pre:false,
//	post:false
//  },
  
  init: function(opt) {
	  
	App.extend(this,opt);
	this[this.event](); // bind to an event
	
  },
  
  submit: function() {
	var self = this;
	
//	$(self.formNode).unbind('submit');
	$(self.formNode).submit(function() {
	  var serial = $(this).serialize();
	  var json = self.unserialize(serial);
	  self.processEvent(json);
	  
	  return false;
	});

	
  },
  
  processEvent: function(data) {
	
	this.modelParam.data = data;
	this.m = new Model(this.modelParam);
//console.log(this); 
	this.m.exec();
	
  },
  
  unserialize: function(serial) {
	
	var arr1, arr2, obj = {};
	arr1 = serial.split('&');
	
	for (var i = 0; i < arr1.length; i++) {
	  arr2 = arr1[i].split('=');
	  obj[arr2[0]] = arr2[1];
	}

	return obj;
	
  }
  
}

/*
 * ===========================================================================
 * FIELD OBJECT
 * ===========================================================================
 */
var FieldObj = App.FieldObj = makeClass();

FieldObj.prototype = {
  
  m: false,
  node:false,
  event: false,
  modelData: {},
  modelParam: {},
  hooks: {
	pre:false,
	post:false
  },
  
  
  init: function(opt) {
	  
	App.extend(this,opt);
	this[this.event](); // bind to an event
	
  },
  
  setHooks: function(opt) {
	this.hooks = opt;
	
  },
  
  preHook: function() {
	
	if (!this.hooks.pre) return;
	
	var funcCall = this.hooks.pre;
	funcCall(this);
	
  },
  
  processEvent: function() {
	var m;
	this.getData();
	m = new Model(this.modelParam);
	m.set({data:this.modelData, resultNode:this.node});
	m.exec();
	this.m = m;
	
  },
  
  postHook: function() {

	if (!this.hooks.post) return;
	
	var funcCall = this.hooks.post;
	funcCall(this);	
	
  },
  
  postProcess: function() {
	
	
  },
  
  change: function() {
	var self = this;

	$(self.node).unbind('change');
	$(self.node).change(function() {
	  
	  self.preHook();
	  self.processEvent();
	  self.postHook();

	});

  },

  blur: function() {
	var self = this;

	$(self.node).unbind('blur');
	$(self.node).blur(function() {

	  self.preHook();
	  self.processEvent();
	  self.postHook();

	});

  },

  focus: function() {
	var self = this;

	$(self.node).unbind('focus');
	$(self.node).focus(function() {

	  self.preHook();
	  self.processEvent();
	  self.postHook();

	});

  },

  getData: function() {

	switch (this.node.type) {
	  case 'text':
	  case 'textarea':
	  case 'radio':
	  case 'select':
		this.modelData[this.node.name] = this.node.value;
		break;
	  case 'checkbox':
		var value = this.node.checked ? this.node.value : '0';
		this.modelData[this.node.name] = value;
		break;
	}
	
  }

};


/*
 * ===========================================================================
 * UTIL
 * ===========================================================================
 */
var Util = App.Util = makeClass();

Util.prototype = {
  
  jsReload: function(href) {
	if (href) {
	  window.location.replace(App.hostName + href);	
	}
	else {
	  window.location.reload();
	}
	
  },
  
  showFocus: function() {
	$(this.node).addClass('focus');
	
  },
  
  showFAIL: function(node) {
	$(node).removeClass('focus');
	$(node).addClass('fail').delay('2000').queue(function(){
	  $(this).removeClass('fail');
	  $(this).dequeue();
	});
	
  },
  
  showOK: function(node) {
	$(node).removeClass('focus');
	$(node).addClass('ok').delay('1000').queue(function(){
	  $(this).removeClass('ok');
	  $(this).dequeue();
	});
	
  },
  
  restripeNodes: function(nodes) {
	var count, rowClass;
	count = 1;

	$(nodes).each(function(){
	  rowClass = count%2?'odd':'even';
	  $(this).removeClass('odd even').addClass(rowClass);
	  count++;
	});

  },
  
  toglDialg: function() {
  
	$('.toglDialg').click(function() {
	  if ( ! $(this).siblings('.dialog:first').size()) return true;

	  $(this).siblings('.dialog:first').toggle();
	  $(this).toggleClass('active');

	  return false;
	});
  },
  
  addSlash: function(str) {
	
	str += str.charAt(str.len-1) == '/' ? '' : '/';
	return str;
	
  }
  
}


/* 
 * ===========================================================================
 * IMAGE HANDLER
 * ===========================================================================
 */
var ImageHandler = App.ImageHandler = makeClass();

ImageHandler.prototype = {
  
  code: 0,
  width: 0,
  height: 0,
  filename: 'no_image.png',
  directory: 'no_image',
  image: {},
  imageListElem:'',
  tplTag: 'li',
  tpl: false,
  m: {},
  modelData: {},
  modelParam: {},
  
  init: function(o) {
	App.extend(this,o);
  },
  
  set: function(o) {
	App.extend(this,o);
  },
  
  append: function(filename) {
	this.filename = filename;
	this.directory = this.filename.split('.').slice(0,-1).join('.');
	var src = ['/images',this.directory,this.code,this.width,this.height,this.filename].join('/');
	$('<img>',{src:src}).appendTo('#' + this.imageListElem).wrap('<' + this.tplTag + '>').parent('li').attr('id',this.m.entityID);
	
	this.events();

  },
  
  write: function(o) {
	App.extend(this,o);
	
	this.m = new Model(this.modelParam);
	this.m.exec();
	
  },
  
  events: function() {
	var self = this;
	
	$('#' + this.imageListElem).find('img').each(function() {
	  $(this).unbind('dblclick');
	  $(this).dblclick(function() {
		self.remove(this.parentNode);
	  });
	});
	
  },
  
  remove: function(node) {
	
	if (!confirm('Really delete?')) return;
	
	node.style.display = 'none';
	node.parentNode.removeChild(node);

	this.modelParam.entityID = node.id;
	this.modelParam.method = 'del';
	this.m = new Model(this.modelParam);
	this.m.exec();

  },
  
  sort: function() {
	var self = this;
	
	$('#' + this.imageListElem).dragsort("destroy");
	$('#' + this.imageListElem).dragsort({
	  dragEnd:function() {
		var sort = 1;
		var modelParam = {url:self.modelParam.url,method:'put',data:{sort:0}};
		var m = new Model(modelParam)
		
		$(this).parent('ul').children().each(function() {
		  m.entityID = this.id;
		  m.data.sort = sort;
		  m.exec();
		  sort++;
		});
	  
	  }
	});
  }
  
};




