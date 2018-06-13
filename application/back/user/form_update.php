<?php
$db = new database();

$password = salt_pass($_POST['new_password']);
$value_user = array(
    "username" => trim($_POST['username']),
    "password" => $password,
    "address" => trim($_POST['address']),
    "role" => trim($_POST['role'])
);
$con_user = "id='{$_GET['id']}'";
$query_user = $db->update("users", $value_user, $con_user);

if($query_user == TRUE){
    header("location:" . $baseUrl . "/back/user");
}
mysql_close();