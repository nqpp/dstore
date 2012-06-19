<div class="row row-padded">
  <div class="span4">
	<h1>My Cart</h1>
  </div>
  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  <a href="/mycart.html?clear" class="btn">
		<i class="icon-trash"> </i> Empty Cart
	  </a>
	  <a href="/myorders.html?new" class="btn"<?php if (!count($carts)) {echo ' disabled';} ?>>
		<i class="icon-shopping-cart"> </i> Order
	  </a>
	</div>
  </div>
</div>


<div class="row row-padded">

  <div class="span8">

	<table id="cart" class="table table-striped table-bordered">
	  <thead>
		<tr>
		  <th>Item</th>
		  <th width="60">Qty</th>
		  <th width="80">Subtotal</th>
		  <th width="60"></th>
		</tr>
	  </thead>
	  <tbody>
		<?php $cartTotal = 0; ?>
		<?php if (count($carts)): ?>
		<?php foreach ($carts as $c): ?>
		<?php
		$subTotal = $c->qtyTotal * $c->itemPrice;
		$gst = ($subTotal + $c->freightTotal) * $c->taxRate;
		$total = $subTotal + $c->freightTotal + $gst;
		$cartTotal += $total;
		?>
		<tr>
		  <td>
			<a href="/store/<?= $c->productsID ?>.html" title="edit"><?= $c->name ?></a>
			<table class="table table-bordered table-condensed">
			  <thead>
				<tr>
				  <th>Colour</th>
				  <th width="100">Qty</th>
				</tr>
			  </thead>
			  <tbody>
			  <?php foreach($cartItems[$c->cartID] as $ci): ?>
				<tr>
				  <td><?= $ci->name ?></td>
				  <td><?=  $ci->qty ?></td>
				</tr>
			  <?php endforeach ?>
			  </tbody>
			</table>
		  </td>
		  <td><?= $c->qtyTotal ?></td>
		  <td>
			<span class="pull-right"><?= number_format($total,2,'.',',') ?></span>
		  </td>
		  <td>
			<div class="btn-group">
			  <a class="btn btn-mini" href="/store/<?= $c->productsID ?>.html" title="edit"> <i class="icon-edit"> </i></a>
			  <a class="btn btn-mini cart-item-delete" href="/mycart/<?= $c->cartID ?>.html?delete" title="delete"><i class="icon-trash"> </i> </a>
			  
			</div>
		  </td>
		</tr>
		<?php endforeach ?>
		<?php else: ?>
  		<tr>
  		  <td colspan="4">
  			<p>There are no items in your cart.</p>
  			<p>You can add new items by viewing their details in the <a href="/store">Store</a></p>
  		  </td>
  		</tr>
		<?php endif; ?>
	  </tbody>
	  <tfoot>
		<tr>
		  <td colspan="2">
			<strong class="pull-right">Cart Total</strong>
		  </td>
		  <td>
			<strong class="pull-right"><?= number_format($cartTotal,2,'.',',') ?></strong>
		  </td>
		  <td></td>
		</tr>
	  </tfoot>
	</table>

  </div>

  <div class="span4">
	
	<h3>Cart Instructions</h3>
	<ul>
	  <li>Remove a product from your cart by clicking the garbage button at the end of the row.</li>
	  <li>Update product quantities by clicking on the product name or edit button at the end of the row and changing quanitites for colours.</li>
	  <li>Place your order by clicking the Order button at the top right.</li>
	  <li>Empty your cart by clicking the Empty Cart button at the top right.</li>
	</ul>
	
  </div>

</div>

<script type="text/javascript">
  //  var cartJSON = <?//= $cartJSON ?>;
</script>
<?
//= $js_tpl_cart_item ?>