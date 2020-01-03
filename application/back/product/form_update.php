<?php

require(base_path() . "/assets/library/uploadimg.php");
require(base_path() . "/application/back/product/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new database();

    $vdo_filename = upload_new_video($_POST['id'], "video_filename", "products");

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
        "video_filename" => $vdo_filename,
        "product_status" => $_POST['product_status'],
        "modified_at" => date('Y-m-d H:i:s'),
    );
    $query_pd = $db->update("products", $value_pd, "id='{$_POST['id']}'");

    if ($query_pd == TRUE) {

        $product_id = $_POST['id'];

        upload_img($product_id, "product");

        /************* Delete & Save All Child Product ************/

        $option_product_child = array(
            "table" => "products",
            "condition" => "parent_product_id='{$product_id}' AND flag_status=1"
        );
        $query_product_child = $db->select($option_product_child);

        while ($rs_product_child = $db->get($query_product_child)) {

            $query = $db->delete("products", "id='{$rs_product_child['id']}'");

            if ($query==TRUE) {
                
                delete_img($rs_product_child['id'], "product");

            } else {
                //error can't delete foreign key just update status
                $arr_update = array(
                    "flag_status" => 0
                );

                $query_pd = $db->update("products", $arr_update, "id={$rs_product_child['id']}");
            }
            
        }

        if (isset($_POST['child_name'])) {

            for ($key = 0; $key < count($_POST['child_name']); $key++) {
                
                $value_child_pd = array(
                    "name" => trim($_POST['child_name'][$key]),
                    "price" => trim($_POST['child_price'][$key]),
                    "wholesale_price" => trim($_POST['child_wholesale_price'][$key]),
                    "agent_price" => trim($_POST['child_agent_price'][$key]),
                    "sale_price" => trim($_POST['child_sale_price'][$key]),
                    "kerry" => trim($_POST['kerry']), //Same as parent
                    "start_ship_date" => date("Y-m-d", strtotime($_POST['start_ship_date'])), //Same as parent
                    "description" => trim($_POST['description']), //Same as parent
                    "quantity" => trim($_POST['child_quantity'][$key]),
                    "weight" => trim($_POST['child_weight'][$key]),
                    "video_filename" => $vdo_filename, //Same as parent
                    "parent_product_id" => $product_id,
                    "created_at" => date('Y-m-d H:i:s'),
                    "modified_at" => date('Y-m-d H:i:s')
                );
                $query_pd_c = $db->insert("products", $value_child_pd);

                if ($query_pd_c == TRUE) {

                    $child_product_id = $db->insert_id();

                    duplicate_img($product_id, $child_product_id, "product");

                }
            }
        }
            
        header("location:" . $baseUrl . "/back/product");
    }
}

?>