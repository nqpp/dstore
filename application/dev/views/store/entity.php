<div class="row ">
  <div class="span8">
	<ul class="breadcrumb">
	  <li>
		<a href="/dashboards.html?client">Dashboard</a> <span class="divider">/</span>
	  </li>
	  <li>
		<a href="/store">Store</a> <span class="divider">/</span>
	  </li>
	  <li>
		<?= $product->name ?>
	  </li>
	</ul>
  </div>

  <div class="span3">
	<div class="btn-group pull-right" id="cart-contain"></div>
  </div>

  <div class="span1">
	<a class="btn pull-right" href="/store.html"><span class="icon-list"> </span> list</a>
  </div>

</div>
<div class="row row-padded">
  <div class="span8">
	<h1><?= $product->name ?></h1>
	<p><?= nl2br($product->description); ?></p>
	<div class="row">
	  <div class="span3">
		<h3>Pricing</h3>
		<table id="price" class="table table-striped table-bordered">
		  <thead>
			<tr>
			  <th width="60">Qty</th>
			  <th>Price ea</th>
			</tr>
		  </thead>
		  <tbody>
			<?php foreach ($prices as $price) : ?>
  			<tr>
  			  <td><?php echo $price->metaKey() ?></td>
  			  <td><?php echo $price->metaValue() ?></td>
  			</tr>
			<?php endforeach; ?>
		  </tbody>
		</table>
	  </div>
	  <div class="span5">
		<h3>Ordering</h3>
		<table id="cart_prep" class="table table-striped table-bordered">
			<thead>
			<tr>
			  <th width="70">Code</th>
			  <th>Colour</th>
			  <th width="70">Qty</th>
			</tr>
		  </thead>
		  <tbody></tbody>
		  <tfoot></tfoot>
		</table>
	  </div>
	</div>
  </div>
  <div class="span4">
	<h3>Gallery</h3>
	<ul id="image" class="thumbnails">
	  <?php foreach ($images as $image) : ?>
  	  <li><a href="/images/<?= substr($image->metaKey(), 0, -4) ?>/2/600/600/<?= $image->metaKey() ?>" rel="gallery" class="fb thumbnail"><img src="/images/<?= substr($image->metaKey(), 0, -4) ?>/2/120/120/<?= $image->metaKey() ?>"></a></li>
	  <?php endforeach; ?>
	</ul>
  </div>
</div>
<?= $js_tpl_subproduct_list ?>
<?= $js_tpl_cart ?>
<script type="text/javascript">
  var productJSON = <?php echo $productJSON ?>;
  var subProductJSON = <?php echo $subProductJSON ?>;
  var cartJSON = <?php echo $cartJSON ?>;
  var productsID = <?php echo $product->productID ?>;
	var userAddresses = <?php echo $userAddresses ?>;
	var userAddressID = <?php echo $userAddressID ?>;
</script>
<script type="text/template" id="tpl-subproduct">
<tr>
  <th colspan="2">Total Qty</th>			  
  <td class="display text-right" id="qty_total"><%=qtyTotal %></td>
</tr>
<tr>
  <th colspan="2">Price ea</th>			  
  <td class="display text-right" id="price_ea"><% if(itemPrice) { %>$<%=itemPrice_formatted %><% } %></td>			  
</tr>
<tr>
  <th colspan="2">Sub Total (ex GST)</th>			  
  <td class="display text-right" id="sub_total"><% if(subtotal) { %>$<%=subtotal_formatted %><% } %></td>			  
</tr>
<tr>
  <th colspan="2">Freight to:
	<div class="btn-group pull-right" id="deliveryAddressID">
		<a class="btn dropdown-toggle" data-toggle="dropdown"><span>Select Address&hellip; </span> <b class="caret"></b></a>
		<ul class="dropdown-menu"></ul>
	</div>
	</th>			  
  <td class="display text-right" id="freight"><% if(freightTotal) { %>$<%=freightTotal_formatted %><% } %></td>			  
</tr>
<tr>
  <th colspan="2">Total GST</th>			  
  <td class="display text-right" id="gst_total"><% if(gst) { %>$<%=gst_formatted %><% } %></td>
</tr>
<tr>
  <th colspan="2">Total inc GST</th>			  
  <td class="display text-right" id="price_total"><% if(total) { %>$<%=total_formatted %><% } %></td>
</tr>
<tr>
  <th colspan="3">
	<button id="add_to_cart" type="button" class="btn btn-success cart pull-right" disabled>Add to Cart</button>
  </th>
<!--  <td></td>-->
</tr>
</script>