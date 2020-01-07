<?php

require(base_path() . "/assets/library/uploadimg.php");

function upload_file($input_file_name, $allowed_ext, $path)
{
    $filename = '';

    if(file_exists($_FILES[$input_file_name]['tmp_name']) && is_uploaded_file($_FILES[$input_file_name]['tmp_name'])) {
        $ext = explode('.', basename($_FILES[$input_file_name]['name']));   // Explode file name from dot(.)
        $file_extension = end($ext); // Store extensions in the variable.
        $filename = date('YmdHis') . md5(uniqid()). "." . $file_extension;     // Set the target path with a new name of image.
        
        if (($_FILES[$input_file_name]["size"] < 1000000) && in_array($file_extension, $allowed_ext)) { // 1000 B = 1 KB = 1000 KB = 1 MB 
            move_uploaded_file($_FILES[$input_file_name]["tmp_name"], $path . $filename);
        }  
    }

    return $filename;
}

function upload_video($path)
{
    $vdo_filename = '';

    if (file_exists($_FILES['file_video']['tmp_name']) && is_uploaded_file($_FILES['file_video']['tmp_name'])) {
        if ($_FILES["file_video"]["size"] < 100000000) {
            $ext_vdo = explode('.', basename($_FILES['file_video']['name'])); 
            $vdo_filename = date('YmdHis') . md5(uniqid()). "." . end($ext_vdo);
            move_uploaded_file($_FILES["file_video"]["tmp_name"], $path . $vdo_filename);
        }
    }

    return $vdo_filename;
}

function upload_img($ref_id, $type, $path)
{
    $db = new database();

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
                    "ref_id" => $ref_id,
                    "filename" => $full_filename,
                    "filetype" => $type
                );
    
                $db->insert("images", $value_img);
            }  
        }
    }
}

function upload_new_video($ref_id, $field, $table, $path)
{
    $db = new database();

    $option_vdo = array(
        "table" => $table,
        "fields" => $field,
        "condition" => "id='{$ref_id}'"
    );
    $query_vdo = $db->select($option_vdo);
    $rs_vdo = $db->get($query_vdo);

    $vdo_filename = $rs_vdo[$field];
    
    if (file_exists($_FILES['file_video']['tmp_name']) && is_uploaded_file($_FILES['file_video']['tmp_name'])) {
        if ($_FILES["file_video"]["size"] < 100000000) {
            $ext_vdo = explode('.', basename($_FILES['file_video']['name'])); 
            $vdo_filename = date('YmdHis') . md5(uniqid()). "." . end($ext_vdo);
            move_uploaded_file($_FILES["file_video"]["tmp_name"], $path . $vdo_filename);

            @unlink($path . $rs_vdo[$field]);
        }
    }

    return $vdo_filename;
}

function delete_img($ref_id, $type, $path)
{
    $db = new database();

    $option_img = array(
        "table" => "images",
        "fields" => "filename",
        "condition" => "ref_id='{$ref_id}' AND filetype='{$type}'"
    );

    $query_img = $db->select($option_img);

    while($rs_im = $db->get($query_img)){
        @unlink($path . $rs_im['filename']);
        @unlink($path . "thumb_" . $rs_im['filename']);
        @unlink($path . "md_" . $rs_im['filename']);
        @unlink($path . "sm_" . $rs_im['filename']);
    }

    return $db->delete("images", "ref_id='{$ref_id}'");
}

function duplicate_img($src_id, $des_id, $type, $path)
{
    $db = new database();

    $option_img = array(
        "table" => "images",
        "fields" => "filename",
        "condition" => "ref_id='{$src_id}' AND filetype='{$type}'"
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
            "ref_id" => $des_id,
            "filename" => $full_filename,
            "filetype" => $type
        );

        $db->insert("images", $value_img);
    }
}

function soft_delete($id, $table)
{
    $db = new database();

    $arr_update = array(
        "flag_status" => 0
    );

    $query = $db->update($table, $arr_update, "id={$id}");
}
