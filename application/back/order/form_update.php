<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    $validextensions = array("jpeg", "jpg", "png", "pdf");
    $path = base_path() . "/assets/upload/order/";

    $cover_page_filename = upload_file('cover_page_file', $validextensions, $path);

    if (isset($_POST['cover_page_filename_hidden'])) {
        if (empty($cover_page_filename)) {
            $cover_page_filename = $_POST['cover_page_filename_hidden'];
        } else {
            @unlink($path . $_POST['cover_page_filename_hidden']);
        }
    }

    $value_or = array(
        "shipping_type" => trim($_POST['shipping_type']),
        "sender" => trim($_POST['sender']),
        "sender_type" => trim($_POST['sender_type']),
        "receiver" => trim($_POST['receiver']),
        "cover_page_filename" => $cover_page_filename,
        "note" => trim($_POST['note']),
        "total" => $_SESSION[_ss . 'total_price'],
        "total_weight" => $_SESSION[_ss . 'total_weight'],
        "boxsize_code" => $_SESSION[_ss . 'boxsize_code'],
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