<div class="row row-padded">
  <div class="span-4">
	<h1>My Orders</h1>
  </div>
  
</div>
  
<div class="accordion" id="metaAccordion">
  <?php if (count($orders)): ?>
  <?php $i = 1; foreach ($orders as $o): ?>
  <div class="accordion-group">

	<div class="accordion-heading">
	  <a class="accordion-toggle" data-toggle="collapse" href="#collapse<?= $i ?>">
		<div class="row">
		  <div class="span2 pull-right">
			<span class="label label-<?= $o->status ?> pull-right"><?= ucfirst($o->status) ?></span>
		  </div>
		  <div class="span2">
			<?= sprintf("#%04s",$o->orderID) ?> &nbsp; 
			<?= date("d M Y",strtotime($o->createdAt)) ?>
		  </div>
		</div>
	  </a>
	</div>

	<div id="collapse<?= $i ?>" class="accordion-body collapse">
	  
		<div class="span8-5">

		  <table class="table table-striped table-bordered">
			<thead>
			  <tr>
				<th>Name</th>
				<th width="60">Qty</th>
				<th width="80">Sub Total</th>
				<th width="80">Freight</th>
				<th width="60">GST</th>
				<th width="80">Total</th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
			  $orderTotal = 0;
			  ?>
			  <?php if (count($orderProducts) && isset($orderProducts[$o->orderID])): ?>
			  <?php foreach ($orderProducts[$o->orderID] as $op): ?>
			  <?php
			  $subTotal = $op->qtyTotal * $op->itemPrice;
			  $gst = ($subTotal + $op->freightTotal) * ($op->taxRate / 100);
			  $total = $subTotal + $op->freightTotal + $gst;
			  $orderTotal += $total;
			  ?>
			  <tr>
				<td>
				  <?= $op->name ?>
				  <table class="table table-striped table-condensed table-bordered">
					<thead>
					  <tr>
						<th width="70">Code</th>
						<th>Name</th>
						<th width="60">Qty</th>
					  </tr>
					</thead>
					<tbody>
					  <?php if (count($orderProductQuantities) && isset($orderProductQuantities[$op->orderProductID])): ?>
					  <?php foreach ($orderProductQuantities[$op->orderProductID] as $opq): ?>
					  <tr>
						<td><?= $opq->code ?></td>
						<td><?= $opq->name ?></td>
						<td><?= $opq->qty ?></td>
					  </tr>
					  <?php endforeach; ?>
					  <?php endif; ?>
					</tbody>
				  </table>
				</td>
				<td><?= $op->qtyTotal ?></td>
				<td><span class="pull-right"><?= number_format($subTotal,2,'.',',') ?></span></td>
				<td><span class="pull-right"><?= number_format($op->freightTotal,2,'.',',') ?></td>
				<td><span class="pull-right"><?= number_format($gst,2,'.',',') ?></td>
				<td><span class="pull-right"><?= number_format($total,2,'.',',') ?></td>
			  </tr>
			  <?php endforeach; ?>
			  <?php endif; ?>
			</tbody>
			<tfoot>
			  <tr>
				<td></td>
				<td colspan="4">
				  <span class="pull-right"><strong>Order Total</strong></span>
				</td>
				<td><span class="pull-right"><strong>$<?= number_format($orderTotal,2,'.',',') ?></strong></span></td>
			  </tr>
			</tfoot>
		  </table>

		</div>

		<div class="span3">
		<div class="well">

		  <h5>Deliver To</h5>
		  <div class="orderAddress"></div>
		  <p>
			<?php if (isset($orderAddresses[$o->orderID])): ?>
			<?php
			$a = $orderAddresses[$o->orderID];
			?>
		  <?= $a->address ?><br>
		  <?= $a->city ?><br>
		  <?= $a->state ?>, <?= $a->postcode ?>
		  <?php endif; ?>
		  </p>
		  
		</div>
		</div>

	</div>
  </div>
  <?php $i++; endforeach; ?>
  <?php else: ?>
  <div>No orders yet. <a href="/store">Let's go shopping</a></div>
  <?php endif; ?>

</div>
  
