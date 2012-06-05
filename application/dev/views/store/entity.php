<div class="container">

  <div class="span-8">

	<div id="product"></div>

	<div id="images">
	  <ul id="image" class="float"></ul>
	</div>

  </div>

  <div class="span-4">

	<h2>Pricing</h2>
	<table id="price">
	  <thead>
		<tr>
		  <th width="60">Qty</th>
		  <th>Price ea</th>
		</tr>
	  </thead>
	  <tbody></tbody>
	</table>

  </div>

  <div class="span-6">

	<h2>Ordering</h2>
	<table id="cart_prep">
	  <thead>
		<tr>
		  <th width="40">Code</th>
		  <th width="80">Colour</th>
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
		  <th></th>
		  <td class="text-right" colspan="2"><button id="add_to_cart" type="button" class="cart" disabled>Add to Cart</button></td>
		</tr>
	  </tfoot>
	</table>

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
