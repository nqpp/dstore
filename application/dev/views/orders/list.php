<div class="row row-padded">
  <div class="span4">
	<h1>Orders</h1>
  </div>
  <div class="span5 pull-right">
	<div class="btn-group pull-right" data-toggle="buttons-checkbox" id="statusFilter"></div>
  </div>
</div>

<div class="accordion" id="orderAccordion"></div>


<script type="text/javascript">
  var ordersJSON = <?= $ordersJSON ?>;
  var orderProductsJSON = <?= $orderProductsJSON ?>;
  var orderProductQuantitiesJSON = <?= $orderProductQuantitiesJSON ?>;
  var orderAddressesJSON = <?= $orderAddressesJSON ?>;
  var orderStatusTypesJSON = <?= $orderStatusTypesJSON ?>;
  var orderStatusFilterJSON = <?= $orderStatusFilterJSON ?>;
</script>

<?= $js_tpl_list ?>
<?= $js_tpl_order_products_list ?>
<?= $js_tpl_quantities_list ?>
<?= $js_tpl_order_address ?>
<?= $js_tpl_status_filter ?>
  