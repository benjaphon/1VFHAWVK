<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {

$product_id = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[0] : 0;
$price = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[1] : 0;

$db = new database();

//Add new or update product to session order detail
$qty = isset($_POST['qty']) && !empty($_POST['qty']) ? $_POST['qty'] : 0;

$option_pd = array(
    "table" => "products",
    "condition" => "id={$product_id}"
);

$query_pd = $db->select($option_pd);
$rs_pd = $db->get($query_pd);

    if (!isset($_SESSION[_ss . 'cart'])) {
        $_SESSION[_ss . 'cart'] = array();
        $_SESSION[_ss . 'qty'][] = array();
        $_SESSION[_ss . 'price'][] = array();
        $_SESSION[_ss . 'note'][] = array();
        $_SESSION[_ss . 'total_price'] = 0;
    }

    if (in_array($product_id, $_SESSION[_ss . 'cart'])) {
        $key = array_search($product_id, $_SESSION[_ss . 'cart']);
    } else {
        array_push($_SESSION[_ss . 'cart'], $product_id);
        $key = array_search($product_id, $_SESSION[_ss . 'cart']);
        $_SESSION[_ss . 'qty'][$key] = 0;
        $_SESSION[_ss . 'price'][$key] = 0;
        $_SESSION[_ss . 'note'][$key] = '';
    }

if ($rs_pd['quantity'] >= ($_SESSION[_ss . 'qty'][$key]+$qty)) {
    $_SESSION[_ss . 'qty'][$key] += $qty;
    $_SESSION[_ss . 'price'][$key] = $price;
    $_SESSION[_ss . 'total_price'] += $price * $qty;
}
else
{
    echo "<script>alert('สินค้า {$rs_pd['name']} คงเหลือไม่เพียงพอ');</script>";
}


//keep note session
if (isset($_POST['note'])) {
    for ($i = 0; $i < count($_POST['note']); $i++) {
        $key = $_POST['arr_key_' . $i];
        $_SESSION[_ss . 'note'][$key] = $_POST['note'][$i];
    }
}

/*
 * render table***********************************************************************
 */
require 'assets/template/back/render_cart.php';
/*
 * render table***********************************************************************
 */

}

?>