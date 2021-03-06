<div class="row row-padded">

  <div class="span4">
	<h1>Edit Supplier</h1>
  </div>

  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  
	  <a class="btn" href="/suppliers/<?= $this->m_suppliers->id ?>.html?delete"><span class="icon-trash"> </span> delete</a>
	  
	  <a class="btn" href="/suppliers.html">
		<i class="icon-list"> </i> list
	  </a>
	  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
		<i class="caret"> </i>
	  </a>
	  <ul class="dropdown-menu">
		<?php if (count($suppliers)): ?>
		<?php foreach ($suppliers as $s): ?>
		<li><a href="/suppliers/<?= $s->supplierID ?>"><?= $s->name ?></a></li>
		<?php endforeach; ?>
		<?php endif; ?>
	  </ul>
	  
	</div>

  </div>

</div>


<div class="row">
  
  <div class="span4">
	
	<div id="supplier" class="bb"></div>

  </div>
  
  <div class="span4">
	  <h4>Addresses</h4>
	  <table id="address" class="table table-striped table-bordered">
		<thead>
		  <tr>
			<th width="80">Type</th>
			<th>Address</th>
			<th width="30">
			  <a class="btn btn-mini addContact" href="#"><i class="icon-plus"> </i></a>
			</th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>

	  <h4>Phone Numbers</h4>
	  <table id="phone" class="table table-striped table-bordered">
		<thead>
		  <tr>
			<th width="80">Type</th>
			<th>Number</th>
			<th width="30">
			  <a class="btn btn-mini addContact" href="#"><i class="icon-plus"> </i></a>
			</th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>
  </div>
  
</div>

<script type="text/javascript">
var supplierJSON = <?= $supplierJSON ?>;
var addressJSON = <?= $addressJSON ?>;
var phoneJSON = <?= $phoneJSON ?>;

</script>

<?= $js_tpl_entity ?>
<?= $js_addresslist ?>
<?= $js_phonelist ?>
<?= $js_tpl_typeahead_list ?>

