String.prototype.nl2br = function(is_xhtml) {
  var str = this.valueOf();
//  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '' : '<br>';
  var breakTag = '<br>';
//console.log(this)
  return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

//
//var flash = false;
//var noFlash = true;
//var hostName = window.location.protocol + '//' + window.location.hostname;
//var monthsShort = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
//
//// toggle visibility of information in sidebar. record changed state to db.
//function accordion() {
//  if (! $('.accordion').size()) return;
//  
//  $('.hidden').hide();
//  $('.hidden').siblings('h3').css('text-decoration','underline');
//  $('.accordion').css('cursor','pointer');
//
//  $('.accordion').click(function(){
//	var self, data, url, metaKey;
//	self = this;
//	url = hostName + '/metas/a/updateValue'
//	metaKey = $(this).siblings('.'+this.id).attr('id');
//	data = {"schemaName":"userPref","metaKey":metaKey}
//	
//    if ($(this).siblings('.'+this.id).hasClass('hidden')) {
//      $(this).siblings('.'+this.id).removeClass('hidden');
//      $(this).siblings('.'+this.id).show();
//      $(this).css({'text-decoration': 'none','cursor':'normal'});
//	  data.metaValue = '0';
//    }
//    else {
//      $(this).siblings('.'+this.id).addClass('hidden');
//      $(this).siblings('.'+this.id).hide();
//      $(this).css({'text-decoration': 'underline','cursor':'pointer'});
//	  data.metaValue = '1';
//    }
//	
//	$.post(url, data);
//  });
//}
//
//function confirmDelete() {
//  $('a.delete').click(function(){
//    return confirm('Really Delete?');
//  });
//}
//
//function flashMessage() {
//  if (noFlash) return;
//  
//  $('.flash').hide().delay(2000).slideDown('slow').delay(5000).slideUp('slow');
//}
//
////function dates() {
////  $('.date').dateinput({format: 'dd mmm yyyy'});
////
////  $('.minCal').dateinput({
////    format: 'dd mmm yyyy',
////    selectors: true,
////    css: {
////      prefix: 'min_cal'
////    }
////  });
////
////}
//
//function showFOCUS(node) {
//  $(node).addClass('focus');
//}
//
//function showOK(node) {
//
//  $(node).removeClass('focus');
//  $(node).addClass('ok').delay('2000').queue(function(){
//    $(this).removeClass('ok');
//    $(this).dequeue();
//  });
//}
//
//function showFAIL(node) {
//  
//  $(node).removeClass('focus');
//  $(node).addClass('fail').delay('2000').queue(function(){
//    $(this).removeClass('fail');
//    $(this).dequeue();
//  });
//}
//
//
////restripe nominated table rows
//function restripeNodes(nodes) {
//  var count, rowClass;
//  count = 1;
//  
//  $(nodes).each(function(){
//    rowClass = count%2?'odd':'even';
//    $(this).removeClass('odd even').addClass(rowClass);
//    count++;
//  });
//  
//}
//
//// perform ajax post
//function postData(u,d,n,callback,arg) {
//
//  $.post(u, d, function(result){
//	if (result.status == '302') {
//	  jsReload();
//	}
//	else if (result.status == 'error') {
//	  showFAIL(n);
//	  console.log(result.message);
//	  return;
//	}
//	  if (typeof(callback) != 'undefined') {
//		if (callback == jsReload) {
//		  callback(arg);
//		}
//		else {
//		  callback(result,arg);
//		}
//	  }
//	showOK(n);
//	},
//	"json"
//  );
//  
//}
//
//function jsReload(href) {
//  var url = hostName;
//  
//  if (href) {
//	window.location.replace(url + href);	
//  }
//  else {
//	window.location.reload();
//  }
//  
//}

$(function(){
//  accordion();
//  dates();
//  confirmDelete();
//  flashMessage();
//
//  $('.hide').hide();
})