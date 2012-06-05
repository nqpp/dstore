<script type="text/template" id="tpl-list">
  <div class="thumbnail">  
<div class="handle <%= typeof(category) == 'string'?category.toLowerCase():''%>"><%=category%></div>
<a href="/products/<%= productID %>"><img src="/images/<%= (typeof imgDir != 'string' ? 'no_image':imgDir) %>/2/160/160/<%= (typeof img != 'string' ? 'no_image.png':img) %>"></a>
<div class="title"><a href="/products/<%= productID %>"><%= name %></a></div>
  </div>
</script>