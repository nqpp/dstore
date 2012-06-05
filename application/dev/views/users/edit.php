<div class="row row-padded">
  
  <div class="span4">
	<h1 class="">Edit User</h1>
  </div>
  
  <div class="span3 pull-right">
	<h2 class="pull-right"><?= $firstName.' '.$lastName ?></h2>	
  </div>
  
</div>

<form class="form-vertical" action="<?=$_SERVER['REQUEST_URI'] ?>" method="POST">
  
<div class="row row-padded">
  
  <div class="span4">
	<button type="submit" class="btn"><i class="icon-ok"></i> Save</button>
  </div>
  
  <div class="btn-group pull-right">

	<a class="btn" href="/users.html">
	  <i class="icon-list"></i> list
	</a>

	<a class="btn" href="/users/<?= $userID ?>.html?delete">
	  <i class="icon-trash"></i> delete
	</a>
  </div>

</div>
  

<fieldset class="well">
  
  <div class="row">
	
	<div class="span4">

	  <label for="firstName">First Name</label>
	  <input type="text" name="firstName" class="" value="<?= $firstName ?>">

	  <label for="lastName">Last Name</label>
	  <input type="text" name="lastName" class="" value="<?= $lastName ?>">

	  <label for="email">Email Address</label>
	  <input type="text" name="email" value="<?= $email ?>">

	</div>

	<div class="span4">

	  <label for="password">Password <?= $passwd ? '':'<span class="label label-important">&nbsp;Not Set&nbsp;</span>' ?></label>
	  <input type="text" name="passwd" value="">

	  <label for="confirm_passwd">Confirm Password</label>
	  <input type="text" name="confirm_passwd" value="">

	</div>

	<div class="span3">

	  <label for="adminGroup">Group</label>
	  <?php if (count($groupMeta)): ?>
	  <?php foreach ($groupMeta as $g): ?>
	  <label class="radio">
		<input type="radio" name="adminGroup" value="<?= $g->metaKey ?>"<?= $g->metaKey == $adminGroup ? ' checked':'' ?>> <?= $g->metaValue ?>
	  </label>
	  <?php endforeach; ?>
	  <?php else: ?>
	  <p>No Groups Available</p>
	  <?php endif; ?>

	</div>

  </div>


</fieldset>
</form>
