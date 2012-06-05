<div class="row-padded">
  <h1>Add New User</h1>
</div>
  
<form class="form-vertical" action="<?=$_SERVER['REQUEST_URI'] ?>" method="POST">

<div class="row-padded">

	<button class="btn" type="submit" name="save_action"><span class="icon-ok"> </span> save</button>

	<div class="span4 pull-right">
	  <a class="btn pull-right" href="/users.html"><span class="icon-list"> </span> list</a>
	</div>

</div>
  
<fieldset class="well">
  
  <div class="span4">

    <label for="firstName">First Name</label>
    <input type="text" name="firstName" value="">

    <label for="lastName">Last Name</label>
    <input type="text" name="lastName" value="">

    <label for="email">Email Address</label>
    <input type="text" name="email" value="">

  </div>

  <div class="span4">

    <label for="password">Password</label>
    <input type="text" name="password" value="">

    <label for="confirm_password">Confirm</label>
    <input type="text" name="confirm_password" value="">

  </div>

  <div class="span3">
    <label for="adminGroup">Group</label>
    <?php if (count($groupMeta)): ?>
    <?php foreach ($groupMeta as $g): ?>
    <label class="radio">
      <input type="radio" name="adminGroup" value="<?= $g->metaKey ?>"> <?= $g->metaValue ?>
    </label>
    <?php endforeach; ?>
    <?php else: ?>
    <p>No Groups Available</p>
    <?php endif; ?>
  </div>
</fieldset>


</form>