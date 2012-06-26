<div id="locationApp">
  <div class="row row-padded">
	<div class="span4">
	  <h1>Locations</h1>
	</div>
  </div>
  
  <div class="row-padded well">
	<div class="span1">
	  <strong>Suburb</strong>
	</div>
	<a href="/locations.html?filter=" class="btn btn-mini<?= $filter == 'num'?' btn-inverse':'' ?>">Numeric</a>
	<?php foreach (range('A','Z') as $char): ?>
	<a href="/locations.html?filter=<?= $char ?>" class="btn btn-mini<?= $filter == $char?' btn-inverse':'' ?>"><?= $char ?></a>
	<?php endforeach; ?>
  </div>

  <div class="row">
	
	<div class="span6">

	<table id="locations" class="table table-striped table-bordered">
	  <thead>
		<tr>
		  <th width="70">Postcode</th>
		  <th>Suburb</th>
		  <th width="50">Depot</th>
		  <th width="50">Zone</th>
		</tr>
	  </thead>
	  <tbody></tbody>
	</table>

	</div>
	
	<div class="span6">
	  <div id="pagination"></div>
	</div>
	
  </div>
  
</div>

<script type="text/javascript">
var locationsJSON = <?= $locationsJSON ?>;

</script>

<?= $js_tpl_list ?>
<?= $js_tpl_pagination ?>
