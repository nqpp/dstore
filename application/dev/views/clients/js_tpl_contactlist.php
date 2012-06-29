<script type="text/template" id="tpl-contact">
  <div class="accordion-heading">
	<a class="accordion-toggle" data-toggle="collapse" href="#collapse<%=id%>">
	  <div class="row">
	  <div class="span3">
		<%=firstName+' '+lastName%>
	  </div>
	  <div class="span4"><%=(jobTitle ? jobTitle:'')+(department ? ' - '+department:'')%>
	  </div>
	  </div>
	</a>
  </div>
  <div id="collapse<%=id%>" class="accordion-body collapse">
	  
	  <div class="span1 pull-right">
		<div class="btn-group">
		  <a class="btn btn-mini pull-right" href="/contacts/<%=userID%>" title="edit">
			<i class="icon-edit"> </i>
		  </a>
		  <a class="btn btn-mini pull-right contact-delete" href="#" title="delete">
			<i class="icon-trash"> </i>
		  </a>
		</div>
	  </div>
	  
	  <div class="span3">
		<h5>Email</h5>
		<a href="#"><%=email%></a>
	  </div>
	
	  <div class="span3">
		<h5>Phone</h5>
		<div id="contact-phone-<%=id%>">
		<table>
		<%
		if (contactPhones != 'undefined') {
		  _.each(contactPhones.models,function(m) {
		%>
		<tr>
		  <td width="40">
		  <%= m.get('metaKey') %>
		  </td>
		  <td>
		  <%= m.get('metaValue') %>
		  </td>
		</tr>
		<%
		  });
		}
		%>
		</table>
		</div>
	  </div>
	  
	<div class="row row-padded">&nbsp;</div>
	
  </div>
</script>