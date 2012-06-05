<script type="text/template" id="tpl-contact-entity">
  <tr>
	<th width="80">First Name</th>
	<td class="handle">
	  <div class="display"><%=firstName%></div>
	  <div class="edit"><input type="text" name="firstName" value="<%=firstName%>"></div>
	</td>
  </tr>
  <tr>
	<th>Last Name</th>
	<td class="handle">
	  <div class="display"><%=lastName%></div>
	  <div class="edit"><input type="text" name="lastName" value="<%=lastName%>"></div>
	</td>
  </tr>
  <tr>
	<th>Email</th>
	<td class="handle">
	  <div class="display"><%=email%></div>
	  <div class="edit"><input type="text" name="email" value="<%=email%>"></div>
	</td>
  </tr>
  <tr>
	<th>Password</th>
	<td class="handle">
	  <div class="display">
	  <%= password == '' ? '<span class="red">Not Currently Set</span>':'<span class="green">Reset</span>' %>
	  </div>
	  <div class="edit"><input type="password" name="password" value=""></div>
	</td>
  </tr>
  <tr>
	<th></th>
	<td class="handle">
	  <div class="display">Confirm</div>
	  <div class="edit"><input type="password" name="confirm_password" value=""></div>
	</td>
  </tr>

</script>