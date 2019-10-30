<?php

require(base_path() . "/assets/library/uploadimg.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    
    if ($_POST['pay_type']=="other") {
        $_POST['pay_type'] = trim($_POST['txt_pay_type']);
    }
    
    $value_pm = array(
        "pay_money" => trim($_POST['pay_money']),
        "detail" => trim($_POST['detail']),
        "order_id" => $_POST['order_id'],
        "pay_type" => trim($_POST['pay_type']),
        "created_at" => date('Y-m-d H:i:s')
    );
    $query_pm = $db->insert("payments", $value_pm);

    if ($query_pm == TRUE) {

        $payment_id = $db->insert_id();
        $path = base_path() . "/assets/upload/payment/";

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
                        "ref_id" => $payment_id,
                        "filename" => $full_filename,
                        "filetype" => "payment"
                    );
        
                    $db->insert("images", $value_img);
                }  
            }
        }

        $db->update("orders", array("order_status"=>"P","ship_price"=>$_POST['ship_price'] ,"total"=>$_POST['grand_total']+$_POST['ship_price']),"id='{$_POST['order_id']}'");
        header("location:" . $baseUrl . "/back/order");
    }
    $db->close();
}

?>