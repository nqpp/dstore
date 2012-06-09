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

  <div class="span4 pull-right">
	<a class="btn pull-right" href="/store.html"><span class="icon-list"> </span> list</a>
  </div>
  
</div>

<div class="row row-padded">

  <div class="span8">
	<div id="product"></div>
	
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
		  <tbody></tbody>
		</table>
	  </div>
	  
	  <div class="span5">
		<h3>Ordering</h3>
		<table id="cart_prep" class="table table-striped table-bordered">
		  <thead>
			<tr>
			  <th width="50">Code</th>
			  <th width="120">Colour</th>
			  <th>Qty</th>
			</tr>
		  </thead>
		  <tbody></tbody>
		  <tfoot>
			<tr>
			  <th colspan="2" class="text-right">Total Qty</th>			  
			  <td class="display" id="qty_total"></td>			  
			</tr>
			<tr>
			  <th colspan="2" class="text-right">Price ea</th>			  
			  <td class="display" id="price_ea"></td>			  
			</tr>
			<tr>
			  <th colspan="2" class="text-right">Sub Total</th>			  
			  <td class="display" id="sub_total"></td>			  
			</tr>
			<tr>
			  <th colspan="2" class="text-right">Freight</th>			  
			  <td class="display" id="freight"></td>			  
			</tr>
			<tr>
			  <th colspan="2" class="text-right">Total GST</th>			  
			  <td class="display" id="gst_total"></td>
			</tr>
			<tr>
			  <th colspan="2" class="text-right">Total inc GST</th>			  
			  <td class="display" id="price_total"></td>
			</tr>
			<tr>
			  <th colspan="2"></th>
			  <td class="text-right"><button id="add_to_cart" type="button" class="cart" disabled>Add to Cart</button></td>
			</tr>
		  </tfoot>
		</table>
	  </div>
	  
	</div>
	
  </div>

  <div class="span4">

	<h3>Gallery</h3>
	<ul id="image" class="thumbnails"></ul>
	
  </div>

</div>

<div class="row">

  <div class="span-8">



  </div>

  <div class="span-4">


  </div>

  <div class="span-6">


  </div>


  <div class="span-4 last">
	<a href="/store_cart">View Cart</a>
  </div>
	  



</div>

<script type="text/javascript">
  var productJSON = <?= $productJSON ?>;
  var subProductJSON = <?= $subProductJSON ?>;
  var priceJSON = <?= $priceJSON ?>;
  var imageJSON = <?= $imageJSON ?>;
  var freightJSON = <?= $freightJSON ?>;
  var gst = <?= $taxes->GST ?>;

</script>

<?= $js_tpl_entity ?>
<?= $js_tpl_subproduct_list ?>
<?= $js_tpl_price_list ?>
<?= $js_tpl_image_list ?>
