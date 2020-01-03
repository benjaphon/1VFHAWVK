<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db = new database();
    $db->update("orders", array("order_status"=>"F"),"id='{$_POST['order_id']}'");
    header("location:" . $baseUrl . "/back/order");
}

?>