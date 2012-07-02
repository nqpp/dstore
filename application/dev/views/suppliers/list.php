<div id="supplierApp">
  
  <div class="row row-padded">
	<div class="span4">
	  <h1>Suppliers</h1>
	</div>

	<div class="span4 pull-right">
	  <div class="input-prepend pull-right">
		<span class="add-on">Add new</span><input class="span3" type="text" name="name" id="new-supplier" placeholder="Type name and press Enter"/>
	  </div>
	</div>
	
  </div>
  
  <div class="accordion" id="suppliersAccordion">
	<?php if (count($suppliers)): ?>
	<?php foreach ($suppliers as $s): ?>
	<div class="accordion-group">
	  <div class="accordion-heading">
		<a class="accordion-toggle" data-toggle="collapse" href="#collapse<?= $s->supplierID ?>"><?= $s->name ?></a>
	  </div>
	  <div id="collapse<?= $s->supplierID ?>" class="accordion-body collapse">

		<div class="span1 pull-right">
		  <div class="btn-group">
			<a class="btn btn-mini pull-right" href="/suppliers/<?= $s->supplierID ?>" title="edit">
			  <i class="icon-edit"> </i>
			</a>
			<a class="btn btn-mini pull-right contact-delete" href="#" title="delete">
			  <i class="icon-trash"> </i>
			</a>
		  </div>
		</div>
		
		<div class="span3">
		  <h5>Phone</h5>
		  <table class="table table-striped table-bordered">
			<tbody>
			<?php if (count($phones) && isset($phones[$s->supplierID])): ?>
			<?php foreach ($phones[$s->supplierID] as $p): ?>
			  <tr>
				<td><?= $p->metaKey ?></td>
				<td><?= $p->metaValue ?></td>
			  </tr>
			<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		  </table>
		</div>
		
		<div class="span3">
		  <h5>Address</h5>
		  <table class="table table-striped table-bordered table-condensed">
			<tbody>
			<?php if (count($addresses) && isset($addresses[$s->supplierID])): ?>
			<?php foreach ($addresses[$s->supplierID] as $a): ?>
			  <tr>
				<td><?= $a->type ?></td>
				<td>
				  <?= $a->address ?><br>
				  <?= $a->city ?><br>
				  <?= $a->state ?>, <?= $a->postcode ?>
				</td>
			  </tr>
			<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		  </table>
		</div>
		
	  </div>
	</div>
	<?php endforeach; ?>
	<?php endif; ?>
  </div>
  
  
</div>

<script type="text/javascript">
//var suppliersJSON = <?//= //$suppliersJSON ?>;

</script>

<?//= //$js_tpl_list ?>

