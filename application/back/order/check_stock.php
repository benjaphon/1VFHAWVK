<?php

if (isset($_SESSION[_ss . 'cart']) && COUNT($_SESSION[_ss . 'cart']) > 0) {
    $db = new database();
    //Check Stock
    foreach ($_SESSION[_ss . 'cart'] as $key => $value) {
        $product_id = $value;

        $option_pd = array(
            "table" => "products",
            "condition" => "id={$product_id}"
        );

        $query_pd = $db->select($option_pd);
        $rs_pd = $db->get($query_pd);

        if (!isset($_SESSION[_ss . 'temp_qty'][$key]))
            $temp_qty = $_SESSION[_ss . 'qty'][$key];
        else
            $temp_qty = $_SESSION[_ss . 'qty'][$key]-$_SESSION[_ss . 'temp_qty'][$key];

        if ($rs_pd['quantity'] < $temp_qty) {
            echo "สินค้า {$rs_pd['name']} คงเหลือไม่เพียงพอ";
            break;
        }
    }
}
else
{
    echo "ไม่มีสินค้าในตะกร้าสินค้า กรุณาเพิ่มสินค้า";
}
?>