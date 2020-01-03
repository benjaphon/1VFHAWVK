<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('Asia/Bangkok');
    $db = new database();
    $value_pi = array(
        "product_id" => trim($_POST['product_id']),
        "price" => trim($_POST['price']),
        "quantity" => trim($_POST['qty']),
        "note" => trim($_POST['note']),
        "import_date" => date('Y-m-d H:i:s'),
        "refund" => $_POST['refund']
    );
    $query_pi = $db->insert("product_import", $value_pi);

    if ($query_pi == TRUE) {

        $sql_pd = "UPDATE products SET quantity=quantity+{$_POST['qty']} WHERE id='{$_POST['product_id']}'";
        $query_pd = $db->query($sql_pd);

        header("location:" . $baseUrl . "/back/import");
    }
}

?>