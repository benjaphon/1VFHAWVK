<?php
/*
 * php code///////////**********************************************************
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $db = new database();
    $query = $db->delete("users", "id='{$_POST['user_id']}'");
    if($query == TRUE){
        header("location:" . $baseUrl . "/back/user");
    }else{
        echo "error";
    }

}
