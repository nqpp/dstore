<div class="row row-padded">
  
  <div class="span4">
	<h1>Page Permissions</h1>
  </div>

  <div class="span4 pull-right">
	  <a class="btn pull-right" href="/permissions?make"><span class="icon-repeat"> </span> make</a>
  </div>
  
</div>

<?php
$cols = count($adminGroups);
?>

<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th width="200">Page Uri</th>
      <th colspan="<?= $cols ?>">Permissions</th>
    </tr>
    <tr>
      <th class="text-right">Check All</th>
	  <?php if (count($cols)): ?>
	  <?php foreach ($adminGroups as $a): ?>
      <th width="80">
	  <label class="checkbox"><input type="checkbox" class="checkAll" value="<?= $a->metaKey ?>"> <?= $a->metaValue ?></label>
      </th>
	  <?php endforeach; ?>
	  <?php endif; ?>
	  <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($items && isset($items[0]))): ?>
    <?php $i = 1; foreach ($items[0] as $row): ?>
    <tr class="<?= $i%2?'odd':'even' ?>">
      <td><?= $row->page_uri ?></td>
	  <?php if (count($cols)): ?>
	  <?php foreach ($adminGroups as $a): ?>
      <td >
	  <label class="checkbox">
		<input type="checkbox" class="permission parent-<?= $row->parentID ?>" id="bit-<?= $row->permissionID ?>" name="bit[<?= $row->permissionID ?>][]" value="<?= $a->metaKey ?>"<?= $row->bit & (int)$a->metaKey ? ' checked':'' ?>>
		<?= $a->metaValue ?>
	  </label>
      </td>
	  <?php endforeach; ?>
	  <?php endif; ?>
	  <td></td>
    </tr>
	<?php if (isset ($items[$row->permissionID])): ?>
	<?php foreach ($items[$row->permissionID] as $child): ?>
    <tr class="<?= $i%2?'odd':'even' ?>">
      <td><?= $row->page_uri.': '.$child->page_uri ?></td>
	  <?php if (count($cols)): ?>
	  <?php foreach ($adminGroups as $a): ?>
      <td>
	  <label class="checkbox">
		<input type="checkbox" class="permission parent-<?= $child->parentID ?>" id="bit-<?= $child->permissionID ?>" name="bit[<?= $child->permissionID ?>][]" value="<?= $a->metaKey ?>"<?= $child->bit & (int)$a->metaKey ? ' checked':'' ?>>
		<?= $a->metaValue ?>
	  </label>
      </td>
	  <?php endforeach; ?>
	  <?php endif; ?>
	  <td></td>
    </tr>
    <?php $i++; endforeach; ?>
	<?php endif; ?>
    <?php $i++; endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>