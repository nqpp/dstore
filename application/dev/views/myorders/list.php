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
			  <th>Qty</th>
			  <th>Sub Total</th>
			  <th>Sub Total</th>
			  <th width="40">Sort</th>
			  <th width="100">
				<a class="btn" href="/metas/<?= $schema ?>.html?deleteschema"><span class="icon-trash"> </span> Group</a>
			  </th>			  
			</tr>
		  </thead>
		</table>
	  </div>
	</div>
	<?php $i++; endforeach; ?>
	<?php endif; ?>
	
  </div>
  
</div>

