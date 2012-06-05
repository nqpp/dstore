<script type="text/template" id="tpl-address-list">
  <td class="editable">
	<div class="display"><%= metaKey %></div>
	<div class="edit">
	  <select class="span2" name="metaKey">
		<?php if (count($addressTypes)): ?>
  		<option value="">--</option>
		  <?php foreach ($addressTypes as $a): ?>
			<option value="<?= $a->metaKey ?>"<%=(metaKey=='<?= $a->metaKey ?>'?' selected':'') %>><?= $a->metaKey ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</div>
  </td>
  
  <td class="editable">
	<div class="display"><%= metaValue.nl2br() %></div>
	<div class="edit">
	  <textarea class="span2 h5" name="metaValue"><%=metaValue%></textarea>
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
