var quoteID;
var metaJSON;

function list() {
  listFilter();

}

function add() {

  selectRedirect();
}

function edit() {
//console.log(window.location)
  selectRedirect();
  editQuote();
  addNewItem();
  addNewQty();
  deleteQ();
  collapseQ();
  actionInit();
  itemEditInit();

//  qEmailsEdit();
}

function selectRedirect() {
  
  $('select.redirect').unbind('change');
  $('select.redirect').change(function(){
    if (isNaN(this.value)) {
      var u = hostName + this.value;
      window.location.href = u;
      return;
    }
  });
}

// add new quote item
function addNewItem() {
  $('a#addNew').click(function(){
    // ajax call to get new id
    var u = hostName + '/quote_items/a/add?quoteID=' + quoteID;
    $.get(u, newNode,"json");
  });
}

// add a new quote item to the page
function newNode(result) {
  var n;
  // clone node
  n = $('#table_node > table').clone();
    
  if (result.status == '302') {
	jsReload();
  }
  else if (result.status == 'error') {
    n.css({"border":"1px solid #F00"});
  }
  else {
    // replace placeholder with new ID
    n.attr('id','qItem-' + result.id);
    // find child elements with class of append-id and append nodeId to their id
    var a = n.find('.append-id');
    a.attr('id', $(a).attr('id') + result.id).removeClass('append-id');
    // find child elements with class of replace-value and make their value = nodeId
    n.find('.replace-value').val(result.id).removeClass('replace-value');
  }

  // add node to page
  n.prependTo('#qData');
  // reinitialise buttons
  deleteQ();
  addNewQty();
  collapseQ();
  actionReinit();

  $('#qData table:first').each(function(){
    itemEdit(this);
  });
}

// add new qty point to an item
function addNewQty() {

  $('a.addQtyItem').unbind('click');
  $('a.addQtyItem').click(function(){
    
    var url,parentID,data;

    parentID = $(this).parents('table.qTable').attr('id').split('-').pop();
    url = hostName + '/quote_items/a/addChild?quoteID=' + quoteID + '&quoteItemID=' + parentID;
    data = {"parentID":parentID}

    $.post(url,data,newQtyNode,"json");

  });

}

// add new item quantity node to existing item
function newQtyNode(result) {
//console.log(result);return;
  var n,a;

  n = $('#qty_node > table').clone();

  if (result.status == '302') {
	jsReload();
  }
  else if (result.status == 'error') {
    n.css({"border":"1px solid #F00"});
  }
  else {
    // replace placeholder with new ID
    n.attr('id','quoteQty-' + result.id);
    // find child elements with class of append-id and append nodeId to their id
    a = n.find('.append-id');
    a.attr('id', $(a).attr('id') + result.id).removeClass('append-id');
    // find child elements with class of replace-value and make their value = nodeId
    n.find('.replace-value').val(result.id).removeClass('replace-value');
  }

  // add node to page
  n.appendTo('table#qItem-' + result.parentID + ' td.quantities');
  // reinitialise buttons
  deleteQ();
  collapseQ();
  actionReinit();

  $('table#qItem-' + result.parentID).each(function(){
    itemEdit(this);
  });

}

function deleteQ() {
  
  $('a.deleteQ').unbind('click'); // removes all bound click listeners
  $('a.deleteQ').click(function(){
    
    var delID, url, self, parentTable, parentID;

    if (!confirm("Really Delete?")) return;
    
    self = this;
    delID = self.id.split('-').pop();
    url = hostName + '/quote_items/a/delete?quoteItemID=' + delID;
    parentTable = $(self).parents('.qTable');
    parentID = $(parentTable).attr('id').split('-').pop();

    if (delID == '') {
      showFAIL(self);
      return;
    }

    $.get(url, function(result){
	  if (result.status == '302') {
		jsReload();
	  }
      else if (result.status == 'error') {
        showFAIL(self);
        return;
      }

      // parent item has been deleted
      if (result.parentID != parentID) {
        var arr = $(parentTable).attr('id').split('-');
        arr.pop();
        arr.push(result.parentID);
        $(parentTable).attr('id',arr.join('-'));
      }
      
      $('#quoteQty-' + delID).remove();
      // parent table has no more qty items
      if (! $(parentTable).find('td.quantities table').size()) {
        $(parentTable).remove();
      }

      enableOrder();

    });
  });
}

