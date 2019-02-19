<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    $value_rq = array(
        "status" => "A",
        "problem" => trim($_POST['problem']),
        "order_id" => trim($_POST['order_id']),
        "user_id" => $_SESSION[_ss . 'id'],
        "created_at" => date('Y-m-d H:i:s')
    );
    $query_rq = $db->insert("request", $value_rq);
    if ($query_rq == TRUE) {
        header("location:" . base_url() . "/back/order");
    }
    mysql_close();
}

?>