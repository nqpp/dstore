<div class="row-padded">
  <h1>Add Metadata Entry</h1>
</div>

<form class="form-vertical" action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">

  <div class="row-padded">

	<button class="btn" type="submit" name="save_action"><span class="icon-ok"> </span> save</button>

	<div class="span4 pull-right">
	  <a class="btn pull-right" href="/metas.html"><span class="icon-list"> </span> list</a>
	</div>

  </div>

  <fieldset class="well">


	<div class="span4">
      <label>SchemaName</label>
	  <?php if (count($schemaNames)): ?>
  	  <ul class="unstyled">
		  <?php foreach ($schemaNames as $s): ?>
			<li>
			  <label class="radio">
				<input type="radio" name="schemaName" value="<?= $s ?>"><?= $s ?>
			  </label>
			</li>
		  <?php endforeach; ?>
  		<li>
  		  <label class="radio">
  			<input type="radio" name="schemaName" value="other">Other, please nominate
  		  </label>
  		  <input type="text" name="schemaNameNew">
  		</li>
  	  </ul>
	  <?php endif; ?>
    </div>

    <div class="span4">

	  <label>MetaKey</label>
	  <input type="text" name="metaKey">

	  <label>MetaValue</label>
	  <textarea name="metaValue" class=""></textarea>

    </div>

	<div class="row row-padded">
	</div>

  </fieldset>

</form>
