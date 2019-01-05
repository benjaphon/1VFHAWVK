<?php
/*
 * php code///////////**********************************************************
 */
if (!isset($_GET['id'])) {
    header("location:" . $baseUrl . "/back/order");
}
$db = new database();

$sql_os = "SELECT os.*, pm.pay_type, pm.pay_money, pm.url_picture, pm.detail, pm.created_at as pay_created_at, u.username FROM orders os ";
$sql_os .= "LEFT JOIN payments pm ON pm.order_id = os.id ";
$sql_os .= "LEFT JOIN users u ON u.id = os.user_id ";
$sql_os .= "WHERE os.id='{$_GET['id']}' ORDER BY pm.created_at DESC LIMIT 1";

$query_os = $db->query($sql_os);
$rows_os = $db->rows($query_os);
if($rows_os != 1){
    header("location:" . $baseUrl . "/back/order");
}else{
    $rs_os = $db->get($query_os);
}

$sql_od = "SELECT d.*,p.id,p.name,p.start_ship_date,p.url_picture FROM order_details d INNER JOIN products p ";
$sql_od .= "ON d.product_id=p.id ";
$sql_od .="WHERE d.order_id='{$_GET['id']}' ";
$query_od = $db->query($sql_od);

$title = 'รายละเอียดการสั่งซื้อสินค้า';
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
<style>

</style>

