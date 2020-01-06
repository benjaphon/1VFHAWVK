<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    $path = base_path() . "/assets/upload/payment/";
    
    if ($_POST['pay_type']=="other") {
        $_POST['pay_type'] = trim($_POST['txt_pay_type']);
    }
    
    $value_pm = array(
        "pay_money" => trim($_POST['pay_money']),
        "detail" => trim($_POST['detail']),
        "order_id" => $_POST['order_id'],
        "pay_type" => trim($_POST['pay_type']),
        "created_at" => date('Y-m-d H:i:s')
    );
    $query_pm = $db->insert("payments", $value_pm);

    if ($query_pm == TRUE) {

        $payment_id = $db->insert_id();

        upload_img($payment_id, "payment", $path);

        $db->update("orders", array("order_status"=>"P","ship_price"=>$_POST['ship_price'] ,"total"=>$_POST['grand_total']+$_POST['ship_price']),"id='{$_POST['order_id']}'");
        header("location:" . $baseUrl . "/back/order");
    }
}

?>