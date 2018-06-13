<?php

/*
 * php code///////////**********************************************************
 */
$db = new database();

$option_im = array(
    "table" => "products",
    "fields" => "url_picture",
    "condition" => "id='{$_GET['id']}'"
);
$query_im = $db->select($option_im);
$rs_im = $db->get($query_im);

$query = $db->delete("products", "id='{$_GET['id']}'");
if ($query == TRUE) {
    if ($rs_im['url_picture'] != "ecimage.jpg") {
        $path = base_path() . "/assets/upload/product/";
        @unlink($path . $rs_im['url_picture']);
        @unlink($path . "thumb_" . $rs_im['url_picture']);
        @unlink($path . "md_" . $rs_im['url_picture']);
        @unlink($path . "sm_" . $rs_im['url_picture']);
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
