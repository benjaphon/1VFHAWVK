<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION[_ss . 'levelaccess'] == 'admin') {
    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    $option_product = array(
        "table" => "products",
        "condition" => "id='{$_GET['id']}'"
    );
    $query_product = $db->select($option_product);
    $rs_product = $db->get($query_product);

    switch ($rs_product['product_status']) {
        case 'P':
            $db->update("products", array("product_status"=>"S", "start_ship_date"=>date('Y-m-d')), "id='{$_GET['id']}' OR parent_product_id='{$_GET['id']}'");
            break;
        case 'S':
            $db->update("products", array("product_status"=>"P"), "id='{$_GET['id']}' OR parent_product_id='{$_GET['id']}'");
            break;
        default:
            break;
    }

    header("location:" . $baseUrl . "/back/product?page=" . $_GET['page']);
    
}

?>