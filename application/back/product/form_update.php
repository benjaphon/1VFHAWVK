<?php

require(base_path() . "/assets/library/uploadimg.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new database();
    $option_vdo = array(
        "table" => "products",
        "fields" => "video_filename",
        "condition" => "id='{$_POST['id']}'"
    );
    $query_vdo = $db->select($option_vdo);
    $rs_vdo = $db->get($query_vdo);

    $vdo_filename = $rs_vdo['video_filename'];
    
    if (file_exists($_FILES['file_video']['tmp_name']) && is_uploaded_file($_FILES['file_video']['tmp_name'])) {
        if ($_FILES["file_video"]["size"] < 100000000) {
            $ext_vdo = explode('.', basename($_FILES['file_video']['name'])); 
            $vdo_filename = date('YmdHis') . md5(uniqid()). "." . end($ext_vdo);
            $path = base_path() . "/assets/upload/product/";
            move_uploaded_file($_FILES["file_video"]["tmp_name"], $path . $vdo_filename);

            @unlink($path . $rs_vdo['video_filename']);
        }
    }

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
        $path = base_path() . "/assets/upload/product/";

        if (isset($_FILES['image'])) {
            for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                $validextensions = array("jpeg", "jpg", "png");      // Extensions which are allowed.
                $ext = explode('.', basename($_FILES['image']['name'][$i]));   // Explode file name from dot(.)
                $file_extension = end($ext); // Store extensions in the variable.
                $filename = date('YmdHis') . md5(uniqid());     // Set the target path with a new name of image.
                
                if (($_FILES["image"]["size"][$i] < 1000000) && in_array($file_extension, $validextensions)) { // 1000 B = 1 KB = 1000 KB = 1 MB 
                    $full_filename = uploadimg($filename, 600, 600, $path, $i);
                    uploadimg("thumb_" . $filename, 400, 400, $path, $i);
                    uploadimg("md_" . $filename, 150, 150, $path, $i);
                    uploadimg("sm_" . $filename, 70, 70, $path, $i);
    
                    $value_img = array(
                        "ref_id" => $product_id,
                        "filename" => $full_filename,
                        "filetype" => "product"
                    );
        
                    $db->insert("images", $value_img);
                }  
            }
        }

        /************* Delete & Save All Child Product ************/

        $option_product_child = array(
            "table" => "products",
            "condition" => "parent_product_id='{$product_id}' AND flag_status=1"
        );
        $query_product_child = $db->select($option_product_child);

        while ($rs_product_child = $db->get($query_product_child)) {

            $query = $db->delete("products", "id='{$rs_product_child['id']}'");

            if ($query==TRUE) {

                $option_img = array(
                    "table" => "images",
                    "fields" => "filename",
                    "condition" => "ref_id='{$rs_product_child['id']}' AND filetype='product'"
                );
                $query_img = $db->select($option_img);

                while($rs_im = $db->get($query_img)){
                    @unlink($path . $rs_im['filename']);
                    @unlink($path . "thumb_" . $rs_im['filename']);
                    @unlink($path . "md_" . $rs_im['filename']);
                    @unlink($path . "sm_" . $rs_im['filename']);
                }

                $db->delete("images", "ref_id='{$rs_product_child['id']}'");

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
                    "kerry" => trim($_POST['child_kerry'][$key]),
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

                    $option_img = array(
                        "table" => "images",
                        "fields" => "filename",
                        "condition" => "ref_id='{$product_id}' AND filetype='product'"
                    );
                    $query_img = $db->select($option_img);

                    while ($rs_img = $db->get($query_img)) {
                        $ext = explode('.', basename($rs_img['filename']));   // Explode file name from dot(.)
                        $file_extension = end($ext); // Store extensions in the variable.
                        $filename = date('YmdHis') . md5(uniqid());     // Set the target path with a new name of image.
                        $full_filename = $filename . "." . $file_extension; 
                        
                        copy($path . $rs_img['filename'], $path . $full_filename);
                        copy($path . "thumb_" . $rs_img['filename'], $path . "thumb_" . $full_filename);
                        copy($path . "md_" . $rs_img['filename'], $path . "md_" . $full_filename);
                        copy($path . "sm_" . $rs_img['filename'], $path . "sm_" . $full_filename);

                        $value_img = array(
                            "ref_id" => $child_product_id,
                            "filename" => $full_filename,
                            "filetype" => "product"
                        );

                        $db->insert("images", $value_img);
                    }
                }
            }
        }
            
        header("location:" . $baseUrl . "/back/product");
    }
}

?>