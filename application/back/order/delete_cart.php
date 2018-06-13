<?php

$product_id = isset($_POST['product_id']) ? explode(",", $_POST['product_id'])[0] : "";

if (!isset($_SESSION[_ss . 'cart'])) {
    $_SESSION[_ss . 'cart'] = array();
    $_SESSION[_ss . 'qty'] = array();
    $_SESSION[_ss . 'price'] = array();
    $_SESSION[_ss . 'total_price'] = 0;
}
$key = array_search($product_id, $_SESSION[_ss . 'cart']);
$_SESSION[_ss . 'total_price'] -= $_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key];
$_SESSION[_ss . 'qty'][$key] = 0;
$_SESSION[_ss . 'price'][$key] = 0;
$_SESSION[_ss . 'cart'] = array_diff($_SESSION[_ss . 'cart'], array($product_id));

/*
 * render table***********************************************************************
 */
require 'assets/template/back/render_cart.php';
/*
 * render table***********************************************************************
 */

?>