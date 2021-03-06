<script type="text/template" id="tpl-email-list">
  <td class="editable">
	<div class="display"><%= metaKey %></div>
	<div class="edit">
	  <select class="span1-5 change-update" name="metaKey">
		<?php if (count($emailTypes)): ?>
  		<option value="">--</option>
		  <?php foreach ($emailTypes as $a): ?>
			<option value="<?= $a->metaKey ?>"<%= metaKey == '<?= $a->metaKey ?>'?' selected':'' %>><?= $a->metaKey ?></option>
		  <?php endforeach; ?>
		<?php endif; ?>
	  </select>
	</div>
  </td>
  <td class="editable">
	<div class="display"><%= metaValue %></div>
	<div class="edit">
	  <input class="span2" type="text" class="" name="metaValue" value="<%=metaValue%>">
	</div>
  </td>
  <td>
	<div class="display">
	  <a class="btn btn-mini delete" href="#" title="delete this row"><i class="icon-trash"> </i></a>
	</div>
	<div class="edit hide">
	  <a class="btn btn-mini save" href="#" title="save"><i class="icon-ok"> </i></a>
	</div>
  </td>
</script>
