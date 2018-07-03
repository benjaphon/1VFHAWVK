<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {


$db = new database();

$sql_od = "SELECT d.*,p.id,p.name,p.url_picture FROM order_details d INNER JOIN products p ";
$sql_od .= "ON d.product_id=p.id ";
$sql_od .="WHERE d.order_id='{$_POST['id']}' ";
$query_od = $db->query($sql_od);

if (!isset($_SESSION[_ss . 'cart'])) {
    $_SESSION[_ss . 'cart'] = $_SESSION[_ss . 'temp_cart'] = array();
    $_SESSION[_ss . 'qty'][] = $_SESSION[_ss . 'temp_qty'][] = array();
    $_SESSION[_ss . 'price'][] = $_SESSION[_ss . 'temp_price'][] = array();
    $_SESSION[_ss . 'note'][] = $_SESSION[_ss . 'temp_note'][] = array();
    $_SESSION[_ss . 'total_price'] = $_SESSION[_ss . 'temp_total_price'] = 0;
}

while ($rs_od = $db->get($query_od)) {
    array_push($_SESSION[_ss . 'cart'], $rs_od['product_id']);
    $key = array_search($rs_od['product_id'], $_SESSION[_ss . 'cart']);
    $_SESSION[_ss . 'qty'][$key] = $_SESSION[_ss . 'temp_qty'][$key] = $rs_od['quantity'];
    $_SESSION[_ss . 'price'][$key] = $_SESSION[_ss . 'temp_price'][$key] = $rs_od['price'];
    $_SESSION[_ss . 'note'][$key] = $_SESSION[_ss . 'temp_note'][$key] = $rs_od['note'];
    $_SESSION[_ss . 'total_price'] = $_SESSION[_ss . 'temp_total_price'] = $rs_od['price'] * $rs_od['quantity'];
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