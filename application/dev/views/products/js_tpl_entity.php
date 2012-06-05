<script type="text/template" id="tpl-entity">
  
<div class="span3">

  <label for="name">Product</label>
  <input class="span3" type="text" name="name" value="<%=name%>">

  <label for="code">Code</label>
  <input class="span3" type="text" name="code" value="<%=code%>">

</div>
  
<div class="span5">
  
  <label for="description">Description</label>
  <textarea class="span5 h4" name="description"><%=description%></textarea>

</div>
  

  
<div class="span2">
  <label for="category">Category</label>
  <select class="span2" name="category">
	<option value=""<%=category == ''?' selected':''%>>--</option>
	<option value="Community"<%=category == 'Community'?' selected':''%>>Community</option>
	<option value="Staff"<%=category == 'Staff'?' selected':''%>>Staff</option>
  </select>
</div>

<div class="span2">
  <label for="supplier">Supplier</label>
  <select class="span2" name="suppliersID">
	<option value="">--</option>
	<?php if (count ($suppliers)): ?>
	<?php foreach ($suppliers as $s): ?>
	<option value="<?= $s->supplierID ?>"<%=supplier == '<?= $s->name ?>'?' selected':''%>><?= $s->name ?></option>
	<?php endforeach; ?>
	<?php endif; ?>
  </select>
</div>

<div class="span4">
  <div class="span">
	<label for="cubicWeight">Cubic Weight</label>
	<input class="span1" type="text" name="cubicWeight" value="<%=cubicWeight%>">
  </div>

  <div class="span">
	<label for="deadWeight">Dead Weight</label>
	<input class="span1" type="text" name="deadWeight" value="<%=deadWeight%>">
  </div>

  <div class="span">
	<label for="perCarton">Ctn Qty</label>
	<input class="span1" type="text" name="perCarton" value="<%=perCarton%>">
  </div>
</div>

</script> 

