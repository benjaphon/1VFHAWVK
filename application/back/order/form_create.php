<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    $value_or = array(
        "order_status" => "R",
        "shipping_type" => trim($_POST['shipping_type']),
        "sender" => trim($_POST['sender']),
        "sender_type" => trim($_POST['sender_type']),
        "receiver" => trim($_POST['receiver']),
        "note" => trim($_POST['note']),
        "order_datetime" => date('Y-m-d H:i:s'),
        "total" => $_SESSION[_ss . 'total_price'],
        "total_weight" => $_SESSION[_ss . 'total_weight'],
        "user_id" => $_SESSION[_ss . 'id'],
        "created_at" => date('Y-m-d H:i:s'),
        "modified_at" => date('Y-m-d H:i:s')
    );
    $query_or = $db->insert("orders", $value_or);
    $order_id = $db->insert_id();
    if ($query_or == TRUE) {
        foreach ($_SESSION[_ss . 'cart'] as $key => $value) {
            $value_od = array(
                "order_id" => $order_id,
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
        

        //$_SESSION[_ss . 'mform'] = "borbaimai";
        //$_SESSION[_ss . 'order_id'] = $order_id;
        header("location:" . base_url() . "/back/order");
    }
    $db->close();
}

?>