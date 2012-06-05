<script type="text/template" id="tpl-list">
<td class="editable">
  <div class="display"><%=zone%>&nbsp;</div>
  <div class="edit">
	<select name="zonesID" class="span1">
	  <option value="">--</option>
	  <?php if (count ($zones)): ?>
	  <?php foreach ($zones as $z): ?>
	  <option value="<?= $z->zoneID ?>"<%=zonesID == '<?= $z->zoneID ?>'?' selected':''%>><?= $z->code ?></option>
	  <?php endforeach; ?>
	  <?php endif; ?>
	</select>
  </div>
</td>
<td class="editable">
  <div class="display"><%=postcode%></div>
  <div class="edit">
	<input type="text" name="postcode" class="span1" value="<%=postcode%>">
  </div>
</td>
<td class="editable">
  <div class="display"><%=suburb%></div>
  <div class="edit">
	<input type="text" name="suburb" class="span3" value="<%=suburb%>">
  </div>
</td>
<td>
  <div class="display">
	<a href="#" class="delete">x</a>
  </div>
  <div class="edit hide">
	<a href="#" class="save">o</a>
  </div>
</td>
</script>