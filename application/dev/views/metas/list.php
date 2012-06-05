<div class="row row-padded">
  <div class="span-4">
	<h1>Meta</h1>
  </div>
  <a class="btn pull-right" href="/metas.html?new">
	<span class="icon-plus"> </span> Add
  </a>
</div>

<?php
$collapse = $this->input->get('schemaName') ? ' in':'';
?>

<div class="accordion" id="metaAccordion">
  <?php if (count($metas) > 0): ?>
  <?php $i = 1; foreach ($metas as $schema => $keys) : ?>
  <div class="accordion-group">
	<div class="accordion-heading">
	  <a class="accordion-toggle" data-toggle="collapse" href="#collapse<?= $i ?>"><?= $schema ?></a>
	</div>
	<div id="collapse<?= $i ?>" class="accordion-body collapse<?= $collapse ?>">
	  <table class="table table-striped">
		<thead>
		  <tr>
			<th width="100">Meta Key</th>
			<th>Meta Value</th>
			<th width="40">Sort</th>
			<th width="100">
			  <a class="btn" href="/metas/<?= $schema ?>.html?deleteschema"><span class="icon-trash"> </span> Group</a>
			</th>			  
		  </tr>
		</thead>
		<tbody>
		  <?php $j = 1; foreach ($keys as $meta): ?>
		  <tr>
			<td><a href="/metas/<?= $meta->metaID ?>"><?= $meta->metaKey ?></a></td>
			<td><a href="/metas/<?= $meta->metaID ?>"><?= $meta->metaValue ?></a></td>
			<td><?= $meta->sort ?></td>
			<td>
			  <a class="btn" href="/metas/<?= $meta->metaID ?>?delete"><span class="icon-trash"> </span> Delete</a>
			</td>
		  </tr>
		  <?php $j++; endforeach; ?>
		</tbody>
	  </table>
	</div>
  </div>
  <?php $i++; endforeach; ?>
  <?php else : ?>
  <p>No metas to display.</p>
  <?php endif; ?>	

</div>


