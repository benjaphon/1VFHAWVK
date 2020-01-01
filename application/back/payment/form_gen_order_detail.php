<?php

$db = new database();

$option_order = array(
    "table" => "orders",
    "condition" => "id='{$_GET['order_id']}' "
);
$query_order = $db->select($option_order);
$rs_order = $db->get($query_order);

$sql_od = "SELECT d.*,p.id,p.name,p.kerry FROM order_details d INNER JOIN products p ";
$sql_od .= "ON d.product_id=p.id ";
$sql_od .="WHERE d.order_id='{$_GET['order_id']}' ";
$query_od = $db->query($sql_od);
$rows_count = $db->rows($query_od);

ob_start();
include(base_path().'/application/back/payment/view_order_detail.php');
$var=ob_get_contents(); 
ob_end_clean();
echo $var;


?>