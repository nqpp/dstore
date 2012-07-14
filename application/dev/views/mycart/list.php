<div class="row row-padded">
  <div class="span4">
	<h1>My Cart</h1>
  </div>
  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  <a href="/mycart.html?clear" class="btn">
		<i class="icon-trash"> </i> Empty Cart
	  </a>
	  <a href="#orderModal"data-toggle="modal" id="orderAction" class="btn"<?php if (!count($carts)) {
  echo ' disabled';
} ?>>
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
	<? $cartTotal += $c->total ?>
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
	<?php foreach ($cartItems[$c->cartID] as $ci): ?>
	  				<tr>
	  				  <td><?= $ci->name ?></td>
	  				  <td><?= $ci->qty ?></td>
	  				</tr>
	<?php endforeach ?>
				  </tbody>
				</table>
			  </td>
			  <td><?= $c->qtyTotal ?></td>
			  <td>
				<span class="pull-right"><?= number_format($c->total, 2, '.', ',') ?></span>
			  </td>
			  <td>
				<div class="btn-group">
				  <a class="btn btn-mini" href="/store/<?= $c->productsID ?>.html" title="edit"> <i class="icon-edit"> </i></a>
				  <a class="btn btn-mini cart-item-delete" href="/mycart/<?= $c->cartID ?>.html?delete" title="delete"><i class="icon-trash"> </i> </a>

				</div>
			  </td>
			</tr>
  <?php endforeach; ?>
  	  </tbody>
  	  <tfoot>
  		<tr>
  		  <td colspan="2">
  			<strong class="pull-right">Cart Total</strong>
  		  </td>
  		  <td>
  			<strong class="pull-right"><?= number_format($cartTotal, 2, '.', ',') ?></strong>
  		  </td>
  		  <td></td>
  		</tr>
  	  </tfoot>
<?php else: ?>
  	  <tr>
  		<td colspan="4">
  		  <p>There are no items in your cart.</p>
  		  <p>You can add new items by viewing their details in the <a href="/store">Store</a></p>
  		</td>
  	  </tr>
  	  </tbody>
<?php endif; ?>
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

<div class="modal hide" id="orderModal">
  <form class="form-horizontal noMargin" action="/myorders.html?new" method="POST">
	<div class="modal-header">
	  <h3>Order</h3>
	</div>
	<div class="modal-body">
	  <fieldset>
		<div class="control-group">
		  <label class="control-label" for="input01">Select Delivery Address</label>
		  <div class="controls">
			<div class="btn-group" id="deliveryAddressID">
			  <a class="btn dropdown-toggle" data-toggle="dropdown"><span>Select Address&hellip; </span> <b class="caret"></b></a>
			  <ul class="dropdown-menu"></ul>
			  <input type="hidden" name="deliveryAddressID" id="valDeliveryAddressID" value="<?php echo $userAddressID ?>" />
			</div>
		  </div>
		</div>
		<hr />
		<div class="control-group">
		  <label class="control-label" for="input01">Double Check Cart</label>
		  <div class="controls">
			<table class="table table-striped table-bordered" id="orderModalCart"></table>
		  </div>
		</div>
		<hr />
		<div class="control-group">
		  <label class="control-label" for="purchaseOrder">Enter Purchase Order Number</label>
		  <div class="controls">
			<input type="text" class="input-xlarge purchaseOrder" id="purchaseOrder" name="purchaseOrder">
		  </div>
		</div>
	  </fieldset>
	</div>
	<div class="modal-footer">
	  <a href="#" class="btn" data-dismiss="modal">Close</a>
	  <button type="submit" class="btn btn-success btn-large" id="doOrder" disabled="true">Order</button>
	</div>
  </form>
</div>
<script type="text/javascript">
  var userAddresses = <?= $userAddresses ?>;
  var userAddressID = <?php echo $userAddressID ?>;
  var carts = <?= json_encode($carts) ?>;
</script>
<script type="text/template" id="popCartItem">
  <td>
	<strong><%=name %></strong>
  </td>
  <td><%=qtyTotal %></td>
  <td>
	<span class="pull-right"><%=total %></span>
  </td>
</script>
<script type="text/template" id="popCart">
  <thead>
	<tr>
	  <th>Item</th>
	  <th width="60">Qty</th>
	  <th width="80">Subtotal</th>
	</tr>
  </thead>
  <tbody></tbody>
  <tfoot>
	<tr>
	  <td colspan="2"><strong class="pull-right">Freight Total</strong></td>
	  <td><strong class="pull-right" id="freightTotal"></strong></td>
	</tr>
	<tr>
	  <td colspan="2">
		<strong class="pull-right">Cart Total</strong>
	  </td>
	  <td>
		<strong class="pull-right" id="cartTotal"></strong>
	  </td>
	</tr>
  </tfoot>
  <p class="help-block"><i class="icon-question-sign"></i> <strong>Why did the price change?</strong><br />
	As you updated your delivery address, we re-calculated your freight to deliver to the new address.</p>
</script>