<div id="clientApp">
  <div class="row row-padded">

	<div class="span4">
	  <h1>Edit Client</h1>
	</div>

	<div class="span4 pull-right">
	  <div class="btn-group pull-right">
		<a class="btn" href="/clients.html"><span class="icon-list"> </span> list</a>
		<a class="btn client-delete" href="/clients/<?= $this->m_clients->id ?>.html?delete"><span class="icon-trash"> </span> delete</a>
	  </div>

	</div>

  </div>

  <div class="row">

	<div class="span4">
	  <div class="" id="client"></div>

	  <h4>Addresses</h4>
	  <table id="address" class="table table-striped table-bordered">
		<thead>
		  <tr>
			<th width="80">Type</th>
			<th>Address</th>
			<th width="30">
			  <a class="btn btn-mini" href="#" class="addContact"><i class="icon-plus"> </i></a>
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
			  <a class="btn btn-mini" href="#" class="addContact"><i class="icon-plus"> </i></a>
			</th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>

	</div>


	<div class="span8">

	  <div id="contacts">
		<div class="row">
		  
		  <div class="span2">
			<h4>Contacts</h4>		  
		  </div>
		  
		  <div class="span4 pull-right">
			<div class="input-prepend pull-right">
			  <span class="add-on">Add new</span><input class="span3" type="text" name="firstName" id="new-contact" placeholder="Type name and press Enter"/>
			</div>
		  </div>
		  
		</div>
		<div class="accordion" id="contactsAccordion">
		  
		</div>
	  </div>

	</div>

  </div>


</div>


<script type="text/javascript">
  var clientJSON = <?= $clientJSON ?>;
  var contactsJSON = <?= $contactsJSON ?>;
  var addressJSON = <?= $addressJSON ?>;
  var phoneJSON = <?= $phoneJSON ?>;
  var contactPhonesJSON = <?= $contactPhonesJSON ?>;

</script>


<?= $js_tpl_entity ?>
<?= $js_tpl_contactlist ?>
<?//= $js_clientcontact_detail ?>
<?= $js_tpl_addresslist ?>
<?= $js_tpl_phonelist ?>
<?= $js_tpl_contact_phonelist ?>


