<?php
/*
 * php code///////////**********************************************************
 */
if(!isset($_GET['id'])){
    header("location:" . $baseUrl . "/back/order");
}
$db = new database();

$option_order = array(
    "table" => "orders",
    "condition" => "id='{$_GET['id']}' "
);
$query_order = $db->select($option_order);
$rs_order = $db->get($query_order);

$sql_od = "SELECT d.*,p.id,p.name,p.url_picture,p.parcel,p.registered,p.ems FROM order_details d INNER JOIN products p ";
$sql_od .= "ON d.product_id=p.id ";
$sql_od .="WHERE d.order_id='{$_GET['id']}' ";
$query_od = $db->query($sql_od);

$title = 'รายละเอียดการชำระเงิน';
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

<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/ckeditor/ckeditor.js"></script>

<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">รายละเอียดการชำระเงิน</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data saveform" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="btn btn-warning" href="<?php echo $baseUrl; ?>/back/order/update/<?php echo $_GET['id']; ?>">
                    <i class="glyphicon glyphicon-edit"></i>
                    แก้ไขออเดอร์
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/order">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <form id="payment_form" action="<?php echo $baseUrl; ?>/back/order/form_payment" method="post" enctype="multipart/form-data">
    <div class="row mt">
        <div class="col-lg-6">
            <table class="table" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th width="150px">#</th>
                        <th>ชื่อสินค้า</th>
                        <th style="text-align: right;">ราคา(บาท)</th>
                        <th style="text-align: right;">จำนวน</th>
                        <th style="text-align: right;">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    while ($rs_od = $db->get($query_od)) {
                        $total_price = $rs_od['price'] * $rs_od['quantity'];
                        $grand_total = $total_price + $grand_total;
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo base_url(); ?>/assets/upload/product/sm_<?php echo $rs_od['url_picture']; ?>">
                            </td>
                            <td><?php echo $rs_od['name']; ?></td>
                            <td style="text-align: right;"><?php echo number_format($rs_od['price'], 2); ?></td>
                            <td style="text-align: right;"><?php echo $rs_od['quantity']; ?></td>
                            <td style="text-align: right;"><?php echo number_format($total_price, 2); ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="info">
                        <td colspan="3"></td>
                        <td colspan="2" style="text-align: right;">
                            
                            <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
                            <label for="pay_money" class="text-bold control-label required">ค่าส่ง (ตามเงื่อนไข)</label>
                            <input type="text" style="text-align: right;" id="ship_price" name="ship_price" class="form-control input-sm" width="20px" data-validation="number" data-validation-error-msg="โปรดระบุค่าส่ง" data-validation-allowing="float"
                            <?php 

                                $grand_total_with_ship = 0;

                                if ($rs_order['ship_price']==null) {
                                    if($db->rows($query_od)==1){
                                        $query_od = $db->query($sql_od);
                                        $rs_od = $db->get($query_od);
                                        if($rs_od['quantity']==1){
                                            switch ($rs_order['shipping_type']) {
                                                case 'พัสดุธรรมดา':
                                                    $grand_total_with_ship = $grand_total + $rs_od['parcel'];
                                                    echo "value='".$rs_od['parcel']."'";
                                                    break;
                                                case 'ลงทะเบียน':
                                                    $grand_total_with_ship = $grand_total + $rs_od['registered'];
                                                    echo "value='".$rs_od['registered']."'";
                                                    break;
                                                case 'EMS':
                                                    $grand_total_with_ship = $grand_total + $rs_od['ems'];
                                                    echo "value='".$rs_od['ems']."'";
                                                    break;
                                            }
                                            
                                        }
                                    }
                                }else{
                                    $grand_total_with_ship = $grand_total + $rs_order['ship_price'];
                                    echo "value='".$rs_order['ship_price']."'";
                                }

                            ?>>* สอบถามแอดมิน

                        </td>   
                    </tr>
                    <tr class="info">
                        <td colspan="5" style="text-align: right; font-size: 16px;"><strong>รวมทั้งหมด <span id="grand_total"><?php echo number_format($grand_total_with_ship, 2); ?></span> บาท</strong></td>
                    </tr>
                    <tr class="info">
                        <td colspan="5">ที่อยู่ผู้ส่ง :<br><?php echo $rs_order['sender']; ?></td>
                    </tr>
                    <tr class="info">
                        <td colspan="5">ที่อยู่ผู้รับ :<br><?php echo $rs_order['receiver']; ?></td>
                    </tr>
                    <tr class="info">
                        <td colspan="5">ประเภทการส่ง :<br><?php echo $rs_order['shipping_type']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6">
            <div class="form-horizontal" style="margin-top: 10px;">
                    <input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>">


                    <div class="form-group col-xs-12 clearfix">
                        <h3>การชำระเงิน</h3>
                    </div>
                    <div class="form-group clearfix">  
                        <label for="pay_type" class="text-bold col-xs-12">ช่องทางการชำระเงิน</label>
                        <div class="col-xs-6">
                            <div class="radio">
                              <label><input type="radio" name="pay_type" value="กสิกรไทย" data-validation="required">กสิกรไทย</label>
                            </div>
                            <div class="radio">
                              <label><input type="radio" name="pay_type" value="กรุงไทย">กรุงไทย</label>
                            </div>
                            <div class="radio">
                              <label><input type="radio" name="pay_type" value="other"><input type="text" placeholder="Other" class="form-control" name="txt_pay_type"></label>

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="radio">
                              <label><input type="radio" name="pay_type" value="ไทยพาณิชย์">ไทยพาณิชย์</label>
                            </div>
                            <div class="radio">
                              <label><input type="radio" name="pay_type" value="พร้อมเพย์">พร้อมเพย์</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <div class="col-sm-6">
                            <label for="pay_money" class="text-bold control-label required">จำนวนเงิน</label>
                            <input type="text" id="pay_money" name="pay_money" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-xs-6">
                            <label for="url_picture" class="control-label">รูปภาพ</label>
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                    <div class="form-group clearfix"">
                        <div class="col-sm-6">
                            <label for="detail" class="text-bold">เพิ่มเติม</label>
                            <input type="text" id="detail" name="detail" class="form-control" autocomplete="off">
                        </div>
                    </div>     


                
            </div>
        </div>
    </div>
    </form>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data saveform" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="btn btn-warning" href="<?php echo $baseUrl; ?>/back/order/update/<?php echo $_GET['id']; ?>">
                    <i class="glyphicon glyphicon-edit"></i>
                    แก้ไขออเดอร์
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/order">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
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

<link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/jquery.datetimepicker.css" type="text/css" />

<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<script type='text/javascript' src="<?php echo $baseUrl; ?>/assets/js/jquery.datetimepicker.js"></script>

<script>
    $(document).ready(function () {
        $(".saveform").click(function () {
            $("#payment_form").submit();
            return false;
        });

        $("#ship_price").blur(function(){
            var ship_price = ($(this).val()) ? parseFloat($(this).val()) : 0;
            
            $("#grand_total").text(ship_price+<?php echo $grand_total; ?>);
        });
    });
    $.validate();
</script>
