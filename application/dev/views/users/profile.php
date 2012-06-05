<h1>Edit Profile - <?=$firstName.' '.$lastName ?></h1>
<form action="<?=$_SERVER['REQUEST_URI'] ?>" method="POST">
<div class="container">
  <div class="span-8">

    <label for="firstName">First Name</label>
    <input type="text" name="firstName" value="<?= $firstName ?>">

    <label for="lastName">Last Name</label>
    <input type="text" name="lastName" value="<?= $lastName ?>">

    <label for="password">Password <?= $password ? '':'<span class="error">&nbsp;Not Set&nbsp;</span>' ?></label>
    <input type="password" name="password" value="">

    <label for="confirm_password">Confirm</label>
    <input type="password" name="confirm_password" value="">

  </div>

  <div class="span-8">

    <label for="email">Email Address</label>
    <input type="text" name="email" value="<?= $email ?>">

    <label for="emailSignature">Email Signature</label>
    <textarea name="emailSignature" class="h-12"><?= isset($emailSignature)?$emailSignature:'' ?></textarea>

  </div>


</div>


<div class="container">
  <button type="submit">Save</button>
  <a href="/dashboardf.html" class="cancel">cancel</a>
  <div class="clear">&nbsp;</div>

</div>

</form>