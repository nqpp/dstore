<!doctype html>
<html>
  <head>
    <title><?= $page_name ?> :: D-Store</title>
    <link href="/styles/core.css" type="text/css" rel="stylesheet" />
    <link href="/styles/<?= $theme ?>.css" type="text/css" rel="stylesheet" />
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
          <p>Welcome, <strong><a href="/users/profile.html"><?= User::$firstName ?></a>.</strong> &nbsp;&nbsp;<a href="/logout.html">Logout</a></p>
        </div>
      </div>
      <ul id="nav">
		<li class="first"><a href="/products.html">Products</a></li>
		<li class=""><a href="/clients.html">Clients</a></li>
        <li><a href="/orders.html">Orders</a></li>
        <?php if (User::$adminGroup & 1): ?>
        <li class="last"><a href="/admin_dashboard.html">Admin</a>
          <ul>
            <li><a href="/metas.html">Metadata</a></li>
            <li><a href="/users.html">Users</a></li>
            <li><a href="/permissions.html">Permissions</a></li>
            <li><a href="/syslogs.html">System Logs</a></li>
          </ul>
        </li>
        <?php endif; ?>
      </ul>

      <div id="page">
		<?php $flash = $this->session->flashdata('msg'); ?>
		<?php if ($flash): ?>
		<div class="flash <?= $flash['type'] ?>"><?= $flash['text'] ?></div>
		<?php endif; ?>
				
        <?=$content ?>
      </div>

      <div id="foot">
      </div>
	  
    </div>
    <script type="text/javascript" src="/scripts/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="/scripts/underscore.js"></script>
    <script type="text/javascript" src="/scripts/backbone.js"></script>
    <script type="text/javascript" src="/scripts/common.js"></script>

	<?php if (isset($scriptFiles) && count($scriptFiles)): ?>
	<?php foreach ($scriptFiles as $file): ?>
	<script type="text/javascript" src="<?= $file ?>"></script>
	<?php endforeach; ?>
	<?php endif; ?>
		
<?php if ($flash): ?>
<script type="text/javascript">
  flash = true;
</script>
<?php endif; ?>
  
<?php if (isset($scripts)): ?>
<script type="text/javascript">
<?= $scripts ?>

</script>
  <?php endif; ?>
  </body>
</html>