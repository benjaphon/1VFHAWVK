<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION[_ss . 'levelaccess'] == 'admin') {
    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    $path = base_path() . "/assets/upload/product/";

    $option_product = array(
        "table" => "products",
        "condition" => "id='{$_GET['id']}'"
    );
    $query_product = $db->select($option_product);
    $rs_product = $db->get($query_product);

    $vdo_filename = '';

    if (isset($rs_product['video_filename']) && !empty($rs_product['video_filename']))  {
            $ext_vdo = explode('.', basename($rs_product['video_filename'])); 
            $vdo_filename = date('YmdHis') . md5(uniqid()). "." . end($ext_vdo);
            $path = base_path() . "/assets/upload/product/";
            copy($path . $rs_product['video_filename'], $path . $vdo_filename);
    }
    
    $value_pd = array(
        "name" => $rs_product['name'],
        "price" => $rs_product['price'],
        "wholesale_price" => $rs_product['wholesale_price'],
        "agent_price" => $rs_product['agent_price'],
        "sale_price" => $rs_product['sale_price'],
        "kerry" => $rs_product['kerry'],
        "start_ship_date" => $rs_product['start_ship_date'],
        "description" => $rs_product['description'],
        "quantity" => $rs_product['quantity'],
        "weight" => $rs_product['weight'],
        "video_filename" => $vdo_filename,
        "product_status" => $rs_product['product_status'],
        "product_full_name" => $rs_product['name'],
        "created_at" => date('Y-m-d H:i:s'),
        "modified_at" => date('Y-m-d H:i:s')
    );
    $query_pd = $db->insert("products", $value_pd);

    if ($query_pd == TRUE) {

        $product_id = $db->insert_id();

        duplicate_img($_GET['id'], $product_id, "product", $path);

        /*********** Duplicate Child Products **************/

        $option_child_pd = array(
            "table" => "products",
            "condition" => "parent_product_id='{$_GET['id']}' AND flag_status=1"
        );
        $query_child_pd = $db->select($option_child_pd);

        while ($rs_child_pd = $db->get($query_child_pd)){
            
            $value_child_pd = array(
                "name" => trim($rs_child_pd['name']),
                "price" => trim($rs_child_pd['price']),
                "wholesale_price" => trim($rs_child_pd['wholesale_price']),
                "agent_price" => trim($rs_child_pd['agent_price']),
                "sale_price" => trim($rs_child_pd['sale_price']),
                "kerry" => trim($rs_child_pd['kerry']),
                "start_ship_date" => date("Y-m-d", strtotime($rs_child_pd['start_ship_date'])), //Same as parent
                "description" => trim($rs_child_pd['description']), //Same as parent
                "quantity" => trim($rs_child_pd['quantity']),
                "weight" => trim($rs_child_pd['weight']),
                "video_filename" => $vdo_filename, //Same as parent
                "parent_product_id" => $product_id,
                "product_full_name" => $rs_product['name'] . ' ' . trim($rs_child_pd['name']),
                "created_at" => date('Y-m-d H:i:s'),
                "modified_at" => date('Y-m-d H:i:s')
            );
            $query_pd_child = $db->insert("products", $value_child_pd);

            if ($query_pd_child == TRUE) {
                $child_product_id = $db->insert_id();

                duplicate_img($rs_child_pd['id'], $child_product_id, "product", $path);

            }

        }
            
        header("location:" . $baseUrl . "/back/product");
    }
}

?>