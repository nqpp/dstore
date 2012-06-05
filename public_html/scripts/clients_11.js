
function list() {
  clientNew();
}

function clientNew() {
  
  $('a.toglDialg').click(function() {
	
	if ( ! $(this).siblings('.dialog:first').size()) return true;
	
	$(this).siblings('.dialog:first').toggle();
	$(this).toggleClass('active');
	
	return false;
  });

  var forms, modelParam;
  
  modelParam = {
	url: 'clients',
	method: 'post',
	callback: clientNew_post,
	callbackArg: 'this'
  }
  
  
  forms = [
	{name:'new-client',event:'submit'}
  ];
  
  for (var f in forms) {
	var j,o,e;
	j = $('form[name=' + forms[f].name + ']')[0];
	e = forms[f].event;
	o = new FormObj({formNode:j, modelParam:modelParam, event:e});

  }
}

function clientNew_post(obj) {
  
  if (typeof(obj.serverResponse) == 'undefined') {
	console.log(obj);
	return;
  }
  
  var util = new Util();
  util.jsReload(obj.serverResponse.redirect);
  
}



function edit() {
  clientEdit();
  
  
//  primaryContact();
//  createUploader();
}

function clientEdit() {
  var fields, modelParam;

  fields = [
	{name:'name',elem:'input',event:'change'},
	{name:'billingName',elem:'input',event:'change'},
	{name:'abn',elem:'input',event:'change'}
  ];
  modelParam = {
	url: '/clients/',
	entityID: clientID,
	method: 'put'
  }
  
  for (var f in fields) {
	var j,o,e;
	j = $(fields[f].elem + '[name=' + fields[f].name + ']')[0];
	e = fields[f].event;
	o = new FieldObj({node:j, modelParam:modelParam, event:e});
//	fObj.push(o);
  }
  
}




function addMeta() {
  var modelParam
  
  $('a.addContact').click(function(){
	addMeta(this.id);
  });
  
}

function addContact() {

  $('a.addContact').click(function(){
	addMeta(this.id);
  });
  
function addMeta(c) {
  
  var url,data,n,s,i;
  url = hostName + '/metas/a/add';
  data = {"schemaName":"masons","schemaID":masonID,"metaKey":c.toLowerCase()}
  
  $.post(url,data,function(result){
	  if (result.status == 'error') {
		console.log(result.message);
		return;
	  }
	  // clone the tr
	  n = $('div#cloneNodes table.contactTable_node tr').clone();
	  n.attr('id',result.metaID);
	  // clone appropriate select and add to tr
	  s = $('div#cloneNodes select.contact' + c + 'Types').clone();
	  s.appendTo(n.children('th:first'));
	  // clone input field or textarea depending on requirement
	  if (c == 'Email' || c == 'Phone') {
		i = $('div#cloneNodes input.data').clone();
	  }
	  else if (c == 'Address') {
		i = $('div#cloneNodes textarea.data').clone();		
	  }
	  i.attr('name',s.val());
	  i.appendTo(n.children('td:first'));
	  // add tr to appropriate table 
	  n.prependTo('table#contact' + c + 'List tbody');
	  
	  changeContact();
	  deleteContact();
	  restripeNodes('table#contact' + c + 'List tbody tr');
	},
	"json"
  );

}

}



$(function(){
  if ($('#list').size()) list();
  if ($('#edit').size()) edit();
});
