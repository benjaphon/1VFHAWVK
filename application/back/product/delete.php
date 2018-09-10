<?php

/*
 * php code///////////**********************************************************
 */
$db = new database();

$option_img = array(
    "table" => "images",
    "fields" => "filename",
    "condition" => "ref_id='{$_GET['id']}' AND filetype='product'"
);
$query_img = $db->select($option_img);

$query = $db->delete("products", "id='{$_GET['id']}'");
if ($query == TRUE) {
    while($rs_im = $db->get($query_img)){
        $path = base_path() . "/assets/upload/product/";
        @unlink($path . $rs_im['filename']);
        @unlink($path . "thumb_" . $rs_im['filename']);
        @unlink($path . "md_" . $rs_im['filename']);
        @unlink($path . "sm_" . $rs_im['filename']);
    }

    $query = $db->delete("images", "ref_id='{$_GET['id']}'");
} else {
    //error can't delete foreign key just update status
    $value_pd = array(
        "flag_status" => 0
    );
    $query_pd = $db->update("products", $value_pd, "id={$_GET['id']}");
}

header("location:" . $baseUrl . "/back/product");

?>
