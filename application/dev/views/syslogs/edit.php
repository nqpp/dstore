<div class="row row-padded">

  <div class="span4">
	<h1>System Log</h1>
  </div>

  <div class="span4 pull-right">
	<h2 class="pull-right"><?= $filename ?></h2>
  </div>

</div>

<div class="row row-padded">

  <div class="btn-group pull-right">

	<a class="btn" href="/syslogs.html">
	  <i class="icon-list"></i> list
	</a>

	<a class="btn" href="/syslogs/<?= $filename ?>.html?delete">
	  <i class="icon-trash"></i> delete
	</a>
  </div>

</div>

<div class="container">
  <textarea class="span12" name="filedata" class="span12" style="height:600px"><?= $log ?></textarea>
</div>

