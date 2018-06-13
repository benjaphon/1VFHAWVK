<?php

$sender_type = $_POST['sender_type'];
if ($sender_type == 'address_admin') {
	$option = array(
		"fields" => "address",
	    "table" => "users",
	    "condition" => "username='admin'",
	    "limit" => 1
	);
} elseif ($sender_type == 'address_agent') {
	$option = array(
		"fields" => "address",
	    "table" => "users",
	    "condition" => "id={$_SESSION[_ss . 'id']}",
	    "limit" => 1
	);
} else {
	return;
}

$db = new database();

$query = $db->select($option);
$rs_pd = $db->get($query);
echo $rs_pd['address'];


?>