<script type="text/template" id="tpl-list">
  <div class="thumbnail">
	<div class="handle <%= typeof(category) == 'string'?category.toLowerCase():''%>"><%=category%>&nbsp</div>
	<h5><a href="/products/<%= productID %>"><%= name %></a></h5>
	<a href="/products/<%= productID %>">
	  <img src="/images/<%= (typeof imgDir != 'string' ? 'no_image':imgDir) %>/2/192/192/<%= (typeof img != 'string' ? 'no_image.png':img) %>">
	</a>
	
  </div>
</script>