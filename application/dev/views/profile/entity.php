<div class="row-padded">
  <h1>My Profile</h1>
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
	&nbsp
  </div>

</div>

<script type="text/javascript">
  var contactJSON = <?= $contactJSON ?>;
  var addressJSON = <?= $addressJSON ?>;
  var phoneJSON = <?= $phoneJSON ?>;
  var employmentDataJSON = <?= $employmentDataJSON ?>;

</script>

<?= $js_tpl_entity ?>
<?= $js_tpl_addresslist ?>
<?= $js_tpl_phonelist ?>
<?= $js_tpl_employmentdatalist ?>
<?= $js_tpl_typeahead_list ?>
