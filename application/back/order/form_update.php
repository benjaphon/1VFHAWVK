<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    $validextensions = array("jpeg", "jpg", "png", "pdf");
    $path = base_path() . "/assets/upload/order/";

    $sender_filename = upload_file('sender_file', $validextensions, $path);

    if (empty($sender_filename)) {
        $sender_filename = $_POST['sender_filename_hidden'];
    } else {
        @unlink($path . $_POST['sender_filename_hidden']);
    }

    $receiver_filename = upload_file('receiver_file', $validextensions, $path);

    if (empty($receiver_filename)) {
        $receiver_filename = $_POST['receiver_filename_hidden'];
    } else {
        @unlink($path . $_POST['receiver_filename_hidden']);
    }

    $value_or = array(
        "shipping_type" => trim($_POST['shipping_type']),
        "sender" => trim($_POST['sender']),
        "sender_type" => trim($_POST['sender_type']),
        "sender_filename" => $sender_filename,
        "receiver" => trim($_POST['receiver']),
        "receiver_filename" => $receiver_filename,
        "note" => trim($_POST['note']),
        "total" => $_SESSION[_ss . 'total_price'],
        "total_weight" => $_SESSION[_ss . 'total_weight'],
        "modified_at" => date('Y-m-d H:i:s')
    );
    $query_or = $db->update("orders", $value_or, "id='{$_POST['id']}'");
    if ($query_or == TRUE) {
        $db->delete("order_details", "order_id='{$_POST['id']}'");
        foreach ($_SESSION[_ss . 'cart'] as $key => $value) {
            $value_od = array(
                "order_id" => $_POST['id'],
                "product_id" => $value,
                "quantity" => $_SESSION[_ss . 'qty'][$key],
                "price" => $_SESSION[_ss . 'price'][$key],
                "weight" => $_SESSION[_ss . 'weight'][$key],
                "note" => $_SESSION[_ss . 'note'][$key]
            );
            $db->insert("order_details", $value_od);

            //cut stock
            $sql_pd = "UPDATE products SET quantity=quantity-{$_SESSION[_ss . 'qty'][$key]} WHERE id='{$value}'";
            $db->query($sql_pd);
        }

        unset($_SESSION[_ss . 'cart']);
        unset($_SESSION[_ss . 'qty']);
        unset($_SESSION[_ss . 'price']);
        unset($_SESSION[_ss . 'wholesale_price']);
        unset($_SESSION[_ss . 'weight']);
        unset($_SESSION[_ss . 'note']);
        unset($_SESSION[_ss . 'total_price']);
        unset($_SESSION[_ss . 'temp_qty']);

        header("location:" . base_url() . "/back/order");
    }
}

?>