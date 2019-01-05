<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {

$product_id = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[0] : 0;
$agent_price = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[1] : 0;
$weight = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[2] : 0;
$wholesale_price = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[3] : 0;
$sale_price = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[4] : 0;

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
    $_SESSION[_ss . 'qty'][] = $_SESSION[_ss . 'temp_qty'][] = array();
    $_SESSION[_ss . 'price'][] = array();
    $_SESSION[_ss . 'agent_price'][] = array();
    $_SESSION[_ss . 'wholesale_price'][] = array();
    $_SESSION[_ss . 'sale_price'][] = array();
    $_SESSION[_ss . 'weight'][] = array();
    $_SESSION[_ss . 'note'][] = array();
    $_SESSION[_ss . 'total_price'] = 0;
    $_SESSION[_ss . 'total_weight'] = 0;
}

if (in_array($product_id, $_SESSION[_ss . 'cart'])) {
    $key = array_search($product_id, $_SESSION[_ss . 'cart']);
} else {
    if ($rs_pd['quantity'] >= $qty){
        array_push($_SESSION[_ss . 'cart'], $product_id);
        $key = array_search($product_id, $_SESSION[_ss . 'cart']);
        $_SESSION[_ss . 'qty'][$key] = 0;
        $_SESSION[_ss . 'price'][$key] = 0;
        $_SESSION[_ss . 'agent_price'][$key] = 0;
        $_SESSION[_ss . 'wholesale_price'][$key] = 0;
        $_SESSION[_ss . 'sale_price'][$key] = 0;
        $_SESSION[_ss . 'weight'][$key] = 0;
        $_SESSION[_ss . 'note'][$key] = '';
        $_SESSION[_ss . 'temp_qty'][$key] = 0;
    } else {
        echo "<script>alert('สินค้า {$rs_pd['name']} คงเหลือไม่เพียงพอ');</script>";
    }
}

if(isset($key)){

    if (!isset($_SESSION[_ss . 'temp_qty'][$key]))
        $temp_qty = $_SESSION[_ss . 'qty'][$key];
    else
        $temp_qty = $_SESSION[_ss . 'qty'][$key]-$_SESSION[_ss . 'temp_qty'][$key];

    if ($rs_pd['quantity'] >= ($temp_qty+$qty)) {
        
        $_SESSION[_ss . 'wholesale_price'][$key] = $wholesale_price;
        $_SESSION[_ss . 'agent_price'][$key] = $agent_price;
        $_SESSION[_ss . 'sale_price'][$key] = $sale_price;

        $_SESSION[_ss . 'total_price'] -= $_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key];
        $_SESSION[_ss . 'qty'][$key] += $qty;

        //Whole Sale Price Calculation
        if(($_SESSION[_ss . 'levelaccess'] == 'agent_vip')){

            $_SESSION[_ss . 'price'][$key] = $sale_price;

        }else{

            if($_SESSION[_ss . 'qty'][$key] > 10)
                $_SESSION[_ss . 'price'][$key] = $wholesale_price;
            else
                $_SESSION[_ss . 'price'][$key] = $agent_price;

        }
        
        $_SESSION[_ss . 'total_price'] += $_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key];
        $_SESSION[_ss . 'weight'][$key] = $weight;
        $_SESSION[_ss . 'total_weight'] += $weight * $qty;
    }
    else
    {
        echo "<script>alert('สินค้า {$rs_pd['name']} คงเหลือไม่เพียงพอ');</script>";
    }

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