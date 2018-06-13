<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$query = $db->delete("users", "id='{$_GET['id']}'");
if($query == TRUE){
    header("location:" . $baseUrl . "/back/user");
}else{
    echo "error";
}
