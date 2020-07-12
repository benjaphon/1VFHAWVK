<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {

$db = new database();

//Calculate each records
if (isset($_POST['qtyupdate'])) {

    for ($i = 0; $i < count($_POST['qtyupdate']); $i++) {

        $product_id = $_POST['product_id_' . $i];
        $qty = isset($_POST['qtyupdate'][$i]) && !empty($_POST['qtyupdate'][$i]) ? $_POST['qtyupdate'][$i] : 0;

        $option_pd = array(
            "table" => "products",
            "condition" => "id={$product_id}"
        );

        $query_pd = $db->select($option_pd);
        $rs_pd = $db->get($query_pd);

        $key = $_POST['arr_key_' . $i];

        if (!isset($_SESSION[_ss . 'temp_qty'][$key]))
            $temp_qty = $qty;
        else
            $temp_qty = $qty-$_SESSION[_ss . 'temp_qty'][$key];

        if ($rs_pd['quantity'] >= $temp_qty) {
        
            $_SESSION[_ss . 'total_price'] -= $_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key];

            //Whole Sale Price Calculation
            if(($_SESSION[_ss . 'levelaccess'] == 'agent_vip')){

                $_SESSION[_ss . 'price'][$key] = $_SESSION[_ss . 'sale_price'][$key];
                
            }else{

                if($qty > 10)
                    $_SESSION[_ss . 'price'][$key] = $_SESSION[_ss . 'wholesale_price'][$key];
                else
                    $_SESSION[_ss . 'price'][$key] = $_SESSION[_ss . 'agent_price'][$key];

            }

            $_SESSION[_ss . 'total_price'] += $_SESSION[_ss . 'price'][$key] * $qty;

            $_SESSION[_ss . 'total_weight'] -= $_SESSION[_ss . 'weight'][$key] * $_SESSION[_ss . 'qty'][$key];
            $_SESSION[_ss . 'total_weight'] += $_SESSION[_ss . 'weight'][$key] * $qty;
            
            $_SESSION[_ss . 'qty'][$key] = $qty;

        }
        else
        {
            echo "<script>alert('สินค้า {$rs_pd['name']} คงเหลือไม่เพียงพอ');</script>";
            break;
        }

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