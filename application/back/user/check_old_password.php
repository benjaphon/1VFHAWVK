<?php

$old_password = salt_pass($_POST['old_password']);

$option = array(
    "table" => "users",
    "condition" => "id={$_POST['id']} AND password='{$old_password}'"
);


$db = new database();

$query = $db->select($option);

if($db->rows($query) > 0)
	echo 'true';
else
	echo 'false';


?>