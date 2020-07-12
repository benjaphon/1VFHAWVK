<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();
    $values = array(
        "weight_id"     => trim($_POST['weight_range']),
        "boxsize_id"    => trim($_POST['box_size']),
        "parcel"        => trim($_POST['parcel']),
        "register"      => trim($_POST['register']),
        "ems"           => trim($_POST['ems']),
        "flash"         => trim($_POST['flash']),
        "jt"            => trim($_POST['jt']),
    );
    $query = $db->update("shipping_rate", $values, "id={$_GET['id']}");

    if ($query) {
        header("location:" . $baseUrl . "/back/shipping_rate");
    }
}
