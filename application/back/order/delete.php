<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$query = $db->delete("orders", "id='{$_GET['id']}'");
if($query == TRUE){
    
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

    $db->delete("order_details", "order_id='{$_GET['id']}'");
    $db->delete("payments", "order_id='{$_GET['id']}'");
    header("location:" . $baseUrl . "/back/order");
}else{
    echo "Error!";
}
$db->close();

?>