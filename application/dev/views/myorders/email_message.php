<html>
  <body>
	<p>
	  <strong>DStore Order</strong>
	</p>
	<p>Your DStore order has been entered into our system. You can view full details online at dstore.closingthegap.com.au</p>
	<p>Your order summary:</p>
	<p>Purchase Order #: <?= $order->purchaseOrder ?></p>
	<table width="400">
	  <thead>
		<tr>
		  <th style="text-align:left;">Item</th>
		  <th width="60" style="text-align:left;">Qty</th>
		</tr>
	  </thead>
	  <tbody>
		<?php if (count($order->products)): ?>
		<?php foreach ($order->products as $p): ?>
		<tr>
		  <td><?= $p->name ?></td>
		  <td><?= $p->qtyTotal ?></td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	  </tbody>
	</table>
	<p>You will be contacted soon by a representative from NQ Promotional Products to confirm details of your order.</p>
	<p>Thank you for using the DStore.</p>

  </body>
</html>