<?php
/*
 * php code///////////**********************************************************
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $db = new database();
    $query = $db->delete("shipping_rate", "id='{$_POST['shipping_id']}'");
    if($query == TRUE){
        header("location:" . $baseUrl . "/back/shipping_rate");
    }else{
        echo "error";
    }

}
