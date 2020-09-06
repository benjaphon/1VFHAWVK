<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $db = new database();

    foreach ($_POST['hidden_meta'] as $id) {

        $value = isset($_POST["setting_{$id}"]) ? $_POST["setting_{$id}"] : 'false';
        $query = $db->update("settings", [ "value" => $value ], "id='{$id}'");

    }

    if ($query) {
        header("location:" . $baseUrl . "/back/settings");
    } else {
        echo "เกิดปัญหาบางอย่าง ไม่สามารถบันทึกการตั้งค่าดังกล่าวได้<br>";
        echo "<a href='/back/settings'>ย้อนกลับ</a>";
    }
}