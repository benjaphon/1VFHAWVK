<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();

    $password = salt_pass($_POST['password']);
    $value_user = array(
        "username" => trim($_POST['username']),
        "password" => $password,
        "address" => trim($_POST['address']),
        "role" => trim($_POST['role'])
    );
    $query_user = $db->insert("users", $value_user);

    if ($query_user == TRUE) {
        header("location:" . $baseUrl . "/back/user");
    }
    
}