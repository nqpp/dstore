<script type="text/template" id="tpl-entity">
  <label>First Name</label>
  <input type="text" name="firstName" value="<%=firstName%>">
  
  <label>Last Name</label>
  <input type="text" name="lastName" value="<%=lastName%>">
  
  <label>Email</label>
  <input type="text" name="email" value="<%=email%>">
<% 
var plc = pwSet ? 'Type to reset the password':'Type to set the password';
%>
  <label>
	Password
	<%= pwSet ? '':'<span class="label label-important">Not Currently Set</span>' %>
  </label>
  <input type="text" class="password" name="password" value="" placeholder="<%= plc %>">
  
  <label>Confirm Password</label>
  <input type="text" class="password" name="confirm_password" value="" placeholder="Type to confirm the password">
  
</script>