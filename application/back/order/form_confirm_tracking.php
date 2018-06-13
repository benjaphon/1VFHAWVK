<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	date_default_timezone_set('Asia/Bangkok');
	$db = new database();

    $db->update("orders", array(
    	"tracking_no"=>"{$_POST['tracking_no']}",
    	"ship_date"=>date('Y-m-d H:i:s'),
    	"order_status"=>"S"),
	    "id='{$_POST['order_id']}'"
	);

    header("location:" . $baseUrl . "/back/order");
    mysql_close();
}

?>