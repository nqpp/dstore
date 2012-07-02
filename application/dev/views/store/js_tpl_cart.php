<script type="text/template" id="cart-item-tpl">
<li>
  <a href="/store/<%=productsID %>">
	<div class="pull-right"><%=qtyTotal%></div>
	<span><%=name %></span>
  </a>
</li>
</script>

<script type="text/template" id="cart-tpl">
<a class="btn" href="/mycart"><i class="icon-shopping-cart"></i> Cart</a>
<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
<ul class="dropdown-menu" id="cart"></ul>
</script>