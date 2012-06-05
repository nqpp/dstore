<script type="text/template" id="tpl-client-contact-detail">
  <tr>
	<th width="80">CONTACT</th>
	<td class="">
	  <a href="/client_contacts/<%=userID%>">Edit</a>
	</td>
  </tr>
  <tr>
	<th>First Name</th>
	<td class="handle">
	  <div class="display"><%=firstName%></div>
	  <div class="edit"><input type="text" name=firstName" value="<%=firstName%>"></div>
	</td>
  </tr>
  <tr>
	<th>Last Name</th>
	<td class="handle">
	  <div class="display"><%=lastName%></div>
	  <div class="edit"><input type="text" name=lastName" value="<%=lastName%>"></div>
	</td>
  </tr>
  <tr>
	<th>Email</th>
	<td class="handle">
	  <div class="display"><%=email%></div>
	  <div class="edit"><input type="text" name=firstName" value="<%=email%>"></div>
	</td>
  </tr>
  <tr>
	<th>Password</th>
	<td><%= password == '' ? 'Not Currently Set':'Is Set' %></td>
  </tr>

</script>