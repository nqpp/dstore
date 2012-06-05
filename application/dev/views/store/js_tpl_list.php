<script type="text/template" id="tpl-list">
<div class="handle <%= typeof(category) == 'string'?category.toLowerCase():''%>">
  <a href="/store/<%= productID %>"><%=category%></a>
</div>
<a href="/store/<%= productID %>">
  <img src="/images/<%= (typeof imgDir != 'string' ? 'no_image':imgDir) %>/2/160/160/<%= (typeof img != 'string' ? 'no_image.png':img) %>">
</a>
<div class="title"><a href="/store/<%= productID %>"><%= name %></a></div>
</script>