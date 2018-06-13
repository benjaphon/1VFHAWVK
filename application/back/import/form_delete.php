<?php

/*
 * php code///////////**********************************************************
 */
$db = new database();

$option_pi = array(
	"table" => "product_import",
	"condition" => "id='{$_GET['id']}'"
);

$query_pi = $db->select($option_pi);
$rs_pi = $db->get($query_pi);

$qty = $rs_pi['quantity'];
$product_id = $rs_pi['product_id'];

$query = $db->delete("product_import", "id='{$_GET['id']}'");
if ($query == TRUE) {

    $sql_pd = "UPDATE products SET quantity=quantity-{$qty} WHERE id='{$product_id}'";
    $query_pd = $db->query($sql_pd);

    header("location:" . $baseUrl . "/back/import");
} else {
    echo "error";
}

?>
