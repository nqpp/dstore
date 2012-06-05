<div class="row-padded">
  <h1>System Logs</h1>  
</div>

<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Log Name</th>
      <th width="80">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
	<?php if(count($logs) > 0): ?>
	<?php $i = 1; foreach($logs as $log) : ?>
    <tr class="<?= $i%2?'odd':'even' ?>">
      <td>
        <a href="/syslogs/<?=$log ?>.html" class=""><?= $log ?></a>
      </td>
      <td>
        <a class="btn" href="/syslogs/<?=$log ?>.html?delete">
		  <i class="icon-trash"></i> delete
		</a>
      </td>
    </tr>
	<? $i++; endforeach; ?>
	<?php else: ?>
	<tr>
	  <td>No logs to display.</td>
	  <td></td>
	</tr>
	<?php endif; ?>
  </tbody>
</table>
