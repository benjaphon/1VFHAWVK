<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$query = $db->delete("orders", "id='{$_GET['id']}'");
if($query == TRUE){

    //deleted stamp log
    date_default_timezone_set('Asia/Bangkok');
    
    $option_pm = array(
        "table" => "payments",
        "fields" => "url_picture",
        "condition" => "order_id='{$_GET['id']}'"
    );

    $query_pm = $db->select($option_pm);
    $rs_pm = $db->get($query_pm);

    if ($rs_pm['url_picture'] != "ecimage.jpg") {
        $path = base_path() . "/assets/upload/payment/";
        @unlink($path . $rs_pm['url_picture']);
        @unlink($path . "thumb_" . $rs_pm['url_picture']);
        @unlink($path . "md_" . $rs_pm['url_picture']);
        @unlink($path . "sm_" . $rs_pm['url_picture']);
    }

    $option_od = array(
        "table" => "order_details",
        "condition" => "order_id='{$_GET['id']}'"
    );

    $query_od = $db->select($option_od);

    while ($rs_od = $db->get($query_od)) {
        $value_op = array(  
            "order_id" => $rs_od['order_id'],
            "product_id" => $rs_od['product_id'],
            "quantity" => $rs_od['quantity'],
            "price" => $rs_od['price'],
            "weight" => $rs_od['weight'],
            "note" => $rs_od['note'],
            "delete_time" => date('Y-m-d H:i:s'),
            "delete_by" => $_SESSION[_ss . 'id']
        );
        $db->insert("order_details_del", $value_op);
    }

    $db->delete("order_details", "order_id='{$_GET['id']}'");
    $db->delete("payments", "order_id='{$_GET['id']}'");
    header("location:" . $baseUrl . "/back/order");
}else{
    echo "Error!";
}
$db->close();

?>