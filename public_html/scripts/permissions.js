
function list() {
  var self;

  $('input[type=checkbox].permission').click(function(){
    savePermission(this);

    if ($(this).hasClass('parent-0')) {
      updateChildren(this);
    }
  });

  $('input[type=checkbox].checkAll').click(function(){
    self = this;
    if (self.checked) {
      $('input[type=checkbox][value=' + self.value + '].permission').attr('checked',true).each(function(){
        savePermission(this)
      });
    }
    else {
      $('input[type=checkbox][value=' + self.value + '].permission').attr('checked',false).each(function(){
        savePermission(this)
      });

    }
  });
  
}

function savePermission(node) {
  var url, data, self, u, permissionID, bit;
  url = hostName + '/permissions/a/update?permissionID='

  self = node;
  permissionID = self.id.split('-').pop();
  u = url + permissionID;
  bit = 0;

  $(self).parents('td').find('input[type=checkbox].permission').each(function(){
    bit += parseInt((this.checked ? this.value : 0));
  });

  data = {"bit":bit}
  postData(u,data,self);

}

function updateChildren(node) {
  var parentID, parentValue;
  parentID = node.id.split('-').pop();
  parentValue = node.value;

  $('input[type=checkbox][value=' + parentValue + '].parent-' + parentID).each(function(){
    if (node.checked) {
      $(this).attr('checked',true);
    }
    else {
      $(this).attr('checked',false);
    }
    savePermission(this)
  });
}

$(function(){
  list();
});