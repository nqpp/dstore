function listView() {
//console.log($('tr.child table').size());
  $('tr.child table').hide();
  $('td.parent').css('cursor','pointer')
  $('td.parent').click(function(){
    $('tr#child-'+$(this).html()+' table').toggle();
    
  });
}

$(function(){
  if ($('table.paginate').size()) listView();
});