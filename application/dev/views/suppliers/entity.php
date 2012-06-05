<div class="row row-padded">

  <div class="span4">
	<h1>Edit Supplier</h1>
  </div>

  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  <a class="btn" href="/suppliers.html"><span class="icon-list"> </span> list</a>
	  <a class="btn" href="/suppliers/<?= $this->m_suppliers->id ?>.html?delete"><span class="icon-trash"> </span> delete</a>
	</div>

  </div>

</div>


<div class="row">
  
  <div class="span4">
	
	<div id="supplier" class="bb"></div>

  </div>
  
  <div class="span7">
	<h3>Zone Freight Rates</h3>
	<p class="help-block">Use variable charges or flat rate exclusively</p>
	<table id="freight" class="table table-striped table-bordered">
	  <thead>
		<tr>
		  <th></th>
		  <th colspan="3">Variable</th>
		  <th>Flat</th>
		  <th></th>
		</tr>
		<tr>
		  <th width="40">Zone</th>
		  <th width="90" class="border-left-dashed">Base Rate</th>
		  <th width="90">KG Rate</th>
		  <th width="90">Min Charge</th>
		  <th class="border-left-dashed">Flat Rate</th>
		  <th width="20"></th>
		</tr>
	  </thead>
	  <tbody></tbody>
	</table>
	
  </div>
  
</div>

<script type="text/javascript">
var supplierJSON = <?= $supplierJSON ?>;
var supplierFreightsJSON = <?= $supplierFreightsJSON ?>;

</script>

<?= $js_tpl_entity ?>
<?= $js_tpl_supplier_freights ?>
