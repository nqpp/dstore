<div id="productApp">

  <div class="row row-padded">
	<div class="span4">
	  <h1>Product Store</h1>
	</div>
	<div class="btn-group pull-right" id="cart-contain"></div>
  </div>

  <ul class="thumbnails" id="products"></ul>

</div>


<script type="text/javascript">
  var productsJSON = <?= $productsJSON ?>;

</script>

<?= $js_tpl_list ?>
<?= $js_tpl_cart ?>