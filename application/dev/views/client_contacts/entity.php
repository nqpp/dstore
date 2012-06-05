<h1>Edit Client Contact</h1>

<form action="<?=$_SERVER['REQUEST_URI'] ?>" method="post" id="edit" name="">
  
  <div class="container">
	<div class="span-8">
	  
	  <table id="contact" class="bb">
		<thead>
		  <tr>
			<th>Back to </th>
			<td><?= $client->name ?></td>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>
	  
	  <table id="address" class="bb">
		<thead>
		  <tr>
			<th width="80">Address</th>
			<th></th>
			<th width="10">
			  <a href="javascript:void(0);" class="addContact">+</a>
			</th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>
	  
	  <table id="phone" class="bb">
		<thead>
		  <tr>
			<th width="80">Phone</th>
			<th></th>
			<th width="10">
			  <a href="javascript:void(0);" class="addContact">+</a>
			</th>
		  </tr>
		</thead>
		<tbody>
		</tbody>
	  </table>
	  
	</div>
	
	<div class="span-8">
	  
	</div>
	
	<div class="span-8 last">
	
	</div>
	
  </div>
  
  <div class="jsHide container">

	<a href="/clients.html" class="cancel">cancel</a>

  </div>
  
</form>


<script type="text/javascript">
var contactJSON = <?= $contactJSON ?>;
var addressJSON = <?= $addressJSON ?>;
var phoneJSON = <?= $phoneJSON ?>;

</script>

<?= $js_contact_entity ?>
<?= $js_phonelist ?>
<?= $js_addresslist ?>
