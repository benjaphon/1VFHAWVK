<?php

/*
 * php code///////////**********************************************************
 */
$db = new database();

/************** Child Product *********************/

$option_product_child = array(
    "table" => "products",
    "condition" => "parent_product_id='{$_GET['id']}'"
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

        $query = $db->delete("images", "ref_id='{$rs_product_child['id']}'");

    } else {

        //error can't delete foreign key just update status
        $arr_update = array(
            "flag_status" => 0
        );

        $query_pd = $db->update("products", $arr_update, "id={$rs_product_child['id']}");
        
    }
    
}

$option_product_child = array(
    "table" => "products",
    "condition" => "parent_product_id='{$_GET['id']}' AND flag_status=0"
);
$query_product_child = $db->select($option_product_child);
$rows = $db->rows($query_product_child);

if ($rows==0) {

    $option_product = array(
        "table" => "products",
        "condition" => "id='{$_GET['id']}'"
    );
    $query_product = $db->select($option_product);
    $rs_product = $db->get($query_product);

    $option_img = array(
        "table" => "images",
        "fields" => "filename",
        "condition" => "ref_id='{$_GET['id']}' AND filetype='product'"
    );
    $query_img = $db->select($option_img);
    
    $query = $db->delete("products", "id='{$_GET['id']}'");

    if ($query == TRUE) {

        $path = base_path() . "/assets/upload/product/";
        while($rs_im = $db->get($query_img)){
            @unlink($path . $rs_im['filename']);
            @unlink($path . "thumb_" . $rs_im['filename']);
            @unlink($path . "md_" . $rs_im['filename']);
            @unlink($path . "sm_" . $rs_im['filename']);
        }
        @unlink($path . $rs_product['video_filename']);

        $query = $db->delete("images", "ref_id='{$_GET['id']}'");

    } else {

        //error can't delete foreign key just update status
        $value_pd = array(
            "flag_status" => 0
        );
        $query_pd = $db->update("products", $value_pd, "id={$_GET['id']}");

    }

} else {

    //error can't delete foreign key just update status
    $value_pd = array(
        "flag_status" => 0
    );

    $query_pd = $db->update("products", $value_pd, "id={$_GET['id']}");

}


header("location:" . $baseUrl . "/back/product");

?>
