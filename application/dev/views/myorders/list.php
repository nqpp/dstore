<div class="row row-padded">
  <h1>My Orders</h1>
  
</div>

<div class="row">
  
  <div class="accordion" id="metaAccordion">
	<?php if (count($orders)): ?>
	<?php $i = 1; foreach ($orders as $o): ?>
	<div class="accordion-group">
	  <div class="accordion-heading">
		<a class="accordion-toggle" data-toggle="collapse" href="#collapse<?= $i ?>">
		  <?= sprintf("%04s",$o->orderID) ?> <span class="label label-<?= $o->label ?>"><?= $o->status ?></span>
		</a>
	  </div>
	  <div id="collapse<?= $i ?>" class="accordion-body collapse">
		<table class="table table-striped">
		  <thead>
			<tr>
			  <th width="100">Code</th>
			  <th>Name</th>
			  <th width="60">Qty</th>
			  <th width="60">Sub Total</th>
			  <th width="60">Freight</th>
			  <th width="40">GST</th>
			  <th width="60">Total</th>
			</tr>
		  </thead>
		  <tbody>
			<?php if (count($orderProducts) && isset($orderProducts[$o->orderID])): ?>
			<?php foreach ($orderProducts[$o->orderID] as $op): ?>
			<?php
			$subTotal = $op->qtyTotal * $op->itemPrice;
			$gst = ($subTotal + $op->freightTotal) * $op->taxRate;
			$total = $subTotal + $op->freightTotal + $gst;
			?>
			<tr>
			  <td><?= $op->code ?></td>
			  <td><?= $op->name ?></td>
			  <td><?= $op->qtyTotal ?></td>
			  <td><?= $subTotal ?></td>
			  <td><?= $op->freightTotal ?></td>
			  <td><?= $gst ?></td>
			  <td><?= $total ?></td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		  </tbody>
		</table>
	  </div>
	</div>
	<?php $i++; endforeach; ?>
	<?php else: ?>
	<div>No orders yet. <a href="/store">Let's go shopping</a></div>
	<?php endif; ?>
	
  </div>
  
</div>

