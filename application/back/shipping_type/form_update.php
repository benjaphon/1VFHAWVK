<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();
    
    if (isset($_POST['hidden_shipping_type'])) {
        
        foreach ($_POST['hidden_shipping_type'] as $value) {
            $query = $db->update("shipping_type", [ "type" => $_POST["shipping_type_{$value}"] ], "id={$value}");
        }

    }

    header("location:" . $baseUrl . "/back/shipping_type");
}
