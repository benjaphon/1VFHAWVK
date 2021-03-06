<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    $path = base_path() . "/assets/upload/product/";

    $vdo_filename = upload_video($path);
    
    $value_pd = array(
        "name" => trim($_POST['name']),
        "price" => trim($_POST['price']),
        "wholesale_price" => trim($_POST['wholesale_price']),
        "agent_price" => trim($_POST['agent_price']),
        "sale_price" => trim($_POST['sale_price']),
        "kerry" => trim($_POST['kerry']),
        "start_ship_date" => date("Y-m-d", strtotime($_POST['start_ship_date'])),
        "description" => trim($_POST['description']),
        "quantity" => trim($_POST['quantity']),
        "weight" => trim($_POST['weight']),
        "boxsize_id" => trim($_POST['box_size']),
        "video_filename" => $vdo_filename,
        "product_status" => $_POST['product_status'],
        "product_full_name" => trim($_POST['name']),
        "created_at" => date('Y-m-d H:i:s'),
        "modified_at" => date('Y-m-d H:i:s')
    );

    $query_pd = $db->insert("products", $value_pd);

    if ($query_pd == TRUE) {

        $product_id = $db->insert_id();

        upload_img($product_id, "product", $path);

        //Save Child Products
        if (isset($_POST['child_name_add'])) {

            for ($key = 0; $key < count($_POST['child_name_add']); $key++) {
                
                $value_child_pd = array(
                    "name" => trim($_POST['child_name_add'][$key]),
                    "price" => trim($_POST['child_price_add'][$key]),
                    "wholesale_price" => trim($_POST['child_wholesale_price_add'][$key]),
                    "agent_price" => trim($_POST['child_agent_price_add'][$key]),
                    "sale_price" => trim($_POST['child_sale_price_add'][$key]),
                    "kerry" => trim($_POST['kerry']), //Same as parent
                    "start_ship_date" => date("Y-m-d", strtotime($_POST['start_ship_date'])), //Same as parent
                    "description" => trim($_POST['description']), //Same as parent
                    "quantity" => trim($_POST['child_quantity_add'][$key]),
                    "weight" => trim($_POST['child_weight_add'][$key]),
                    "boxsize_id" => trim($_POST['child_box_size_add'][$key]),
                    "video_filename" => $vdo_filename, //Same as parent
                    "parent_product_id" => $product_id,
                    "product_full_name" => trim($_POST['name']) . ' ' . trim($_POST['child_name_add'][$key]),
                    "created_at" => date('Y-m-d H:i:s'),
                    "modified_at" => date('Y-m-d H:i:s')
                );
                $query_pd_c = $db->insert("products", $value_child_pd);

                if ($query_pd_c == TRUE) {

                    $child_product_id = $db->insert_id();

                    upload_img($child_product_id, "product", $path);
                }
            }
        }           
    }
}

header("location:" . $baseUrl . "/back/product");

?>