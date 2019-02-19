<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();
    $password = salt_pass($_POST['new_password']);
    $value = array(
        "username" => trim($_POST['username']),
        "password" => $password,
        "user_type" => trim($_POST['user_type'])
    );
    $query = $db->update("subject", $values, "id={$_POST['id']}");

    if ($query) {
        header("location:" . $baseUrl . "/back/user");
    }
}
