<script type="text/template" id="tpl-email-list">
<td class="handle">
<div class="display"><%= metaKey %></div>
<div class="edit">
<select name="metaKey">
<?php if (count($emailTypes)): ?>
<option value="">--</option>
<?php foreach ($emailTypes as $a): ?>
<option value="<?= $a->metaKey ?>"<%= metaKey == '<?= $a->metaKey ?>'?' selected':'' %>><?= $a->metaKey ?></option>
<?php endforeach; ?>
<?php endif; ?>
</select>
</div>
</td>
<td class="handle">
<div class="display"><%= metaValue %></div>
<div class="edit">
<input type="text" class="" name="metaValue" value="<%=metaValue%>">
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
