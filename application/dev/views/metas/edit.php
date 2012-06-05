<div class="row-padded">
  <h1>Edit Meta Entry</h1>
</div>
  

<form class="form-vertical" action="<?=$_SERVER['REQUEST_URI'] ?>" method="POST">
  
<div class="row row-padded">
  
  <div class="btn-group span4">
	<button class="btn" type="submit" name="save_action"><span class="icon-ok"> </span> save</button>
	<button class="btn" type="submit" name="add_action"><span class="icon-asterisk"> </span> save as new</button>
  </div>
  
  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  <a class="btn" href="/metas.html"><span class="icon-list"> </span> list</a>
	  <a class="btn" href="/metas/<?= $metaID ?>.html?delete"><span class="icon-trash"> </span> delete</a>
	</div>
  </div>
  
</div>

  <fieldset class="well">
	

  <div class="row">
    <div class="span4">
      <label>SchemaName</label>
      <?php if (count($schemaNames)): ?>
      <ul class="unstyled">
      <?php foreach ($schemaNames as $s): ?>
        <li>
		  <label class="radio">
			<input type="radio" name="schemaName" value="<?= $s ?>"<?= $schemaName == $s ? ' checked':'' ?>>
			<a href="/metas.html?schemaName=<?= $s ?>"><?= $s ?></a>
		  </label>
        </li>
      <?php endforeach; ?>
        <li>
		  <label class="radio">
		  <input type="radio" name="schemaName" value="other"> Other, please nominate
		  </label>
		  <input type="text" name="schemaNameNew">
        </li>
      </ul>
      <?php endif; ?>
    </div>

    <div class="span4">
	  <label>MetaKey</label>
	  <input type="text" name="metaKey" value="<?= $metaKey ?>">

	  <label>MetaValue</label>
	  <textarea name="metaValue" class="h-4"><?= $metaValue ?></textarea>
	</div>
      
    <div class="span2">
	  <label for="sort">Sort</label>
	  <select name="sort" class="span1">
		<option value="">--</option>
		<?php foreach(range(1,50) as $i): ?>
		<option value="<?= $i ?>"<?= $i == $sort ? ' selected':'' ?>><?= $i ?></option>
		<?php endforeach; ?>
	  </select>
    </div>
	  
  </div>

  </fieldset>

</form>
