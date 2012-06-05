<script type="text/template" id="tpl-entity">
  
<div class="editable">
  <label for="name">Product</label>
  <div class="display"><%=name%></div>
  <div class="edit">
	<input type="text" name="name" value="<%=name%>">
  </div>
</div>
  
<div class="editable">
  <label for="code">Code</label>
  <div class="display"><%=code%>&nbsp;</div>
  <div class="edit">
	<div class="span-2">
	  <input type="text" name="code" value="<%=code%>">
	</div>
	<div class="clear">&nbsp;</div>
  </div>
</div>
  
<div class="editable">
  <label for="description">Description</label>
  <div class="display"><%=description.nl2br()%></div>
  <div class="edit">
	<textarea name="description"><%=description%></textarea>
  </div>
</div> 
  
  
<div class="span2">
  <div class="editable">
	<label for="category">Category</label>
	<div class="display"><%=category%>&nbsp;</div>
	<div class="edit">
	  <select name="category">
		<option value=""<%=category == ''?' selected':''%>>--</option>
		<option value="Community"<%=category == 'Community'?' selected':''%>>Community</option>
		<option value="Staff"<%=category == 'Staff'?' selected':''%>>Staff</option>
	  </select>
	</div>
  </div>
</div>

<div class="span-5 last">
  <div class="editable">
	<label for="supplier">Supplier</label>
	<div class="display"><%=supplier%>&nbsp;</div>
	<div class="edit">
	  <select name="suppliersID">
		<option value="">--</option>
		<?php if (count ($suppliers)): ?>
		<?php foreach ($suppliers as $s): ?>
		<option value="<?= $s->supplierID ?>"<%=supplier == '<?= $s->name ?>'?' selected':''%>><?= $s->name ?></option>
		<?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</div>
  </div>
</div>
<div class="clear">&nbsp;</div>

<div class="span-3">
  <div class="editable">
	<label for="cubicWeight">Cubic Weight</label>
	<div class="display"><%=cubicWeight%>&nbsp;</div>
	<div class="edit">
		<input type="text" name="cubicWeight" value="<%=cubicWeight%>">
	</div>
  </div>
</div>

<div class="span-3">
  <div class="editable">
	<label for="deadWeight">Dead Weight</label>
	<div class="display"><%=deadWeight%>&nbsp;</div>
	<div class="edit">
		<input type="text" name="deadWeight" value="<%=deadWeight%>">
	</div>
  </div>
</div>

<div class="span-2 last">
  <div class="editable">
	<label for="perCarton">Ctn Qty</label>
	<div class="display"><%=perCarton%>&nbsp;</div>
	<div class="edit">
		<input type="text" name="perCarton" value="<%=perCarton%>">
	</div>
  </div>
</div>
<div class="clear">&nbsp;</div>
</script> 

