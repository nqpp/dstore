<div id="zoneApp">
  <div class="row row-padded">
	<div class="span4">
	  <h1>Zones</h1>
	</div>

	<div class="span4 pull-right">
	  <div class="input-prepend pull-right">
		<span class="add-on">Add new</span><input class="span3" type="text" name="code" id="new-zone" placeholder="Type code and press Enter"/>
	  </div>
	</div>
	
  </div>

  <div class="span4">
	
	<table id="zones" class="table table-striped table-bordered">
	  <thead>
		<tr>
		  <th>Zone</th>
		  <th width="30"></th>
		</tr>
	  </thead>
	  <tbody></tbody>
	</table>
	
  </div>
  
</div>

<script type="text/javascript">
var zonesJSON = <?= $zonesJSON ?>;

</script>

<?= $js_tpl_list ?>
