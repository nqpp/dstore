<div class="row row-padded">
  <div class="span-4">
	<h1>Users</h1>  
  </div>
  <a class="btn pull-right" href="/users.html?new">
	<span class="icon-plus"> </span> Add
  </a>
</div>

<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th width="150">User</th>
      <th width="150">Group</th>
      <th>Email</th>
      <th width="80">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
	<?php if(count($users)): ?>
	<?php foreach($users as $user) : ?>
    <tr>
      <td>
        <a href="/users/<?=$user->userID ?>.html" class=""><?= $user->firstName.' '.$user->lastName ?></a>
      </td>
      <td><?= $user->groupName ?></td>
      <td><?= $user->email?></td>
      <td>
        <a class="btn" href="/users/<?=$user->userID ?>.html?delete"><span class="icon-trash"> </span> Delete</a>
      </td>
    </tr>
	<?php endforeach; ?>
	<?php else : ?>
	<tr>
	  <td colspan="4">No users to display.</td>
	</tr>
	<?php endif; ?>
  </tbody>
</table>
