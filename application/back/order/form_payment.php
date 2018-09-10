<?php

require(base_path() . "/assets/library/uploadimg.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    $full_filename = '';

    if (file_exists($_FILES['image']['tmp_name'][0]) && is_uploaded_file($_FILES['image']['tmp_name'][0])) {
        $ext = explode('.', basename($_FILES['image']['name'][0]));
        $file_extension = end($ext); 
        $filename = date('YmdHis') . md5(uniqid());
        $full_filename = $filename . "." . end($ext); 

        $validextensions = array("jpeg", "jpg", "png");
        if (($_FILES["image"]["size"][0] < 1000000) && in_array($file_extension, $validextensions)) {
            $path = base_path() . "/assets/upload/payment/";
            uploadimg($filename, 600, 600, $path, 0);
            uploadimg("thumb_" . $filename, 400, 400, $path, 0);
            uploadimg("md_" . $filename, 150, 150, $path, 0);
            uploadimg("sm_" . $filename, 70, 70, $path, 0);

        }
    }else{
        $full_filename = 'ecimage.jpg';
    }
    
    if ($_POST['pay_type']=="other") {
        $_POST['pay_type'] = trim($_POST['txt_pay_type']);
    }
    
    $value_pm = array(
        "pay_money" => trim($_POST['pay_money']),
        "detail" => trim($_POST['detail']),
        "order_id" => $_POST['order_id'],
        "pay_type" => trim($_POST['pay_type']),
        "url_picture" => $full_filename,
        "created_at" => date('Y-m-d H:i:s')
    );
    $query_pm = $db->insert("payments", $value_pm);

    if ($query_pm == TRUE) {
        $db->update("orders", array("order_status"=>"P","ship_price"=>$_POST['ship_price'] ,"total"=>$_POST['grand_total']+$_POST['ship_price']),"id='{$_POST['order_id']}'");
        header("location:" . $baseUrl . "/back/order");
    }
    $db->close();
}

?>