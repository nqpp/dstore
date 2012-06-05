<script type="text/template" id="tpl-entity">
<div class="handle">
  <label for="postcode">Postcode</label>
  <div class="display"><%=postcode%>&nbsp;</div>
  <div class="edit">
	<input type="text" name="postcode" value="<%=postcode%>">
  </div>
</div>
  
<div class="handle">
  <label for="suburb">Suburb</label>
  <div class="display"><%=suburb%></div>
  <div class="edit">
	<input type="text" name="suburb" value="<%=suburb%>">
  </div>
</div>
  
<div class="span-2">
  <div class="handle">
	<label for="zone">Zone</label>
	<div class="display"><%=zone%>&nbsp;</div>
	<div class="edit">
		<select name="zonesID">
		  <option value="">--</option>
		  <?php if (count ($zones)): ?>
		  <?php foreach ($zones as $z): ?>
		  <option value="<?= $z->zoneID ?>"<%=zonesID == '<?= $z->zoneID ?>'?' selected':''%>><?= $z->code ?></option>
		  <?php endforeach; ?>
		  <?php endif; ?>
		</select>
	</div>
  </div>
</div>
  <div class="clear">&nbsp;</div>
  
</script> 