// show/hide quote item details
function collapseQ() {

  $('a#collapseAll').unbind('click');
  $('a#collapseAll').click(function(){
    $('a.togQ').each(function(){
      collapseItem(this);
    });
  });

  $('a#expandAll').unbind('click');
  $('a#expandAll').click(function(){
    $('a.togQ').each(function(){
      expandItem(this);
    });
  });

  $('a.togQ').unbind('click'); // removes all bound click listeners
  $('a.togQ').click(function(){
    toggleItemVis(this);
  });
}

// toggle visibility of quote item
function toggleItemVis(item) {

  var itemId = item.id.split('-').pop();
  
  if ($('table#quoteQty-' + itemId + ' tbody.extra').is(':visible')) {
    collapseItem(item);
  }
  else {
    expandItem(item);
  }
}

// collapse quote item
function collapseItem(item) {

  var itemId, url;
  itemId = item.id.split('-').pop();
  url = hostName + '/quote_items/a/update?quoteItemID=' + itemId;

  if (itemId == '') return;

  $('table#quoteQty-' + itemId + ' tbody.extra').hide();
  $(item).html('expand');
  $.post(url, {"field":"qiExpanded", "value":"0"});

}

// expand quote item
function expandItem(item) {

  var itemId = item.id.split('-').pop();
  var url = hostName + '/quote_items/a/update?quoteItemID=' + itemId;

  $('table#quoteQty-' + itemId + ' tbody.extra').show();
  $(item).html('collapse');
  $.post(url, {"field":"qiExpanded", "value":"1"});

}


function itemEditInit() {

  $('#qData table.qTable').each(function(){
    var self = this;
    
    itemEdit(self);
    itemMod(self);
    
  });
  
}

var itemEditNode,itemEditNodeId;
// edit quote item main data
function itemEdit(node) {

  var nodeID, url, itemID;

  nodeID = node.id;
  itemID = nodeID.split('-').pop();
  url = hostName + '/quote_items/a/updateParent?quoteItemID=' + itemID;

  $(node).find('tbody:first tr td.editable input[type=text]').unbind('blur');
  $(node).find('tbody:first tr td.editable input[type=text]').blur(function(){
    var self, data;
    self = this;
    data = {"field":self.name, "value":self.value};
    postData(url,data,self);
  });


  $(node).find('tbody:first tr td.editable textarea').unbind('blur');
  $(node).find('tbody:first tr td.editable textarea').blur(function(){
    var self, data;
    self = this;
    data = {"field":self.name, "value":self.value};
    postData(url,data,self);
  });

  $(node).find('tbody:first tr td.editable input[type=checkbox]').unbind('change');
  $(node).find('tbody:first tr td.editable input[type=checkbox]').change(function(){
    var self, data;
    self = this;
    data = {"field":self.name, "value":self.value};
    postData(url,data,self);
  });

  $(node).find('tbody:first tr td.editable select').unbind('change');
  $(node).find('tbody:first tr td.editable select').change(function(){
    if (isNaN(this.value)) {
      var u = hostName + this.value;
      window.location.href = u;
      return;
    }
    var self, data;
    self = this;
    data = {"field":self.name, "value":self.value};
    postData(url,data,self,makeSupplierLink,self)
    
  });

  $(node).find('table').each(function(){
    itemQtyEdit(this);
    reCalc(this);
  });

}

function itemQtyEdit(node) {
  var nodeID, url, itemID;

  nodeID = node.id;
  itemID = nodeID.split('-').pop();
  url = hostName + '/quote_items/a/update?quoteItemID=' + itemID;

  $(node).find('tbody tr td.editable input[type=text]').unbind('blur');
  $(node).find('tbody tr td.editable input[type=text]').blur(function(){
    var self, data, calc;
    self = this;
    data = {"field":self.name, "value":self.value};

    postData(url,data,self,reCalc,node);
    
  });

  $(node).find('tbody tr td.editable input[type=checkbox]').unbind('change');
  $(node).find('tbody tr td.editable input[type=checkbox]').change(function(){
    var self, data;
    self = this;
    data = {"field":self.name, "value":self.value};
    postData(url,data,self);
  });

}


