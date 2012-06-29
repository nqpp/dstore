<script type="text/template" id="tpl-price-list">
<td class="editable">
  <div class="display"><%=metaKey%></div>
  <div class="edit"><input class="span1" type="text" name="metaKey" value="<%= metaKey%>"></div>
</td>
<td class="editable">
  <div class="display"><%=metaValue%></div>
  <div class="edit"><input class="span1" type="text" name="metaValue" value="<%=metaValue%>"></div>
</td>
<td>
  <div class="display"><a href="javascript:void(0)" class="delete btn btn-mini"><i class="icon-trash"> </i></a></div>
  <div class="edit hide"><a href="javascript:void(0)" class="save btn btn-mini"><i class="icon-ok"> </i></a></div>
</td>
</script>