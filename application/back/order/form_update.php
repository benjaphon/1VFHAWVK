<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    $value_or = array(
        "shipping_type" => trim($_POST['shipping_type']),
        "sender" => trim($_POST['sender']),
        "receiver" => trim($_POST['receiver']),
        "note" => trim($_POST['note']),
        "user_id" => $_SESSION[_ss . 'id'],
        "modified_at" => date('Y-m-d H:i:s')
    );
    $query_or = $db->update("orders", $value_or, "id='{$_POST['id']}'");
    if ($query_or == TRUE) {
        header("location:" . base_url() . "/back/order");
    }
    mysql_close();
}

?>