function makeSupplierLink(selectNode) {

  var aStr, labelNode, label;

  labelNode = $(selectNode).parents('table.qTable').find('th.' + selectNode.name)[0];

  if ($(labelNode).children('a').size()) {  // has a tag
    label = $(labelNode).children('a').html()
  }
  else { // no a tag
    label = $(labelNode).html()
  }

  if (selectNode.value == -1) {
    aStr = label;
  }
  else {
    aStr = '<a href="/suppliers/edit/' + selectNode.value + '" target="_blank">' + label + '</a>';
  }

  $(selectNode).parents('table.qTable').find('th.' + selectNode.name).html(aStr);
  
}

/*
 *  edit order item that corresponds to a quote item that has been ordered.
 */
function orderItemEdit(node) {
  var nodeID, itemID, url;

  nodeID = node.id;
  itemID = nodeID.split('-').pop();
  url = hostName + '/order_items/a/quoteUpdate?quoteItemID=' + itemID;
  
  $('#qItem-' + nodeId + ' .editable input[type=text]').blur(function(){
    $.post(url);
  });

  $('#qItem-' + nodeId + ' .editable textarea').blur(function(){
    $.post(url);
  });
}

/*
 *  edit item after added to order.
 *  updates order item.
 *  no edit allowed if order email sent
 */
//function itemMod(nodeId) {
function itemMod(node) {

  $(node).find('table a.mod').click(function(){

    if ($(this).html() == 'lock') {
      $(this).html('mod');
      $('table#quoteQty-' + nodeId + ' table td input[type=text]').each(function(){
        $('table#quoteQty-' + nodeId + ' table .editable input[type=text]').unbind('blur');
        $(this).parent('td').removeClass('editable');
      });
      $('table#quoteQty-' + nodeId + ' table td textarea').each(function(){
        $('table#quoteQty-' + nodeId + ' table .editable textarea').unbind('blur');
        $(this).parent('td').removeClass('editable');
      });
    }
    else {
      $(this).html('lock');
      $('table#quoteQty-' + nodeId + ' table td input[type=text]').each(function(){
        $(this).parent('td').addClass('editable');
      });
      $('table#quoteQty-' + nodeId + ' table td textarea').each(function(){
        $(this).parent('td').addClass('editable');
      });
      itemEdit(node);
      orderItemEdit(node);
    }

  });
}

// initialise actions
function actionInit() {
  $('div#orderControls').hide();
  $('div#copyControls').hide();
  $('a#selectAll').hide();
  $('a#deselectAll').hide();
  
  $('input[type=radio][name=actionSelect]').click(function(){
    // display the checkboxes
    $('table.qTable div.actionItemWrapper').show();
    // uncheck all checkboxes
    $('table.qTable input[type=checkbox].actionItem:checked').attr('checked',false);
    // display select all link and init click functionality
    enableSelectAll(this.value);
    enableDeselectAll(this.value);

    if (this.value == 'order') {
      disableCopy();
      $('table.qTable div.actionItemWrapper.ordered').hide();
      $('table.qTable div.actionItemWrapper.ordered input[type=checkbox]').attr('disabled',true);
      $('div#copyControls').hide();
      $('div#orderControls').show();
      $('table.qTable input[type=checkbox].actionItem').siblings('span').html('Order');
      $('table.qTable input[type=checkbox].actionItem').unbind('click')
      $('table.qTable input[type=checkbox].actionItem').click(function(){
        enableOrder();
      });
      
    }
    else if (this.value == 'copy') {
      disableOrder();
      $('div#copyControls').show();
      $('div#orderControls').hide();
      $('table.qTable input[type=checkbox].actionItem').siblings('span').html('Copy');
      $('table.qTable input[type=checkbox].actionItem').unbind('click')
      $('table.qTable input[type=checkbox].actionItem').click(function(){
        enableCopy();
      });

    }
  });
}

// reinitialise actionItem checkbox clicking
function actionReinit() {
  var val = $('input[type=radio][name=actionSelect]:checked').val();
  
  enableSelectAll(val);
  enableDeselectAll(val);

  if (val == 'order') {
    $('table.qTable input[type=checkbox].actionItem').unbind('click')
    $('table.qTable input[type=checkbox].actionItem').click(function(){
      enableOrder();
    });
  }
  else if (val == 'copy') {
    $('table.qTable input[type=checkbox].actionItem').unbind('click')
    $('table.qTable input[type=checkbox].actionItem').click(function(){
      enableCopy();
    });
  }
  else {
    $('a#selectAll').hide();
    $('a#deselectAll').hide();
  }
}

