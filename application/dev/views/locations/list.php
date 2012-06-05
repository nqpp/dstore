<div id="locationApp">
  <div class="row row-padded">
	<div class="span4">
	  <h1>Locations</h1>
	</div>

	<div class="span4 pull-right">
	  <div class="input-prepend pull-right">
		<span class="add-on">Add new</span><input class="span3" type="text" name="postcode" id="new-location" placeholder="Type postcode and press Enter"/>
	  </div>
	</div>
	
  </div>

  <div class="span6">
 
  <table id="locations" class="table table-striped table-bordered">
	<thead>
	  <tr>
		<th width="50">Zone</th>
		<th width="60">Postcode</th>
		<th>Suburb</th>
		<th width="30"></th>
	  </tr>
	</thead>
	<tbody></tbody>
  </table>
  
  </div>
  
</div>

<script type="text/javascript">
var locationsJSON = <?= $locationsJSON ?>;

</script>

<?= $js_tpl_list ?>
