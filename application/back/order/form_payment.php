<?php

require(base_path() . "/assets/library/uploadimg.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    
    if (checkimg() == TRUE) {
        $filename = date('YmdHis') . rand(0, 9);
        $type = end(explode(".", $_FILES["image"]["name"]));
        $image = $filename . "." . $type;

        $path = base_path() . "/assets/upload/payment/";
        uploadimg($filename, 600, 600, $path);
        uploadimg("thumb_" . $filename, 400, 400, $path);
        uploadimg("md_" . $filename, 150, 150, $path);
        uploadimg("sm_" . $filename, 70, 70, $path);
    } else {
        $image = "ecimage.jpg";
    }
    
    if ($_POST['pay_type']=="other") {
        $_POST['pay_type'] = trim($_POST['txt_pay_type']);
    }
    
    $value_pm = array(
        "pay_money" => trim($_POST['pay_money']),
        "detail" => trim($_POST['detail']),
        "order_id" => $_POST['order_id'],
        "pay_type" => trim($_POST['pay_type']),
        "url_picture" => $image,
        "created_at" => date('Y-m-d H:i:s')
    );
    $query_pm = $db->insert("payments", $value_pm);

    if ($query_pm == TRUE) {
        $db->update("orders", array("order_status"=>"P","ship_price"=>$_POST['ship_price'] ,"total"=>$_POST['grand_total']+$_POST['ship_price']),"id='{$_POST['order_id']}'");
        header("location:" . $baseUrl . "/back/order");
    }
    mysql_close();
}

?>