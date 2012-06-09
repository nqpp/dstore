
<form class="" action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">

  <div class="DWarrior">&nbsp;</div>
  <h2 class="org"><?= $org_name ?></h2>

  <div class="clear">&nbsp;</div>
  <? $msg = $this->session->flashdata('msg'); ?>
  <? if ($msg) : ?>
    <p class="alert-<?= $msg['type'] ?>"><?= $msg['text'] ?></p>
  <? endif; ?>

  <div class="row">

	<div class="span3">

	  <h3>Login</h3>
	  <label for="email_address">Email Address</label>
	  <input class="span3" type="text" name="email_address" value="<?= isset($email_address) ? $email_address : '' ?>">

	  <label for="password">Password</label>
	  <input type="password" name="password">

	</div>

  </div>

  <div class="row">

	<div class="span3">
	  <div class="row">
		<p class="help-block pull-right"><a href="/forgot_password">Forgot password?</a></p>
	  </div>
	  <button class="btn pull-right" type="submit">Login</button>
	</div>


	<div class="span3">
	  <p>Don't have a login?</p>
	  <a class="btn" href="/request_login">Request Login</a>
	</div>

  </div>

</form>
