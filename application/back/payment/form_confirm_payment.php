<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    

    $db = new database();
    $sql = "UPDATE payment_bulks AS pm_b ";
    $sql .= "INNER JOIN payments AS pm ON pm_b.id = pm.bulk_id ";
    $sql .= "INNER JOIN orders AS o ON pm.order_id = o.id ";
    $sql .= "SET o.order_status='F' ";
    $sql .= "WHERE pm_b.id = {$_POST['payment_bulk_id']}";

    $query = $db->query($sql);
    header("location:" . $baseUrl . "/back/payment");
    mysql_close();
}

?>