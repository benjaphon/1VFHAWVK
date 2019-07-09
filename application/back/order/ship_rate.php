<?php
/*
 * php code///////////**********************************************************
 */

$db = new database();
$option_ship = array(
    "table" => "shipping_rate"
);

$query_ship = $db->select($option_ship);

$title = 'ตารางอัตราค่าส่ง';
/*
 * php code///////////**********************************************************
 */

/*
 * header***********************************************************************
 */
require 'assets/template/back/header.php';
/*
 * header***********************************************************************
 */
?>

<section id="main-content">
  <section class="wrapper">
        <div class="row mt">
            <div class="col-lg-12">
                <h1 class="page-header">ตารางอัตราค่าส่ง</h1>
            </div>
        </div>
        <div class="row mt">
            <div class="col-lg-6">
            <table class="table" style="text-align:center; font-size: 12px;">
                <thead>
                    <tr>
                        <th style="text-align: center;">น้ำหนักต่ำสุด (กรัม)</th>
                        <th style="text-align: center;">น้ำหนักสูงสุด (กรัม)</th>
                        <th style="text-align: center;">พัสดุธรรมดา (บาท)</th>
                        <th style='text-align: center;'>ลงทะเบียน (บาท)</th>
                        <th style='text-align: center;'>EMS (บาท)</th>
						 <th style='text-align: center;'>Flash Express (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rs_ship = $db->get($query_ship)) { ?>
                    <tr>
                        <td style="text-align: right;"><?php echo $rs_ship['min_wg']; ?></td>
                        <td style="text-align: right;"><?php echo $rs_ship['max_wg']; ?></td>
                        <td style="text-align: right;"><?php echo $rs_ship['parcel']; ?></td>
                        <td style="text-align: right;"><?php echo $rs_ship['register']; ?></td>
                        <td style="text-align: right;"><?php echo $rs_ship['EMS']; ?></td>
						<td style="text-align: right;"><?php echo $rs_ship['Flash']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </section><!--/wrapper -->
</section><!-- /MAIN CONTENT -->



<?php
/*
 * footer***********************************************************************
 */
require 'assets/template/back/footer.php';
/*
 * footer***********************************************************************
 */
?>