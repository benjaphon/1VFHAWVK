<?php
/*
 * Name: add cart file.
 * Description: เพิ่มหรืออัพเดทสินค้าในตระกร้าทุกครั้งที่กดปุ่มเพิ่มสินค้าลงในตระกร้าจะต้องมาทำในไฟล์นี้เพื่อจัดเก็บข้อมูลสินค้าลง Session ก่อนที่จะวิ่งไปที่ render cart file
 * Author: Benjaphon
 * Last Modified: Benjaphon
 */

if($_SERVER['REQUEST_METHOD'] == 'POST') {

$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
$qty = isset($_POST['qty']) && !empty($_POST['qty']) ? $_POST['qty'] : 0;

//ดึงข้อมูลสินค้าที่สั่งซื้อขึ้นมาเก็บไว้ในตัวแปรเพื่อนำไปตรวจสอบในภายหลัง
$db = new database();
$option_pd = array(
    "table" => "products",
    "condition" => "id={$product_id}"
);

$query_pd = $db->select($option_pd);
$rs_pd = $db->get($query_pd);

$agent_price = $rs_pd['agent_price'];
$weight = $rs_pd['weight'];
$wholesale_price = $rs_pd['wholesale_price'];
$sale_price = $rs_pd['sale_price'];

//ถ้ายังไม่เคยมีการสร้างตระกร้าสินค้าขึ้นมาก่อนให้ดำเนินการสร้าง
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

//ถ้าในตระกร้าสินค้าไม่เคยมีสินค้า ที่สั่งซื้อเข้ามา ให้สร้างรายการสินค้าขึ้นมาใหม่
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

//ถ้าในตระกร้าสินค้ามีสินค้าที่เคยสั่งซื้อเข้ามาอยู่แล้วให้ใช้ key อ้างอิงจากรายการสินค้าเดิมที่มีอยู่
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

        //การคำนวณราคาขายส่ง
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


//ส่วนของการเพิ่มรายละเอียดสินค้า
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