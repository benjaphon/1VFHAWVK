<?php

$db = new database();
$path = base_path() . "/assets/upload/product/";

$query = $db->delete("products", "id='{$_POST['child_id']}'");

if ($query==TRUE) {
    
    delete_img($_POST['child_id'], "product", $path);

} else {
    //error can't delete foreign key just update status
    $arr_update = array(
        "flag_status" => 0
    );

    $query_pd = $db->update("products", $arr_update, "id={$_POST['child_id']}");
}


?>