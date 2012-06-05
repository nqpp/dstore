<h1>Add New Client</h1>

<form action="<?=$_SERVER['REQUEST_URI'] ?>" method="post">
  
  <div class="container">
	<div class="span-8">
	  <label for="name">Client Reference</label>
	  <input type="text" name="name">
	  
	  <label for="billingName">Billing Name</label>
	  <input type="text" name="billingName">
	  
	  <label for="abn">ABN</label>
	  <input type="text" name="abn">
	  
	</div>
  
  </div>
  
  <div class="container">

	<button type="submit">Add</button>
	<a href="/clients.html" class="cancel">cancel</a>

  </div>
  
</form>