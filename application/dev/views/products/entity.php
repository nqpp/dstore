<div class="row row-padded">

  <div class="span4">
	<h1>Edit Product</h1>
  </div>

  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  <a class="btn" href="/products.html"><span class="icon-list"> </span> list</a>
	  <a class="btn" href="/products/<?= $this->m_products->id ?>.html?delete"><span class="icon-trash"> </span> delete</a>
	</div>

  </div>

</div>

<div class="row row-padded">

  <div id="product"></div>

</div>


<div class="row">

  <div class="span4">

	<h3>Product Images</h3>
	<div id="image-uploader" class=""></div>
	<div class="help-block">Double-click an image to delete</div>
	<ul class="thumbnails" id="image"></ul>

  </div>

  <div class="span4">

	<h3>Price Points</h3>
	<table id="price" class="table table-striped table-bordered">
	  <thead>
		<tr>
		  <th width="60">Qty</th>
		  <th>Price per Unit</th>
		  <th width="20">
			<a href="javascript:void(0);" class="addOne">+</a>
		  </th>
		</tr>
	  </thead>
	  <tbody></tbody>
	</table>

  </div>

  <div class="span4">

	<h3>Colour Options</h3>
	<table id="subproduct" class="table table-striped table-bordered">
	  <thead>
		<tr>
		  <th width="60">Code</th>
		  <th>Colour</th>
		  <th width="20">
			<a href="javascript:void(0);" class="addOne">+</a>
		  </th>
		</tr>
	  </thead>
	  <tbody></tbody>
	</table>


  </div>

</div>


<script type="text/javascript">
  var productJSON = <?= $productJSON ?>;
  var subProductJSON = <?= $subProductJSON ?>;
  var priceJSON = <?= $priceJSON ?>;
  var imageJSON = <?= $imageJSON ?>;

</script>

<?= $js_tpl_entity ?>
<?= $js_tpl_price_list ?>
<?= $js_tpl_subproduct_list ?>
<?= $js_tpl_image_list ?>
