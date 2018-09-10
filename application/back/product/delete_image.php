<?php

/*
 * php code///////////**********************************************************
 */
$db = new database();

$option_img = array(
    "table" => "images",
    "fields" => "filename",
    "condition" => "id='{$_POST['image_id']}'"
);
$query_img = $db->select($option_img);
$rs_img = $db->get($query_img);

$query = $db->delete("images", "id='{$_POST['image_id']}'");
if ($query == TRUE) {
    $path = base_path() . "/assets/upload/product/";
    @unlink($path . $rs_img['filename']);
    @unlink($path . "thumb_" . $rs_img['filename']);
    @unlink($path . "md_" . $rs_img['filename']);
    @unlink($path . "sm_" . $rs_img['filename']);

} 

?>
