<div class="row row-padded">

  <div class="span4">
	<h1>Edit Contact</h1>
  </div>

  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  <a class="btn" href="/clients/<?= $client->clientID ?>.html"><span class="icon-user"> </span> client</a>
	  <a class="btn contact-delete" href="/contacts/<?= $client->usersID ?>.html?delete"><span class="icon-trash"> </span> delete</a>
	</div>

  </div>

</div>


<div class="row row-padded">
  
  <div class="span4">
	<div id="contact"></div>
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
  
  <div class="span4">
	<h4>Orders</h4>
  </div>

</div>

<script type="text/javascript">
  var contactJSON = <?= $contactJSON ?>;
  var clientJSON = <?= $clientJSON ?>;
  var addressJSON = <?= $addressJSON ?>;
  var phoneJSON = <?= $phoneJSON ?>;

</script>

<?= $js_tpl_entity ?>
<?= $js_addresslist ?>
<?= $js_phonelist ?>
<?= $js_tpl_typeahead_list ?>
