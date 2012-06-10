<h1>Store Cart</h1>
<div class="container">
  <table id="cart" class="table table-striped table-bordered">
  	<tr>
			<th>Item</th>
			<th>QTY</th>
			<th>Subtotal ($)</th>
  		<th></th>
  	</tr>
  </table>
</div>
<script type="text/javascript">
  var cartJSON = <?= $cartJSON ?>;
</script>
<?= $js_tpl_cart_item ?>