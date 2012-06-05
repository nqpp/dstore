<script type="text/template" id="tpl-address-list">
  <td class="editable">
	<div class="display"><%= type %>&nbsp;</div>
	<div class="edit">
	  <select class="span2 change-update" name="type">
		<?php if (count($addressTypes)): ?>
  		<option value="">--</option>
		  <?php foreach ($addressTypes as $a): ?>
			<option value="<?= $a->metaKey ?>"<%=(type=='<?= $a->metaKey ?>'?' selected':'') %>><?= $a->metaKey ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</div>
  </td>

  <td class="editable">
	<div class="display"><%= address+'<br>'+city+'<br>'+state+' '+postcode %></div>
	<div class="edit">
	  <label for="address">Address</label>
	  <input type="text" name="address" value="<%=address%>">
	  <label for="city">City</label>
	  <input type="text" name="city" value="<%=city%>">
	  <label for="state">State</label>
	  <select class="span2" name="state">
		<?php if (count($states)): ?>
  		<option value="">--</option>
		  <?php foreach ($states as $s): ?>
			<option value="<?= $s->metaKey ?>"<%=(state=='<?= $s->metaKey ?>'?' selected':'') %>><?= $s->metaKey ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>

	  </select>
	  <label for="postcode">Postcode</label>
	  <input class="span2" type="text" name="postcode" value="<%=postcode%>">
	</div>
  </td>
  <td>
	<div class="display">
	  <a href="javascript:void(0)" class="delete">x</a>
	</div>
	<div class="edit">
	  <a href="javascript:void(0)" class="save">o</a>
	</div>
  </td>
</script>


