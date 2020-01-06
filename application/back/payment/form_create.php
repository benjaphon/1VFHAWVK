<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pay_money = $_POST['pay_money'];
    $pay_type = $_POST['pay_type'];
    $txt_pay_type = $_POST['txt_pay_type'];
    $detail = $_POST['detail'];
    $order_id_selected = $_POST['order_id_selected'];
    $grand_order_total = $_POST['grand_order_total'];


    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    $path = base_path() . "/assets/upload/payment/";
    
    if ($pay_type=="other") {
        $pay_type = trim($txt_pay_type);
    }


    $value_pm_bulk = array(
        "user_id" => $_SESSION[_ss . 'id'],
        "pay_money" => trim($pay_money),
        "detail" => trim($detail),
        "pay_type" => trim($pay_type),
        "grand_order_total" => $grand_order_total,
        "created_at" => date('Y-m-d H:i:s'),
        "updated_at" => date('Y-m-d H:i:s'),
    );
    $query_pm_bulk = $db->insert("payment_bulks", $value_pm_bulk);

    if ($query_pm_bulk == TRUE) {

        $payment_bulk_id = $db->insert_id();

        upload_img($payment_bulk_id, "payment", $path);

        foreach ($order_id_selected as $arr) {

            $arrValue = explode(",", $arr);
            $order_id = $arrValue[0];
            $total = $arrValue[1];
            $ship_price = $arrValue[2];

            $value_pm = array(
                "pay_money" => trim($pay_money),
                "detail" => trim($detail),
                "order_id" => $order_id,
                "bulk_id" => $payment_bulk_id,
                "pay_type" => trim($pay_type),
                "created_at" => date('Y-m-d H:i:s')
            );
            $query_pm = $db->insert("payments", $value_pm);

            if ($query_pm == TRUE) {

                $payment_id = $db->insert_id();

                upload_img($payment_id, "payment", $path);

                $db->update("orders", array("order_status"=>"P", "total"=>$total, "ship_price"=>$ship_price),"id='{$order_id}'");
            }

        }

    }


    header("location:" . $baseUrl . "/back/payment");

}

?>