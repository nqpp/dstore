<script type="text/template" id="cart-item-tpl">
<li><a href="/store/<%=productsID %>"><%=name %> (x<%=qtyTotal%>)</a></li>
</script>
<script type="text/template" id="cart-tpl">
<div class="btn-group pull-right">
  <a class="btn" href="/mycart"><i class="icon-shopping-cart"></i> Cart</a>
  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
  <ul class="dropdown-menu" id="cart"></ul>
</div>
</script>