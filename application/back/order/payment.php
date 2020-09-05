<?php
unset($_SESSION[_ss . 'cart']);
unset($_SESSION[_ss . 'qty']);
/*
 * php code///////////**********************************************************
 */
if(!isset($_GET['id'])){
    header("location:" . $baseUrl . "/back/order");
}
$db = new database();
$_SESSION[_ss . 'cart'] = array();
$_SESSION[_ss . 'qty'][] = array();


$option_order = array(
    "table" => "orders",
    "condition" => "id='{$_GET['id']}' "
);
$query_order = $db->select($option_order);
$rs_order = $db->get($query_order);

$sql_od = "SELECT d.*,p.id,p.name,p.kerry FROM order_details d INNER JOIN products p ";
$sql_od .= "ON d.product_id=p.id ";
$sql_od .="WHERE d.order_id='{$_GET['id']}' ";
$query_od = $db->query($sql_od);
$rows_count = $db->rows($query_od);

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
                        <!--<th style='text-align: right;'>น้ำหนักรวม (กรัม)</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $product_qty = 0;
                    $kerry_shipping = 0;
                    $grand_total = 0;
                    $grand_total_weight = 0;
                    while ($rs_od = $db->get($query_od)) {
                        $product_qty = $rs_od['quantity'];
                        $kerry_shipping = $rs_od['kerry'];

                        $total_price = $rs_od['price'] * $rs_od['quantity'];
                        $grand_total += $total_price;

                        $total_weight = $rs_od['weight'] * $rs_od['quantity'];
                        $grand_total_weight += $total_weight;

                        array_push($_SESSION[_ss . 'cart'], $rs_od['product_id']);
                        $key = array_search($rs_od['product_id'], $_SESSION[_ss . 'cart']);
                        $_SESSION[_ss . 'qty'][$key] = $rs_od['quantity'];

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
                                <a href='<?php echo base_url(); ?>/assets/upload/product/<?php echo $filename_img; ?>' class="fancybox">
                                    <img src="<?php echo base_url(); ?>/assets/upload/product/sm_<?php echo $filename_img; ?>">
                                </a>
                            </td>
                            <td><?php echo $rs_od['name']; ?></td>
                            <td style="text-align: right;"><?php echo number_format($rs_od['price'], 2); ?></td>
                            <td style="text-align: right;"><?php echo $rs_od['quantity']; ?></td>
                            <td style="text-align: right;"><?php echo number_format($total_price, 2); ?></td>
                            <!--<td style="text-align: right;"><?php echo number_format($total_weight); ?></td>-->
                        </tr>
                        <tr>
                            <td style='text-align:right;'>รายละเอียดสินค้า :</td>
                            <td colspan="5"><?php echo $rs_od['note']; ?></td>
                        </tr>
                    <?php } 

                    $isCal = true;

                    if ($rs_order['ship_price']==null) {

                        $shipping_rate = shipping_calculation();
                        $shipping_type = $rs_order['shipping_type'];

                        if ($rows_count > 1 || $product_qty > 1){
                            $kerry_shipping = 0;
                        }

                        $mapping = [
                            "พัสดุธรรมดา" => $shipping_rate['parcel'],
                            "ลงทะเบียน" => $shipping_rate['register'],
                            "EMS" => $shipping_rate['ems'],
                            "FLASH EXPRESS" => $shipping_rate['flash'],
                            "J&T" => $shipping_rate['jt'],
                            "KERRY" => $kerry_shipping,
                            "CoverPage" => $shipping_rate['coverpage']
                        ];

                        $shipping_fees = $mapping[$shipping_type];

                        if ($shipping_fees == -1) {
                            $shipping_fees = 0;
                            $isCal = false;
                        }
                    
                    } else {
                        $shipping_fees = $rs_order['ship_price'];
                    }

                    $grand_total_with_ship = $grand_total + $shipping_fees;

                    ?>
                    <tr class="info">
                        <td colspan="3"></td>
                        <td colspan="2" style="text-align: right;">
                            <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
                            <label for="pay_money" class="text-bold control-label required">ค่าส่ง (ตามเงื่อนไข)<!--(<a href='<?php echo $baseUrl; ?>/back/order/ship_rate' target='_blank'>ตารางอัตราค่าส่ง</a>)--></label>
                            <input type="text" style="text-align: right;" id="ship_price" name="ship_price" class="form-control input-sm" width="20px" data-validation="number" data-validation-error-msg="โปรดระบุค่าส่ง" data-validation-allowing="float" placeholder="<?php echo $shipping_fees; ?>"  value="<?php echo $shipping_fees; ?>">
                        </td>
                    </tr>
                    <tr class="info">
                        <td colspan="5" style="text-align: right;">
                            <h4>
                                <?php if ($isCal){ ?>
                                    <p>น้ำหนักรวม <?php echo number_format($grand_total_weight); ?> กรัม กล่องไซต์ <?php echo $rs_order['boxsize_code']; ?></p>
                                <?php } else { ?>
                                    <p>ไม่สามารถคำนวณค่าส่งได้ โปรดสอบถามค่าส่งจากแอดมิน!</p>
                                <?php } ?>
                            </h4>
                            
                        </td>
                    </tr>
                    <tr class="info">
                        <td colspan="5" style="text-align: right;"><h4><strong>รวมทั้งหมด <span id="grand_total"><?php echo number_format($grand_total_with_ship); ?></span> บาท</strong></h4></td>
                    </tr>
                    <tr class="info">
                        <td colspan="5">
                            ที่อยู่ผู้ส่ง :<br><?php echo $rs_order['sender']; ?>
                        </td>
                    </tr>
                    <tr class="info">
                        <td colspan="5">
                            ที่อยู่ผู้รับ :<br><?php echo $rs_order['receiver']; ?>
                        </td>
                    </tr>

                    <tr class="info">
                        <td colspan="5">ประเภทการส่ง :<br><?php echo $rs_order['shipping_type']; ?></td>
                    </tr>

                    <?php if (!empty($rs_order['cover_page_filename'])) { ?>
                        <tr class="info">
                            <td colspan="5">
                                <a class="fancybox"  href="<?php echo $baseUrl ?>/assets/upload/order/<?php echo $rs_order['cover_page_filename']; ?>" role="button">ใบปะหน้า</a>
                                <a download="<?php echo $rs_order['cover_page_filename']; ?>" href="<?php echo $baseUrl ?>/assets/upload/order/<?php echo $rs_order['cover_page_filename']; ?>"> (ดาวน์โหลด)</a>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
        <div class="col-lg-6">
            <div class="form-horizontal" style="margin-top: 10px;">
                <input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>">

                <div class="form-group col-xs-12 clearfix">
                    <h3>การชำระเงิน</h3>
                    <h5>ชื่อบัญชี  ภัทราตรี ทิพวัจนา</h5>
                </div>
                <div class="form-group clearfix">  
                    <label for="pay_type" class="text-bold col-xs-12">ช่องทางการชำระเงิน</label>
                    <div class="col-xs-6">
                        <div class="radio">
                            <label><input type="radio" name="pay_type" value="กสิกรไทย" data-validation="required">กสิกรไทย   050-2-61878-4</label>
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
                            <label><input type="radio" name="pay_type" value="พร้อมเพย์">พร้อมเพย์ 0897818110</label>
                        </div>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-sm-6">
                        <label for="pay_money" class="text-bold control-label required">จำนวนเงิน</label>
                        <input type="text" id="pay_money" name="pay_money" placeholder="<?php echo $grand_total_with_ship; ?>" value="<?php echo $grand_total_with_ship; ?>" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-xs-6">
                        <label for="url_picture" class="control-label">รูปภาพ</label>
                        <input type="file" name="image[]" id="image" accept="image/*">
                        <input type="file" name="image[]" id="image" accept="image/*">
                    </div>
                </div>
                
                <div class="form-group clearfix">
                    <div class="col-sm-6">
                        <label for="deduct" class="text-bold">หักยอดค้าง</label>
                        <input type="text" id="deduct" name="deduct" class="form-control input-sm" autocomplete="off" data-validation="number" data-validation-allowing="float" value="0">
                    </div>
                </div>  
                <div class="form-group clearfix">
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
  </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->

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
        $('a.fancybox').fancybox();
        $(".saveform").click(function () {
            if ($("input[name='image[]']")[0].files && $("input[name='image[]']")[0].files[0]) {
                var filename = $("input[name='image[]']")[0].files[0].name;
                var extension = filename.replace(/^.*\./, '').toLowerCase();
                switch (extension) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        break;
                    default:
                        alert("นามสกุลไฟล์ไม่ถูกต้องค่ะ");
                        return false;
                }

                if($("input[name='image[]']")[0].files[0].size > 1000000){
                    alert("ขนาดไฟล์ห้ามใหญ่เกิน 1MB ค่ะ");
                    return false;
                }
            }

            $("#payment_form").submit();
        });

        $("#ship_price").blur(function(){
            var ship_price = ($(this).val()) ? parseFloat($(this).val()) : 0;
            
            $("#grand_total").text(addCommas(ship_price+<?php echo $grand_total; ?>));
        });

        $('body').on('change', '#image', function() {
            if (this.files && this.files[0]) {

                var extension = this.files[0].name.replace(/^.*\./, '').toLowerCase();
                switch (extension) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        break;
                    default:
                        alert("นามสกุลไฟล์ไม่ถูกต้องค่ะ");
                        return false;
                }

                if(this.files[0].size > 1000000){
                    alert("ขนาดไฟล์ห้ามใหญ่เกิน 1MB ค่ะ");
                    return false;
                }
            }
        });
    });
    $.validate();
</script>


