<script type="text/template" id="tpl-entity">
  <label>First Name</label>
  <input type="text" name="firstName" value="<%=firstName%>">
  
  <label>Last Name</label>
  <input type="text" name="lastName" value="<%=lastName%>">
  
  <label>Email</label>
  <input type="text" name="email" value="<%=email%>">
<% 
var plc = passwd == '' ? 'Type to set the password':'Type to reset the password';
%>
  <label>
	Password
	<%= passwd == '' ? '<span class="label label-important">Not Currently Set</span>':'' %>
  </label>
  <input type="text" name="password" value="" placeholder="<%= plc %>">
  
  <label>Confirm Password</label>
  <input type="text" name="confirm_password" value="" placeholder="Type to confirm the password">
  
</script>