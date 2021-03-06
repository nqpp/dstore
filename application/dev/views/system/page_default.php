<!doctype html>
<html>
  <head>
    <title><?= $page_name ?> :: D-Store</title>
    <link href="/styles/<?= $theme ?>/core.css" type="text/css" rel="stylesheet" />
    <?php if (isset($cssFiles) && count($cssFiles)): ?>
	<?php foreach ($cssFiles as $file): ?>
	<link type="text/css" href="<?= $file ?>" rel="stylesheet" />
	<?php endforeach; ?>
	<?php endif; ?>
  </head>
  <body>
    <div id="wrap">
      <div id="head">
        <div class="span-18">
          <h2 class="org"><?= $org_name ?></h2>
        </div>
        <div class="span-6 last">
          <p>Welcome, <strong><a href="/users?profile"><?= User::$firstName ?></a>.</strong> &nbsp;&nbsp;<a href="/logout.html">Logout</a></p>
        </div>
      </div>

	  <?= $nav ?>

      <div id="page">
        <?=$content ?>
      </div>

      <div id="foot"></div>
	  
    </div>

		
<?php if (isset($jsFiles) && count($jsFiles)): ?>
<?php foreach ($jsFiles as $file): ?>
<script type="text/javascript" src="<?= $file ?>"></script>
<?php endforeach; ?>
<?php endif; ?>
  
<?php if (isset($jsScripts)): ?>
<script type="text/javascript">
<?= $jsScripts ?>

</script>
<?php endif; ?>

  </body>
</html>