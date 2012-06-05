<script type="text/template" id="tpl-supplier_freights">
<td><%=code%></td>
<td class="editable">
  <div class="display"><%=baseRate%></div>
  <div class="edit input-prepend">
	<span class="add-on">$</span><input class="span1" type="text" name="baseRate" value="<%=baseRate%>">
  </div>
</td>
<td class="editable">
  <div class="display"><%=kgRate%></div>
  <div class="edit input-prepend">
	<span class="add-on">$</span><input class="span1" type="text" name="kgRate" value="<%=kgRate%>"></div>
</td>
<td class="editable">
  <div class="display"><%=minCharge%></div>
  <div class="edit input-prepend">
	<span class="add-on">$</span><input class="span1" type="text" name="minCharge" value="<%=minCharge%>"></div>
</td>
<td class="editable">
  <div class="display"><%=flatRate%></div>
  <div class="edit input-prepend">
	<span class="add-on">$</span><input class="span1" type="text" name="flatRate" value="<%=flatRate%>"></div>
</td>
<td>
  <div class="display">&nbsp;</div>
  <div class="edit hide"><a href="javascript:void(0)" class="save">o</a></div>
</td>
</script>