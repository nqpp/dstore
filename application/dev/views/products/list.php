<div id="productApp">
  
  <div class="row row-padded">
	<div class="span4">
	  <h1>Products</h1>
	</div>

	<div class="span4 pull-right">
	  <div class="input-prepend pull-right">
		<span class="add-on">Add new</span><input class="span3" type="text" name="name" id="new-product" placeholder="Type name and press Enter"/>
	  </div>
	</div>
	
  </div>


  <ul class="thumbnails" id="products"></ul>

</div>

<script type="text/javascript">
  var productsJSON = <?= $productsJSON ?>;

</script>

<?= $js_tpl_list ?>