function enableSelectAll(val) {

  $('a#selectAll').show();
  $('a#selectAll').unbind('click');
  $('a#selectAll').click(function() {
    $('table.qTable input[type=checkbox].actionItem').attr('checked',true);
    if (val == 'order') {
      enableOrder();
    }
    else if (val == 'copy') {
      enableCopy();
    }

  });

}

function enableDeselectAll(val) {

  $('a#deselectAll').show();
  $('a#deselectAll').unbind('click');
  $('a#deselectAll').click(function() {
    $('table.qTable input[type=checkbox].actionItem').attr('checked',false);
    if (val == 'order') {
      disableOrder();
    }
    else if (val == 'copy') {
      disableCopy();
    }

  });

}

function enableOrder() {

  if (!$('table.qTable input[type=checkbox].actionItem:checked').size()) {
    disableOrder();
    return;
  }
  
  $('input[name=makeOrder]').attr('disabled',false);
  $('select[name=ordersID]').attr('disabled',false).change(function(){
    if (this.value == "") {
      $('input[name=makeOrder]').val('Make Order');
    }
    else {
      $('input[name=makeOrder]').val('Add to Order');
    }
  });

}

function disableOrder() {
  $('select[name=ordersID]').attr('disabled',true);
  $('select[name=ordersID]').val('');
  $('input[name=makeOrder]').attr('disabled',true);
  $('input[name=makeOrder]').val('Make Order');
}

function enableCopy() {

  if (!$('table.qTable input[type=checkbox].actionItem:checked').size()) {
    disableCopy();
    return;
  }

  $('select[name=copyQuoteID]').attr('disabled',false).change(function(){
    if (this.value == "") {
      $('input[name=copyToQuote]').attr('disabled',true);
    }
    else {
      $('input[name=copyToQuote]').attr('disabled',false);
    }
  })
}

function disableCopy() {
  $('select[name=copyQuoteID]').attr('disabled',true);
  $('select[name=copyQuoteID]').val('');
  $('input[name=copyToQuote]').attr('disabled',true);
}



function reCalc(node) {

  var qty, itemCost, artSetup, decorateEa, decorateMin, markup, freight, sellEa;
  var costTotal, calcSell, totalSell, margin, costEa;

  // define
  qty = $(node).find('input[name=itemQty]').val();
  qty = qty ? parseInt(qty) : 0;

  itemCost = $(node).find('input[name=itemCost]').val();
  itemCost = itemCost ? parseFloat(itemCost) : 0;

  artSetup = $(node).find('input[name=artworkSetupCost]').val();
  artSetup = artSetup ? parseFloat(artSetup) : 0;

  decorateEa = $(node).find('input[name=itemDecorateCost]').val();
  decorateEa = decorateEa ? parseFloat(decorateEa) : 0;

  decorateMin = $(node).find('input[name=itemDecorateMinCost]').val();
  decorateMin = decorateMin ? parseFloat(decorateMin) : 0;

  markup = $(node).find('input[name=markup]').val();
  markup = markup ? parseInt(markup)/100 : 0;

  freight = $(node).find('input[name=freightCost]').val();
  freight = freight ? parseFloat(freight) : 0;

  sellEa = $(node).find('input[name=itemSell]').val();
  sellEa = isNaN(sellEa) ? 0 : parseFloat(sellEa);

  // calculate and add to display
  costTotal = qty * itemCost + artSetup + qty * decorateEa + decorateMin + freight;
  $(node).find('td#totalCost').html(costTotal.toFixed(2));

  costEa = costTotal / qty;
  $(node).find('td#costEa').html(costEa.toFixed(3));

  calcSell = (costTotal + qty * itemCost * markup + artSetup * markup + qty * decorateEa * markup + decorateMin * markup) / qty;
  if (isNaN(calcSell)) {
    calcSell = 0;
  }

  $(node).find('td#calcSell').html(calcSell.toFixed(5));

  totalSell = sellEa * qty;
  $(node).find('td#totalSell').html(totalSell.toFixed(2));
  
  margin = totalSell - costTotal;
  $(node).find('td#margin').html(margin.toFixed(2));
}


