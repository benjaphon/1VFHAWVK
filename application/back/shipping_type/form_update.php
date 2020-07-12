<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();

    $db->update("shipping_type", ["is_ws" => 0], true);
    
    if (isset($_POST['shipping_type'])) {
        
        for ($key = 0; $key < count($_POST['shipping_type']); $key++) {
           
            $query = $db->update("shipping_type", [ "is_ws" => true ], "id={$_POST['shipping_type'][$key]}");
            
        }
    }

    header("location:" . $baseUrl . "/back/shipping_type");
}
