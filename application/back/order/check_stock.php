<?php

if (isset($_SESSION[_ss . 'cart'])) {
    $db = new database();
    //Check Stock
    foreach ($_SESSION[_ss . 'cart'] as $key => $value) {
        $product_id = $value;
        $qty = $_SESSION[_ss . 'qty'][$key];

        $option_pd = array(
            "table" => "products",
            "condition" => "id={$product_id}"
        );

        $query_pd = $db->select($option_pd);
        $rs_pd = $db->get($query_pd);

        if ($rs_pd['quantity'] < $qty) {
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