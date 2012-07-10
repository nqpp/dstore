
<div class="DWarrior">&nbsp;</div>
<h2 class="org"><?= $org_name ?></h2>

<form class="" action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">

  <h3>Oops, I Forgot My Password</h3>
  <div class="row row-padded">
	<div class="span3">
	  <p>Enter your email address and a new password will be emailed to you.</p>
	</div>

  </div>
  <div class="row">

	<div class="span3">

	  <label for="email_address">Email Address</label>

	  <input class="span3" type="text" name="email_address" value="">

	</div>

  </div>

  <div class="row row-padded">

	<div class="span3">
	  <button class="btn pull-right" type="submit">Get Password</button>
	</div>

  </div>

  <div class="row">
	<div class="span4">
	  <p>I don't have a login - 
		<a class="" href="/request_login">Request Login</a>
	  </p>
	</div>
  </div>

  <div class="row">
	<div class="span4">
	  <p>I need to go back to login - 
		<a class="" href="/login">Login</a>
	  </p>
	</div>
  </div>


</form>
