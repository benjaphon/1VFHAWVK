<?php
/*
 * Name: render cart file.
 * Description: สำหรับแสดงผลสินค้าที่มีในตระกร้าแต่ละครั้งที่เกิด Action Insert/Update/Delete กับตระกร้าสินค้า ก็จะต้อง Render ไฟล์นี้เสมอ
 * Author: Benjaphon
 * Last Modified: Benjaphon
 */

ob_start();
include(base_path().'/assets/template/back/render_cart_html.php');
$var=ob_get_contents(); 
ob_end_clean();
echo $var;

?>

