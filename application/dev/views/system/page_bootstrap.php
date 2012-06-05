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
	
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
		<div class="container">
		  
		  <div class="brand-logo">
			<a class="brand" href="#">DStore</a>&nbsp;
		  </div>
		  
		  <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?= $this->user->firstName() ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="/profile.html">Profile</a></li>
              <li class="divider"></li>
              <li><a href="/logout.html">Sign Out</a></li>
            </ul>
          </div>
		  
		<?php if ($this->user->adminGroup() & 1): ?>
		  
		  <div class="btn-group pull-right">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			  <i class="icon-eye-open"></i> View 
			  <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
			  <li class="<?= ($this->user->pseudoMode() == 'admin'?'active':'not_active') ?>"><a href="/change_view.html?admin">Admin View</a></li>
			  <li class="<?= ($this->user->pseudoMode() == 'manager'?'active':'') ?>"><a href="/change_view.html?manager">Manager View</a></li>
			  <li class="<?= ($this->user->pseudoMode() == 'client'?'active':'') ?>"><a href="/change_view.html?client">Client View</a></li>
			</ul>

		  </div>
		  
		  <?php endif; ?>
		  
		  <div class="nav-collapse">

			<?= $nav ?>
			
		  </div>
		  
		</div>
	  </div>
	</div>
	
	<div class="container">

	  <?=$content ?>
	  
	</div>
	
<script type="text/javascript" src="/scripts/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/scripts/underscore.js"></script>
<script type="text/javascript" src="/scripts/backbone.js"></script>
<script type="text/javascript" src="/scripts/common.js"></script>
<script type="text/javascript" src="/scripts/bootstrap.js"></script>

		
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