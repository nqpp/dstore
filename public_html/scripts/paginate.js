/*
 * Table pagination plugin for jQuery.
 * Inspired by PacktPub's Tut: http://www.packtpub.com/article/jquery-table-manipulation-part1
 * Bastardised by Matthew Sutton <matt@imagesmith.com.au>
 * ImageSmith 2010
 * TODO: First/Last & Prev/Next links (as an option).
 * TOOD: Activate 1st Link on init.
 */

$.fn.paginate = function(settings) {
  
  $table = this;
  rows = $table.find('> tbody > tr');

  // Env vars for pagination
	var options = $.extend({
    curPage: 0,
    perPage: 20,
    numRows: rows.length,
    numPages: 0
  }, settings);
	
	// Core logic
  // Hide all rows that do not appear in current range.
  doPaginate = function() {

    lt = options.curPage * options.perPage;
//    gt = (options.curPage + 1) * options.perPage - 1;
    gt = (options.curPage + 1) * options.perPage;
    $(rows).hide(); // Hide all rows
    $(rows).slice(lt,gt).show(); // Show rows within range
  }

  options.numPages = Math.ceil(options.numRows / options.perPage);

	// Only Paginate if there is more than one page.
	if(options.numPages > 1) {
		// Paging HTML
	  $pager = $('<ul class="pagination"></ul>');
	  for(i = 0; i < options.numPages; i++) {
	    $('<li>' + (i + 1) + '</li>').bind('click',{'p':i},function(event) {
	        options.curPage = event.data['p'];
	        doPaginate();
	        $(this).addClass('active').siblings().removeClass('active');
	      }).appendTo($pager);
	  }

	  doPaginate();
	  $pager.insertAfter($table);
	}
};