<?php

$product_child_id = $_GET['product_child_id'];
$price = $_GET['price'];
$wholesale_price = $_GET['wholesale_price'];
$agent_price = $_GET['agent_price'];
$sale_price = $_GET['sale_price'];
$quantity = $_GET['quantity'];
$weight = $_GET['weight'];

ob_start();
include(base_path().'/application/back/product/inc/product_child_create.php');
$var=ob_get_contents(); 
ob_end_clean();
echo $var;


?>