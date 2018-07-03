<?php

require(base_path() . "/assets/library/uploadimg.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    if (checkimg() == TRUE) {
        $filename = date('YmdHis') . rand(0, 9);
        $type = end(explode(".", $_FILES["image"]["name"]));
        $image = $filename . "." . $type;

        $path = base_path() . "/assets/upload/product/";
        uploadimg($filename, 600, 600, $path);
        uploadimg("thumb_" . $filename, 400, 400, $path);
        uploadimg("md_" . $filename, 150, 150, $path);
        uploadimg("sm_" . $filename, 70, 70, $path);
    } else {
        $image = "ecimage.jpg";
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
        "created_at" => date('Y-m-d H:i:s'),
        "modified_at" => date('Y-m-d H:i:s')
    );
    $query_pd = $db->insert("products", $value_pd);

    if ($query_pd == TRUE) {
        header("location:" . $baseUrl . "/back/product");
    }
    mysql_close();
}

?>