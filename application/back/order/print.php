<?php

if (!isset($_GET['id'])) {
    header("location:" . $baseUrl . "/back/order");
}

$db = new database();

$sql_os = "SELECT os.* FROM orders os ";
$sql_os .= "WHERE os.id='{$_GET['id']}'";

$query_os = $db->query($sql_os);
$rows_os = $db->rows($query_os);
if($rows_os == 0){
    header("location:" . $baseUrl . "/back/order");
}else{
    $rs_os = $db->get($query_os);
}

$sql_od = "SELECT d.*, p.name, p.parent_product_id FROM order_details d INNER JOIN products p ";
$sql_od .= "ON d.product_id=p.id ";
$sql_od .="WHERE d.order_id='{$_GET['id']}' ";
$query_od = $db->query($sql_od);

?>
<!DOCTYPE html>
<html>
<head>
	<link href="<?php echo $baseUrl; ?>/assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $baseUrl; ?>/assets/css/print-style.css" rel="stylesheet" media="screen, print">
</head>
<body>
	
<printarea>

<div class="container">
	<div class="row">
		<div class="col-xs-6">
			<label>Order ID : </label><?php echo $rs_os['id']; ?> 
		</div>
		<div class="col-xs-6">
			<label>User ID : </label><?php echo $rs_os['user_id']; ?> 
		</div>
	</div>
	<div class="row">
		<h3>Product</h3>
	    <table class="table" style="font-size: 12px;">
	        <thead>
	            <tr>
	                <th>#</th>
	                <th>ชื่อสินค้า</th>
	                <th>ราคา(บาท)</th>
	                <th>จำนวน</th>
	                <th>รวม</th>
	            </tr>
	        </thead>
	        <tbody>
				<?php 
					$grand_total = 0;
					while ($rs_od = $db->get($query_od)) { 
						$total_price = $rs_od['price'] * $rs_od['quantity'];
		                $grand_total = $total_price + $grand_total;
						?>
				<tr>
					<td></td>
					<td>
						<?php 

							$product_name = $rs_od['name'];

							if (isset($rs_od['parent_product_id'])) {
								$option_pd_parent = array(
									"table" => "products",
									"condition" => "id={$rs_od['parent_product_id']}"
								);

								$query_pd_parent = $db->select($option_pd_parent);
								$rs_pd_parent = $db->get($query_pd_parent);

								$product_name = $rs_pd_parent['name'] . ' ' . $rs_od['name'];
							}

							echo $product_name;
						?>
					</td>
					<td><?php echo number_format($rs_od['price'], 2); ?></td>
					<td><?php echo $rs_od['quantity']; ?></td>
					<td><?php echo number_format($total_price, 2); ?></td>
				</tr>
				<?php } ?>
	            <tr class="info">
	                <td colspan="5" style="text-align: right;">
	                    ราคารวมทั้งหมด <strong><?php echo number_format($grand_total, 2); ?></strong> บาท
	                </td>
	            </tr>
	        </tbody>
	    </table>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<label>ผู้ส่ง</label><br>
			<p><?php echo $rs_os['sender']; ?></p>
		</div>
	</div>
	<div class="row">
		<svg class="barcode"
		  jsbarcode-value="<?php echo $rs_os['id']; ?>"
		  jsbarcode-height="30">
		</svg>
	</div>
	<div class="row">
		<div class="col-xs-offset-2 col-xs-4">
			<h3 id="ship_type"><?php echo $rs_os['shipping_type']; ?></h3>
		</div>
		<div class="col-xs-6">
			<label>ผู้รับ</label><br>
			<p style="font-size: 16px;"><?php echo $rs_os['receiver']; ?></p>
		</div>
	</div>
</div>


</printarea>

<script src="<?php echo $baseUrl; ?>/assets/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo $baseUrl; ?>/assets/js/jQuery.print.min.js"></script>
<script src="<?php echo $baseUrl; ?>/assets/js/JsBarcode.all.min.js"></script>

<script>
	$(document).ready(function() {
		$('printarea').print({
			mediaPrint: true,
			stylesheet: '<?php echo $baseUrl; ?>/assets/css/print-style.css'
		});
	});
	JsBarcode(".barcode").init();
</script>
</body>
</html>
