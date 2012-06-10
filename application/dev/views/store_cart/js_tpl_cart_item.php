<script type="text/template" id="tpl-cart-item">
	<td>
		<p><strong><a href="/store/<%=productsID%>"><%=name%></a></strong><br />
		<span class="less">$<%=itemPrice%> ea &bull; <strong>Freight:</strong> $<%=freightTotal%></span></p>
		<table class="table table-condensed">
			<tr>
				<th>Code</th>
				<th>Colour</th>
				<th>QTY</th>
			</tr>
		</table>
	</td>
	<td><%=qtyTotal%></td>
	<td></td>
	<td><a href="/carts/<%=cartID%>" class="remove"><i class="icon-remove"></i></a></td>
</script>
<script type="text/template" id="tpl-cart-item-item">
	<td><%=code%></td>
	<td><%=name%></td>
	<td><%=qty%></td>
</script>