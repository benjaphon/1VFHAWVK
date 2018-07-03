<?php

require(base_path() . "/assets/library/uploadimg.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new database();
    $option_im = array(
        "table" => "products",
        "fields" => "url_picture",
        "condition" => "id='{$_POST['id']}'"
    );
    $query_im = $db->select($option_im);
    $rs_im = $db->get($query_im);
    if (checkimg() == TRUE) {
        $filename = date('YmdHis') . rand(0, 9);
        $type = end(explode(".", $_FILES["image"]["name"]));
        $image = $filename . "." . $type;

        $path = base_path() . "/assets/upload/product/";
        uploadimg($filename, 600, 600, $path);
        uploadimg("thumb_" . $filename, 400, 400, $path);
        uploadimg("md_" . $filename, 150, 150, $path);
        uploadimg("sm_" . $filename, 70, 70, $path);

        if ($rs_im['url_picture'] != "ecimage.jpg") {
            @unlink($path . $rs_im['url_picture']);
            @unlink($path . "thumb_" . $rs_im['url_picture']);
            @unlink($path . "md_" . $rs_im['url_picture']);
            @unlink($path . "sm_" . $rs_im['url_picture']);
        }
    } else {
        $image = $rs_im['url_picture'];
    }

    $value_pd = array(
        "name" => trim($_POST['name']),
        "price" => trim($_POST['price']),
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
        "url_picture" => $image,
        "modified_at" => date('Y-m-d H:i:s'),
    );
    $query_pd = $db->update("products", $value_pd, "id='{$_POST['id']}'");

    if ($query_pd == TRUE) {
        header("location:" . $baseUrl . "/back/product");
    }
    $db->close();
}

?>