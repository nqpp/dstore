<script type="text/template" id="tpl-list">
  <div class="accordion-heading">
	<a class="accordion-toggle" data-toggle="collapse" href="#collapse<%= cid %>">
	  <div class="row">
		<div class="span2">
		  <%= "#"+orderNumber+' &nbsp; '+createdDate %>
		</div>
		<div class="span6">
		  <%= name+" ["+firstName+" "+lastName+"]" %>
		</div>
		<div class="span2 pull-right">
		  <span class="pull-right label label-<%= status %>"><%= status.toUpperCase() %></span>
		</div>
		<div class="span1 pull-right">
		  <span class="pull-right">$ <%= orderTotal.toFixed(2) %></span>
		</div>
	  </div>
	</a>
  </div>

  <div id="collapse<%= cid %>" class="accordion-body collapse">

	  <div class="span8-5">

		<table class="table table-striped table-bordered orderProducts">
		  <thead>
			<tr>
			  <th>Name</th>
			  <th width="40"><span class="pull-right">Qty</span></th>
			  <th width="70"><span class="pull-right">Sub Total</span></th>
			  <th width="60"><span class="pull-right">Freight</span></th>
			  <th width="40"><span class="pull-right">GST</span></th>
			  <th width="60"><span class="pull-right">Total</span></th>
			</tr>
		  </thead>
		  <tbody></tbody>
		  <tfoot>
			<tr>
			  <td></td>
			  <td colspan="4">
				<span class="pull-right"><strong>Order Total</strong></span>
			  </td>
			  <td><span class="pull-right"><strong>$ <%= orderTotal.toFixed(2) %></strong></span></td>
			</tr>
		  </tfoot>
		</table>

	  </div>

	  <div class="span3">
		<div class="well">

		  <h5>Deliver To</h5>
		  <div class="orderAddress"></div>
		  
		  <div class="divider">&nbsp;</div>
		  
		  <h5>Order Status</h5>
		  <div class="btn-group">
			<a class="btn btn-<%= status %>"><%= status.toUpperCase() %></a>
			<a class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
			<ul class="dropdown-menu status">
			  <?php if (count($orderStatusTypes)): ?>
				<?php foreach ($orderStatusTypes as $ost): ?>
				  <li class="<%= '<?= $ost->metaKey ?>' == status ? 'active':'' %>"><a href="#" title="<?= $ost->metaKey ?>"><?= $ost->metaValue ?></a></li>
				<?php endforeach; ?>
			  <?php endif; ?>
			</ul>

		  </div>

		</div>
	  </div>


  </div>
  </div>

</script>
