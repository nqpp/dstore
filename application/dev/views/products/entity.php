<div class="row row-padded">

  <div class="span4">
	<h1>Edit Product</h1>
  </div>

  <div class="span4 pull-right">
	<div class="btn-group pull-right">
	  <a class="btn" href="/products/<?= $this->m_products->id ?>.html?delete"><i class="icon-trash"> </i> delete</a>

	  <a class="btn" href="/products.html"><i class="icon-list"> </i> list</a>
	  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
		<i class="caret"> </i>
	  </a>
	  <ul class="dropdown-menu">
		<?php if (count($products)): ?>
		<?php $f = true; foreach ($products as $cat=>$items): ?>
		<?php if (!$f): ?>
		<li class="divider"></li>
		<?php endif; ?>
		<li><div class="span2"><strong><?= $cat ?></strong></div></li>
		<?php foreach ($items as $s): ?>
		<li><a href="/products/<?= $s->productID ?>"><?= $s->name ?></a></li>
		<?php endforeach; ?>
		<?php $f = false; endforeach; ?>
		<?php endif; ?>
	  </ul>
	  
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
			<a href="javascript:void(0);" class="addOne btn btn-mini"><i class="icon-plus"> </i></a>
		  </th>
		</tr>
	  </thead>
	  <tbody></tbody>
	  <tfoot>
		<tr>
		  <td colspan="3">
			<span class="pull-right">
			  <a class="btn btn-small" href="#" id="save_sort"><i class="icon-list-alt"> </i> Sort</a>
			</span>
		  </td>
		</tr>
	  </tfoot>
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
			<a href="javascript:void(0);" class="addOne btn btn-mini"><i class="icon-plus"> </i></a>
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
