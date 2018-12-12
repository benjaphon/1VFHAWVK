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
        if ($_FILES["file_video"]["size"] < 10000000) {
            $ext_vdo = explode('.', basename($_FILES['file_video']['name'])); 
            $vdo_filename = date('YmdHis') . md5(uniqid()). "." . end($ext_vdo);
            $path = base_path() . "/assets/upload/product/";
            move_uploaded_file($_FILES["file_video"]["tmp_name"], $path . $vdo_filename);

            @unlink($path . $rs_vdo['video_filename']);
            @unlink($path . "thumb_" . $rs_vdo['video_filename']);
            @unlink($path . "md_" . $rs_vdo['video_filename']);
            @unlink($path . "sm_" . $rs_vdo['video_filename']);
        }
    }

    $value_pd = array(
        "name" => trim($_POST['name']),
        "price" => trim($_POST['price']),
        "wholesale_price" => trim($_POST['wholesale_price']),
        "agent_price" => trim($_POST['agent_price']),
        "sale_price" => trim($_POST['sale_price']),
        "parcel" => trim($_POST['parcel']),
        "registered" => trim($_POST['registered']),
        "ems" => trim($_POST['ems']),
        "kerry" => trim($_POST['kerry']),
        "start_ship_date" => date("Y-m-d", strtotime($_POST['start_ship_date'])),
        "description" => trim($_POST['description']),
        "quantity" => trim($_POST['quantity']),
        "weight" => trim($_POST['weight']),
        "video_filename" => $vdo_filename,
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
                $full_filename = $filename . "." . $ext[count($ext) - 1]; 
                if (($_FILES["image"]["size"][$i] < 1000000) && in_array($file_extension, $validextensions)) { // 1000 B = 1 KB = 1000 KB = 1 MB 
                    uploadimg($filename, 600, 600, $path, $i);
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
            
        header("location:" . $baseUrl . "/back/product");
    }
    $db->close();
}

?>