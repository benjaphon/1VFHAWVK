<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();

    $value = array(
        "weight_id"     => trim($_POST['weight_range']),
        "boxsize_id"    => trim($_POST['box_size']),
        "parcel"        => trim($_POST['parcel']),
        "register"      => trim($_POST['register']),
        "ems"           => trim($_POST['ems']),
        "flash"         => trim($_POST['flash']),
        "jt"            => trim($_POST['jt']),
    );
    $query = $db->insert("shipping_rate", $value);

    if ($query == TRUE) {
        header("location:" . $baseUrl . "/back/shipping_rate");
    }
    
}