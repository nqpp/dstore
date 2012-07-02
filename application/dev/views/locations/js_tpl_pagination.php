<script type="text/html" id="tpl-pagination">
  <div class="breadcrumb">

	<span class="cell">
	  <% for(p=0;p<=totalPages;p++){
	  %>
	  <% if (currentPage == p) { %>
	  <span class="page selected"><%= p %></span>
	  <% } else { %>
	  <a href="#" class="page"><%= p %></a>
	  <% } %>	
	  <%	
	  }%>

	  <span class="divider">/</span>

	  <% if (currentPage > firstPage) { %>
	  <a href="#" class="serverprevious">Previous</a>
	  <% }else{ %>
	  <span>Previous</span>
	  <% }%>

	  <% if (currentPage < totalPages) { %>
	  <a href="#" class="servernext">Next</a>
	  <% } %>

	  <% if (firstPage != currentPage) { %>
	  <a href="#" class="serverfirst">First</a>
	  <% } %>

	  <% if (lastPage != currentPage) { %>
	  <a href="#" class="serverlast">Last</a>
	  <% } %>


	  <span class="divider">/</span>

	  <span class="cell serverhowmany">
		Show
		<a href="#" class="selected">3</a>
		|
		<a href="#" class="">9</a>
		|
		<a href="#" class="">12</a>
		per page
	  </span>


	  <span class="divider">/</span>
	  <span class="cell first records">
		Page: <span class="current"><%= currentPage %></span>
		of
		<span class="total"><%= totalPages %></span>
		shown
	  </span>

	  <span class="divider">/</span>

	  <span class="cell sort">
		<a href="#" class="orderUpdate btn small">Sort by:</a>
	  </span>

	  <select id="sortByField">
		<option value="cid">Select a field to sort on</option>
		<option value="suburb">Suburb</option>
		<option value="postcode">Postcode</option>
	  </select>
	</span>


  </div>
</script>
