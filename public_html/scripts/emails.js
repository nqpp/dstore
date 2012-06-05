var emailID;

function attachField(filename,dir) {
  var f = '<input type="hidden" name="attachments[' + dir + '][]" value="' + filename + '">';
  $('form[name=emailer]').append(f);
}

function detachField(filename) {
  $('form[name=emailer]').find('input[type=hidden][value=' + filename + ']').remove();
}

function moveListItem(filename) {


}

function uploader(){
  var artDir = '/fileUploader.php?artDir=artwork/tmp/';

  var uploader = new qq.FileUploader({
      element: document.getElementById('file-uploader'),
//      listElement: document.getElementById('media-list'),
      action: artDir,
      debug: true,
      onComplete:function(id,filename,responseJSON){
        if (responseJSON.filename) {
          attachField(responseJSON.filename,'tmp');
          mediaAdd(responseJSON.filename,'tmp');
          mediaClick();
          return true;
        }
        else {
          return false;
        }
      }
  });

}

function mediaSelect() {

  $('#media-library select').unbind('change');
  $('#media-library select').change(function(){
    // null selection
    if (this.value == '') return;
    // avoid duplicates
    if ($('form[name=emailer]').find('input[type=hidden][value=' + this.value + ']').size()) {
      resetSelect(this);
      return;
    }
    // attach hidden field
    attachField(this.value,this.className);
    // add to visible list
    mediaAdd(this.value, this.className);
    // bind click to new link
    mediaClick();
  // reset select list
    resetSelect(this);
  });
  
}

// reset select list
function resetSelect(sel) {

  $(sel).children('option:selected').removeAttr('selected');
  $(sel).children('option:first').attr('selected',true);
}

// set event bind on remove links
function mediaClick() {

  $('ul#media-list li a.media-remove').unbind('click');
  $('ul#media-list li a.media-remove').click(function(){

    var filename = $(this).parent().children('span').text();

    if ($(this).hasClass('tmp')) {
      deleteUploaded(filename);
      removeAttachment(filename,'tmp');
    }
    else if ($(this).hasClass('system')) {
      removeAttachment(filename,'system');
    }
    
    detachField(filename);
    $(this).parent().remove();
    removeHeading();

  });

}

// delete uploaded tmp file
function deleteUploaded(filename) {
  var url, data;
  url = hostName + '/medias/a/deleteFile';
  data = {'filename':filename,'dir':'tmp'}

  $.post(url, data,
    function(result){
	  if (result.status == '302') {
		jsReload();
	  }
      else if (result.status == 'error') {
        // do something with error
        console.log(result.message);
      }
	  
    }
  );
  
}

// remove attachment from db record
function removeAttachment(filename, dir) {

  var url,data;
  // we have an emailID so possible that the file has been saved to this record
  if (!emailID) return;

  url = hostName + '/emails/a/removeAttachment.html?emailID=' + emailID;
  data = {'filename':filename,'dir':dir}

  $.post(url, data,
    function(result){
	  if (result.status == '302') {
		jsReload();
	  }
      else if (result.status == 'error') {
        // do something with success
        console.log(result.message);
      }
    }
  );

}


// add new item to attachment list.
function mediaAdd(filename,dir) {

    addHeading();
    var li = '<li><span>';
    li += filename
    li += '</span> ';
    li += '<a href="javascript:void(0);" class="media-remove ' + dir + '">Remove</a>';
    li += '</li>';
    
    $('ul#media-list').append(li);
//    $('ul#media-list').append('<li><span>' + filename + '</span> <a href="javascript:void(0);" class="media-remove">Remove</a></li>');
}
// add heading above attachments list
function addHeading() {

  if ($('ul#media-list li').size()) return;

  var str = '<label id="attachment-heading">Attachments</label>';
  $('ul#media-list').before(str);
}
// add heading above attachments list
function removeHeading() {

  if ($('ul#media-list li').size()) return;

  $('#attachment-heading').remove();
}


function trim(str) {
  return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
}

function toolRow() {

  $('a.upload').click(function(){
    $('#file-uploader').parent('.hide').show();
  });

  $('a.email_cc').click(function(){
    $('input[name=email_cc]').parent('.hide').show();
  });

  $('a.email_bcc').click(function(){
    $('input[name=email_bcc]').parent('.hide').show();
  });

  $('a.media-library').click(function(){
    $('#media-library').show();
  });

}

function mce() {

  $('textarea.editor').tinymce({

    height : '340px',
    script_url : '/scripts/tiny_mce/tiny_mce.js',
    theme : "advanced",
    plugins : "pagebreak,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,nonbreaking,xhtmlxtras,template",
    theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,formatselect,undo,redo,|,link,unlink,code",
//    theme_advanced_buttons2 : "",
    theme_advanced_buttons2 : "tablecontrols",
    theme_advanced_buttons3 : "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true

  });

}


$(function(){
  mce();
  toolRow();
  uploader();
  mediaSelect();
  mediaClick();
});