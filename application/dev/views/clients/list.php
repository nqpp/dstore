<div id="clientApp">
<div class="row row-padded">
  <div class="span4">
	<h1>Clients</h1>
  </div>

  <div class="span4 pull-right">
	<div class="input-prepend pull-right">
	  <span class="add-on">Add new</span><input class="span3" type="text" name="name" id="new-client" placeholder="Type name and press Enter"/>
	</div>
  </div>

</div>



<table id="clients" class="table table-striped table-bordered">
  <thead>
	<tr>
	  <th>Client</th>
	  <th width="60"></th>
	</tr>
  </thead>
  <tbody>
  </tbody>
</table>

</div>

<script type="text/javascript">
  var clientsJSON = <?= $clientsJSON ?>;
</script>

<?= $js_tpl_list ?>
