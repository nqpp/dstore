<script type="text/template" id="tpl-order-products-list">
  <td class="quantities">
	<%= name %>
	<table class="table table-striped table-condensed table-bordered quantities">
	  <thead>
		<tr>
		  <th width="70">Code</th>
		  <th>Name</th>
		  <th width="50">Qty</th>
		</tr>
	  </thead>
	  <tbody>
	  </tbody>
	</table>
  </td>
  <td><span class="pull-right"><%= qtyTotal %></span></td>
  <td><span class="pull-right"><%= subTotal %></span></td>
  <td><span class="pull-right"><%= freightTotal %></td>
  <td><span class="pull-right"><%= gst %></td>
  <td><span class="pull-right"><%= total %></td>
</script>