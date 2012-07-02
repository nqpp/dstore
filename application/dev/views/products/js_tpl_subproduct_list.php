<script type="text/template" id="tpl-subproduct-list">
<td class="editable">
  <div class="display"><%=code%></div>
  <div class="edit"><input class="span1" type="text" name="code" value="<%=code%>"></div>
</td>
<td class="editable">
  <div class="display"><%=name%></div>
  <div class="edit">
	<input class="span2" type="text" name="name" value="<%=name%>">
	Sort <input class="span1" type="text" name="sort" value="<%=sort%>">
  </div>
</td>
<td>
  <div class="display"><a href="javascript:void(0)" class="delete btn btn-mini"><i class="icon-trash"> </i></a></div>
  <div class="edit hide"><a href="javascript:void(0)" class="save btn btn-mini"><i class="icon-ok"> </i></a></div>
</td>
</script>