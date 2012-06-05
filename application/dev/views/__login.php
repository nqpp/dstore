<!doctype html>
<html>
  <head>
    <title><?= $page_name ?> :: DDD-Store</title>
    <link href="/styles/core.css" type="text/css" rel="stylesheet" />
    <link href="/styles/<?= $theme ?>.css" type="text/css" rel="stylesheet" />
  </head>
  <body>
    <div id="wrap-600">
        
      <div id="login">

		<div class="DWarrior">&nbsp;</div>
        <h2 class="org"><?= $org_name ?></h2>
        <div class="clear">&nbsp;</div>
			<? $msg = $this->session->flashdata('msg');  ?>
			<? if($msg) : ?>
			<p class="<?=$msg['type'] ?>"><?=$msg['text'] ?></p>
			<? endif; ?>

        <form action="<?=$_SERVER['REQUEST_URI'] ?>" method="POST">
          <h2>Login</h2>
          <label for="email_address">Email Address</label>
          <input type="text" name="email_address" value="<?= isset($email_address)?$email_address:'' ?>">
          <label for="password">Password</label>
          <input type="password" name="password">
          <br>
          <button type="submit">Login</button>
        </form>
        
      </div>
      
    </div>
  </body>
</html>