<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">รายละเอียดการสั่งซื้อสินค้า</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="search-button btn btn-danger" href="<?php echo $baseUrl; ?>/back/order">
                    <i class="glyphicon glyphicon-circle-arrow-left"></i>
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-6">
            <table class="table" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อสินค้า</th>
                        <th style="text-align: right;">ราคา(บาท)</th>
                        <th style="text-align: right;">จำนวน</th>
                        <th style="text-align: right;">รวม</th>
                        <!--<th style='text-align: right;'>น้ำหนักรวม (กรัม)</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rs_od = $db->get($query_od)) {
                        $total_price = $rs_od['price'] * $rs_od['quantity'];
                        $total_weight = $rs_od['weight'] * $rs_od['quantity'];

                        //Select Product Picture
                        $option_img = array(
                            "table" => "images",
                            "condition" => "ref_id='{$rs_od['id']}' AND filetype='product'",
                            "order" => "id",
                            "limit" => "1"
                        );
                        $query_img = $db->select($option_img);

                        if($db->rows($query_img) > 0){
                            $rs_img = $db->get($query_img);
                            $filename_img = $rs_img['filename'];
                        }
                        else {
                            $filename_img = 'ecimage.jpg';
                        }
                        ?>
                        <tr>
                            <td>
                                <a href='<?php echo base_url(); ?>/assets/upload/product/<?php echo $filename_img; ?>' class='fancybox'>
                                    <img src="<?php echo base_url(); ?>/assets/upload/product/sm_<?php echo $filename_img; ?>">
                                </a>
                            </td>
                            <td><?php 
                                if ($rs_od['start_ship_date']==null) {
                                    echo $rs_od['name'];
                                }else{
                                    echo $rs_od['name']." (".date('d-m-Y', strtotime($rs_od['start_ship_date'])).")";
                                }
                            ?></td>
                            <td style="text-align: right;"><?php echo number_format($rs_od['price'], 2); ?></td>
                            <td style="text-align: right;"><?php echo $rs_od['quantity']; ?></td>
                            <td style="text-align: right;"><?php echo number_format($total_price, 2); ?></td>
                            <!--<td style="text-align: right;"><?php echo number_format($total_weight); ?></td>-->
                        </tr>
                        <tr>
                            <td style='text-align:right;'>รายละเอียดสินค้า :</td>
                            <td colspan="5"><?php echo $rs_od['note']; ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="info">
                        <td colspan="5" style="text-align: right;">
                            <!--<p><a href='<?php echo $baseUrl; ?>/back/order/ship_rate' target='_blank'>ตารางอัตราค่าส่ง</a></p>-->
                            <?php if (isset($rs_os['ship_price'])){ ?>
                                <h4>ค่าส่ง <?php echo number_format($rs_os['ship_price']); ?> บาท</h4>
                            <?php } else { ?>
                                <h4>ยังไม่ได้ระบุค่าส่ง</h4>
                            <?php } ?>
                            
                        </td>
                    </tr>
                    <tr class="info">
                        <td colspan="5" style="text-align: right;">
                            <h4>น้ำหนักรวม <?php echo number_format($rs_os['total_weight']); ?> กรัม</h4>
                        </td>
                    </tr>
                    <tr class="info">
                        <td colspan="5" style="text-align: right;"><h4><strong>รวมทั้งหมด <?php echo number_format($rs_os['total']); ?> บาท</strong></h4></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6">
            <div class="form-horizontal" style="margin-top: 10px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">ข้อมูลติดต่อและที่อยู่จัดสั่ง</h3>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>ประเภทการส่ง</strong> : <?php echo $rs_os['shipping_type'];?></li>
                        <li class="list-group-item"><strong>ที่อยู่ผู้ส่ง</strong> : <?php echo $rs_os['sender'];?></li>
                        <li class="list-group-item"><strong>ที่อยู่ผู้รับ</strong> : <?php echo $rs_os['receiver'];?></li>
                        <li class="list-group-item"><strong>ข้อมูลเพิ่มเติม</strong> : <?php echo $rs_os['note'];?></li>
                        <li class="list-group-item"><strong>วันที่สั่งซื้อ</strong> : <?php echo thaidate($rs_os['order_datetime']);?></li>
                        
                        <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                        <li class="list-group-item"><strong>username</strong> : <?php echo $rs_os['username'];?></li>

                            <?php if($rs_os['order_status']=='F' || $rs_os['order_status']=='S'){ ?>
                            <form class="form-inline" role="form" action="<?php echo $baseUrl; ?>/back/order/form_confirm_tracking" method="post" >
                                <input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>">
                                <li class="list-group-item"><strong>เลขพัสดุ</strong> : 
                                    <input type="text" class="form-control input-sm" name="tracking_no" data-validation="length" data-validation-length="max15">
                                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                                </li>
                            </form>
                            <?php } ?>

                        <?php } ?>

                    </ul>
                </div>
            </div>
            <?php if($rs_os['order_status']!='R'){ ?>
            <div class="form-horizontal" style="margin-top: 10px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">รายละเอียดการชำระเงิน</h3>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>ช่องทางการชำระเงิน</strong> : <?php echo $rs_os['pay_type']; ?></li>
                        <li class="list-group-item"><strong>จำนวน</strong> : <?php echo number_format($rs_os['pay_money'],2); ?></li>
                        <li class="list-group-item"><strong>เพิ่มเติม</strong> : <?php echo $rs_os['detail']; ?></li>
                        <li class="list-group-item"><strong>หลักฐานการโอน</strong> : 
                            <a href="<?php echo base_url(); ?>/assets/upload/payment/<?php echo !empty($rs_os['url_picture'])?$rs_os['url_picture']:'ecimage.jpg'; ?>" class="fancybox">
                                <img src="<?php echo base_url(); ?>/assets/upload/payment/md_<?php echo !empty($rs_os['url_picture'])?$rs_os['url_picture']:'ecimage.jpg'; ?>" class="img-responsive" alt="Responsive image">
                            </a>
                        </li>
                        <li class="list-group-item"><strong>วันที่ชำระเงิน</strong> : <?php echo thaidate($rs_os['pay_created_at'], true); ?></li>
                        <?php if($_SESSION[_ss . 'levelaccess'] == 'admin' && $rs_os['order_status']=='P') { ?>
                        <form action="<?php echo $baseUrl; ?>/back/order/form_confirm_payment" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>">
                            <li class="list-group-item">
                                <button type="submit" class="btn btn-success btn-block">ยืนยันการชำระเงิน</button>
                            </li>
                        </form>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="search-button btn btn-danger" href="<?php echo $baseUrl; ?>/back/order">
                    <i class="glyphicon glyphicon-circle-arrow-left"></i>
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
  </section><! --/wrapper -->
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
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('a.fancybox').fancybox();
    });
    $.validate();
</script>
