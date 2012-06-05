<div class="span-3">
  <h1>Clients</h1>
</div>
  
<div class="span-4 rel">
  <a href="/clients?new" class="toglDialg icon32 plus" title="add new client">Add Client</a>
  
  <div class="jsHide dialog">
	
	<form action="<?=$_SERVER['REQUEST_URI'] ?>?new" method="POST" id="list" name="new-client">
	  <div class="span-7">
		
	  <div class="span-6"><input type="text" name="name" /></div>
	  <div class="span-1 last"><button type="submit">Add</button></div>
	  <div class="clear"></div>
	  </div>
	</form>
	
  </div>
</div>


<div class="container">
  <table>
	<thead>
	  <tr>
		<th width="200">Client</th>
		<th></th>
	  </tr>
	</thead>
	<tbody>
	<? if($clients): ?>
	<? foreach($clients as $row) : ?>
	  <tr>
		<td>
		  <a href="clients/<?= $row->clientID() ?>"><?= $row->name(); ?></a>
		</td>
		<td>
		  <a href="clients/<?= $row->clientID() ?>?delete">delete</a>		  
		</td>
	  </tr>
	<? endforeach; ?>
	<? endif; ?>
	</tbody>
  </table>

</div>


<script type="text/template" id="tpl-client-list">
<tr>
  <td>
	<a href="clients/<%=clientID%>"><%=name%></a>
  </td>
  <td>
	<a href="clients/<%clientID%>?delete">delete</a>		  
  </td>
</tr>
</script>