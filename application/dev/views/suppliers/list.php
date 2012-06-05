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
  
  
  <table id="suppliers" class="table table-striped table bordered">
	<thead>
	  <tr>
		<th>Supplier</th>
	  </tr>
	</thead>
	<tbody>	  
	</tbody>
  </table>
  
</div>

<script type="text/javascript">
var suppliersJSON = <?= $suppliersJSON ?>;

</script>

<?= $js_tpl_list ?>

