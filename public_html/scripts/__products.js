var productID = false;

//var imgData = new Array();
//var imgWidth;
//var imgHeight;
//var imgSizeCode;

//var fObj = [];

function edit() {
//  productEdit();
//  productPrice();
//  productImage();
//  createUploader();
//console.log(fObj);
}

function productEdit() {
  var fields, modelParam;

  fields = [{name:'name',elem:'input',event:'change'},{name:'description',elem:'textarea',event:'change'}];
  modelParam = {
	url: '/products/a',
	entityID: productID,
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

function productPrice() {
  var event, modelParam, modelData;
  event = 'change';
  modelParam = {
	url:'/product_metas/a',
	callback:alterPriceRowState,
	callbackArg:'this',
	callbackUseResult:false
  }
  modelData = {productsID:productID, schemaName:'price'}
  
  $('table#prices tbody tr').each(function() {
	var j,o,e;
	j = $(this).find('td input')[0];
	e = event;
	o = new FieldObj({node:j, modelParam:modelParam, modelData:modelData, event:event});
	o.setHooks({pre:checkPriceRowState});
	
//	fObj.push(o);
	
 });
  
}

/*
 *	determine server call type by existence (or not) of row id
 *	row id also becomes model entity id
 */
function checkPriceRowState(fieldObj) {
  var tr;
  tr = $(fieldObj.node).parent().parent()[0];
  fieldObj.modelData.metaKey = $(tr).children('td.metaKey').html();
  
  if (tr.id) {
	fieldObj.modelParam.entityID = tr.id;

	if ($.trim(fieldObj.node.value) == '') {
	  fieldObj.modelParam.method = 'del';
	  return;
	}
	
	fieldObj.modelParam.method = 'put';
  }
  else {
	if ($.trim(fieldObj.node.value) == '') {
	  fieldObj.modelParam.method = 'error';
	  return;
	}
	fieldObj.modelParam.method = 'post';
  }
  
}

function alterPriceRowState(modelObj) {
  var tr;
  tr = $(modelObj.resultNode).parent().parent()[0];
  
  if (modelObj.serverMethod == "POST") {
	tr.id = modelObj.entityID;
  }
  
  if (modelObj.serverMethod == "DELETE") {
	tr.id = "";
  }
  
}

function productImage() {
  
  var modelData, modelParam;
  modelData = {'productsID':productID,'schemaName':'image'};
  modelParam = {data:modelData,url:'/product_metas/a',debug: false}
  
  iH.set({modelParam:modelParam});
  iH.events();
  iH.sort();
  
  var uploader = new qq.FileUploader({
      element: document.getElementById('image-uploader'),
      action: '/fileUploader.php?dir=images',
      debug: false,
	  onComplete:function(id, filename, responseJSON){
console.log(responseJSON); return;		
		modelData.metaKey = responseJSON.filename;
		modelParam.method = 'post';
		modelParam.callback = productImage_post,
		modelParam.callbackArg = 'this',
		modelParam.data = modelData,
		iH.set({modelParam:modelParam});
		iH.write();

	  }
  });
  
}

/*
 * called after the image details have been written to db
 */
function productImage_post(obj) {
  
  iH.set({m:obj});
  iH.append(obj.data.metaKey);

}



$(function(){
  if ($('#edit').size()) edit();
});

