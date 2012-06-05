<script type="text/template" id="tpl-cart-item">
	<td><img src="<%=image_url%>" alt="<%=name%>" /></td>
	<td>
		<p><strong><a href="/"><%=name%></a></strong><br />
		<span class="less">$<%=itemPrice%> /ea &bull; <strong>Freight:</strong> $<%=freightTotal%></span>
		</p>
	</td>
	<td><%=qtyTotal%></td>
	<td><%=subtotal%></td>
</script> 
