<?php

$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;

$key = array_search($product_id, $_SESSION[_ss . 'cart']);
$_SESSION[_ss . 'total_price'] -= $_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key];
$_SESSION[_ss . 'total_weight'] -= $_SESSION[_ss . 'weight'][$key] * $_SESSION[_ss . 'qty'][$key];
$_SESSION[_ss . 'qty'][$key] = 0;
$_SESSION[_ss . 'price'][$key] = 0;
$_SESSION[_ss . 'agent_price'][$key] = 0;
$_SESSION[_ss . 'wholesale_price'][$key] = 0;
$_SESSION[_ss . 'weight'][$key] = 0;
$_SESSION[_ss . 'cart'] = array_diff($_SESSION[_ss . 'cart'], array($product_id));

/*
 * render table***********************************************************************
 */
require 'assets/template/back/render_cart.php';
/*
 * render table***********************************************************************
 */

?>