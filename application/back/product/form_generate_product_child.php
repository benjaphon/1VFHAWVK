<?php

$product_child_id = $_GET['product_child_id'];

ob_start();
include(base_path().'/application/back/inc/product_child_create.php');
$var=ob_get_contents(); 
ob_end_clean();
echo $var;


?>