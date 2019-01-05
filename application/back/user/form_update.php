<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();
    $password = salt_pass($_POST['new_password']);
    $values = array(
        "username" => trim($_POST['username']),
        "password" => $password,
        "address" => trim($_POST['address']),
        "role" => trim($_POST['role'])
    );
    $query = $db->update("users", $values, "id={$_GET['id']}");

    if ($query) {
        header("location:" . $baseUrl . "/back/user");
    }
}