function editQuote() {
  if (!$('.quoteMain').size()) return;

  var url, data, self;
  url = hostName + '/quotes/a/update?quoteID=' + quoteID;

  $('.quoteMain input[type=button].status').click(function(){
    $('.quoteMain input[type=button].status').removeClass('active');
    $('.quoteMain input[type=button].status').removeClass('error');

    var metaID;
    self = this;
    metaID = metaJSON[self.name].metaKey
    data = {"field":"quoteStatus","value":metaID},

    $.post(url, data, function(result) {
		if (result.status == '302') {
		  jsReload();
		}
        else if (result.status == 'error') {
          showFAIL(self);
          console.log(result.message);
          return;
        }

        $(self).addClass('active');
        $('.quoteMain input[type=button].status').attr('disabled',true);

        if (self.name == 'urgent') {
          $('input[type=button].status').attr('disabled',false);
          $('input[type=button][name=current]').attr('disabled',true);
          $('input[type=button][name=urgent]').attr('disabled',true);
        }
        else if (self.name == 'sent') {
          $('input[type=button][name=accepted]').attr('disabled',false);
          $('input[type=button][name=cancel]').attr('disabled',false);
        }

      },
      "json"
    );
  });

  // changing client contact
  $('#changeClientContact').click(function(){
    $('#changeClientContact').toggle();
    $('#contactSelect').toggle();
    $('#contactName').toggle();
  });

  $('#contactSelect select').change(function(){
    if (!this.value) return;

    self = this;
    data = {"field":"clientContactsID","value":this.value}

    $.post(url,data,function(result) {
		if (result.status == '302') {
		  jsReload();
		}
        else if (result.status == 'error') {
          showFAIL(self);
          console.log(result.message);
          return;
        }

        var n;
        if ($('#contactName a.contactLink').size() < 1) {
          n = $('#contact_link_node a').clone()[0];
          $(n).attr('href',n.href.replace('clientContactID',result.clientContactsID).replace('clientsID',result.clientsID));
          $(n).html(result.firstName + ' ' + result.lastName);
          $(n).appendTo('#contactName');
        }
        else {
          n = $('a.contactLink').attr('href');
          $('a.contactLink').attr('href', n.replace('clientContactID',result.clientContactsID).replace('clientsID',result.clientsID));
          $('a.contactLink').html(result.firstName + ' ' + result.lastName);
        }

        $('#contactName').toggle();
        $('#contactSelect').toggle();
        $('#changeClientContact').toggle();
      },
      "json"
    );
    
  });

  $('#quoteNotes').blur(function(){

    self = this;
    data = {"field":"quoteNotes","value":self.value}

    $.post(url, data, function(result) {
		if (result.status == '302') {
		  jsReload();
		}
        else if (result.status == 'error') {
          showFAIL(self);
          console.log(result.message);
          return;
        }

        showOK(self);
      },
      "json"
    );
  });

  $('.quoteMain input[type=checkbox]').change(function(){
    
    self = this;
    data = {"field":self.name,"value":(self.checked?'1':'0')}
    
    $.post(url, data, function(result) {
		if (result.status == '302') {
		  jsReload();
		}
        else if (result.status == 'error') {
          showFAIL(self);
          console.log(result.message);
          return;
        }
        
        showOK(self);
      },
      "json"
    );
  });

}

function listFilter() {
  var url, data, self, u;
  url = hostName + '/metas/a/update?metaID=';

  $('input[type=checkbox].quoteStatus').click(function(){

    self = this;
    u = url + self.value;
    data = {"metaValue":($(self).is(':checked')?1:0),"schemaName":"quoteStatusDisplay"}

    $.post(u, data, function(result){
		if (result.status == '302') {
		  jsReload();
		}
        else if (result.status == 'ok') {
          window.location.href = hostName + '/quotes.html';
        }
        else {
          console.log(result.message);
        }
      },
      "json"
    );
  });
}

$(function(){

  if ($('#quote-list').size()) list();
  if ($('#quote-add').size()) add();
  if ($('#quote-edit').size()) edit();
  
});