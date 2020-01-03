<?php

require(base_path() . "/application/back/product/functions.php");

$db = new database();

/************** Child Product *********************/

$option_product_child = array(
    "table" => "products",
    "condition" => "parent_product_id='{$_GET['id']}'"
);
$query_product_child = $db->select($option_product_child);

while ($rs_product_child = $db->get($query_product_child)) {

    $query = $db->delete("products", "id='{$rs_product_child['id']}'");

    if ($query==TRUE) {

        delete_img($rs_product_child['id'], "product");

    } else {

        //error can't delete foreign key just update status
        soft_delete($rs_product_child['id'], "products");
        
    }
    
}

$option_product_child = array(
    "table" => "products",
    "condition" => "parent_product_id='{$_GET['id']}' AND flag_status=0"
);
$query_product_child = $db->select($option_product_child);
$rows = $db->rows($query_product_child);

if ($rows==0) {

    $option_product = array(
        "table" => "products",
        "condition" => "id='{$_GET['id']}'"
    );
    $query_product = $db->select($option_product);
    $rs_product = $db->get($query_product);
    
    $query = $db->delete("products", "id='{$_GET['id']}'");

    if ($query == TRUE) {

        delete_img($_GET['id'], "product");

    } else {

        //error can't delete foreign key just update status
        soft_delete($_GET['id'], "products");

    }

} else {

    //error can't delete foreign key just update status
    soft_delete($_GET['id'], "products");

}


header("location:" . $baseUrl . "/back/product");

?>
