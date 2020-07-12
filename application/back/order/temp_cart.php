<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {


$db = new database();

$sql_od = "SELECT d.*,p.id,p.name,p.url_picture,p.weight,p.agent_price,p.wholesale_price,p.sale_price,bs.size_index 
            FROM order_details AS d 
            INNER JOIN products AS p ON d.product_id = p.id
            LEFT JOIN box_sizes AS bs ON p.boxsize_id = bs.id ";
$sql_od .="WHERE d.order_id='{$_POST['id']}' ";
$query_od = $db->query($sql_od);

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
    $_SESSION[_ss . 'order_size'] = 0;
}

while ($rs_od = $db->get($query_od)) {
    array_push($_SESSION[_ss . 'cart'], $rs_od['product_id']);
    $key = array_search($rs_od['product_id'], $_SESSION[_ss . 'cart']);
    $_SESSION[_ss . 'qty'][$key] = $_SESSION[_ss . 'temp_qty'][$key] = $rs_od['quantity'];
    $_SESSION[_ss . 'price'][$key] = $rs_od['price'];
    $_SESSION[_ss . 'agent_price'][$key] = $rs_od['agent_price'];
    $_SESSION[_ss . 'wholesale_price'][$key] = $rs_od['wholesale_price'];
    $_SESSION[_ss . 'sale_price'][$key] = $rs_od['sale_price'];
    $_SESSION[_ss . 'weight'][$key] = $rs_od['weight'];
    $_SESSION[_ss . 'note'][$key] = $rs_od['note'];
    $_SESSION[_ss . 'total_price'] += $rs_od['price'] * $rs_od['quantity'];
    $_SESSION[_ss . 'total_weight'] += $rs_od['weight'] * $rs_od['quantity'];

    if ($rs_od['size_index'] > $_SESSION[_ss . 'order_size']) {
        $_SESSION[_ss . 'order_size'] = $rs_od['size_index'];
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