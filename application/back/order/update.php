<?php
/*
 * php code///////////**********************************************************
 */

unset($_SESSION[_ss . 'cart']);
unset($_SESSION[_ss . 'qty']);
unset($_SESSION[_ss . 'price']);
unset($_SESSION[_ss . 'note']);
unset($_SESSION[_ss . 'total_price']);

$db = new database();

$option_order = array(
    "table" => "orders",
    "condition" => "id='{$_GET['id']}' "
);
$query_order = $db->select($option_order);
$rs_order = $db->get($query_order);

$sql_od = "SELECT d.*,p.id,p.name,p.url_picture FROM order_details d INNER JOIN products p ";
$sql_od .= "ON d.product_id=p.id ";
$sql_od .="WHERE d.order_id='{$_GET['id']}' ";
$query_od = $db->query($sql_od);

//restricted process
if ($rs_order['order_status'] != 'R') {
    header("location:" . base_url() . "/back/order");
}


$title = 'แก้ไขการสั่งซื้อสินค้า';
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
#tbl_Order_Detail td:nth-child(3),
#tbl_Order_Detail td:nth-child(4),
#tbl_Order_Detail td:nth-child(5) {
    text-align: right;
}

#imagelightbox
{
    position: fixed;
    z-index: 9999;

    -ms-touch-action: none;
    touch-action: none;
}
</style>


<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->
<!--main content start-->

<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h2 class="page-header">แก้ไขการสั่งซื้อสินค้า</h2>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data saveform" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/order">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
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
                        <tr>
                            <td style='text-align:right;'>หมายเหตุ :</td>
                            <td colspan="4"><?php echo $rs_od['note']; ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="info">
                        <td colspan="5" style="text-align: right;">ค่าส่ง <strong><?php echo $rs_order['ship_price']; ?></strong> บาท</td>
                    </tr>
                    <tr class="info">
                        <td colspan="5" style="text-align: right; font-size: 16px;">
                            <strong>รวมทั้งหมด <?php echo number_format($grand_total, 2); ?> บาท</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6">
            <form action="<?php echo base_url(); ?>/back/order/form_update" id="order-form" method="post">
                <input type="hidden" name="id" value="<?php echo $rs_order['id'];?>">
                <div class="form-group clearfix">  
                    <label for="shipping_type" class="text-bold required col-xs-12">ประเภทการส่ง</label>
                    <div class="col-xs-6">
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="พัสดุธรรมดา">พัสดุธรรมดา</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="ลงทะเบียน">ลงทะเบียน</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="EMS">EMS</label>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="KERRY">KERRY</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="NIM EXPRESS">NIM EXPRESS</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="นัดรับ">นัดรับ</label>
                        </div>
                    </div>
                </div>

                <div class="form-group col-xs-12 clearfix">
                        <label for="sender" class="text-bold required">ที่อยู่ผู้ส่ง</label>
                         <div class="radio">
                          <label><input type="radio" name="sender_type" value="address_agent">ที่อยู่ตัวแทน</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="sender_type" value="address_admin">ที่อยู่ littlenow</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="sender_type" value="address_other">ที่อยู่อื่นๆ (ระบุ)</label>
                        </div>
                        <textarea class="form-control" rows="5" name="sender" id="sender" data-validation="required"><?php echo $rs_order['sender']; ?></textarea>
                </div>

                <div class="form-group col-xs-12 clearfix">
                        <label for="receiver" class="text-bold required">ที่อยู่ผู้รับ</label>
                        <textarea class="form-control" rows="5" name="receiver" id="receiver" data-validation="required"><?php echo $rs_order['receiver']; ?></textarea>
                </div>

                <div class="form-group col-xs-12 clearfix">
                        <label for="note" class="text-bold">หมายเหตุ</label>
                        <textarea class="form-control" rows="5" name="note" id="note"><?php echo $rs_order['note']; ?></textarea>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data saveform" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
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
<div id="wait" style="display:none;position: fixed; text-align: center; height: 100%; width: 100%; top: 0; right: 0; left: 0; z-index: 9999999; background-color: #000000; opacity: 0.7;">
            <span style="border-width: 0px; position: fixed; padding: 50px; background-color: #FFFFFF; font-size: 36px; left: 40%; top: 40%;">Loading ...</span>
</div>
<?php
/*
 * footer***********************************************************************
 */
require 'assets/template/back/footer.php';
/*
 * footer***********************************************************************
 */
?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/imagelightbox.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<!-- selectbox -->
<script src="<?php echo $baseUrl; ?>/assets/js/bootstrap-select.js" type="text/javascript"></script>

<script>
$(document).ready(function(){

    /* Initial */
    $('a').imageLightbox();
    $.validate();
    $(document)
      .ajaxStart(function () {
        $('#wait').show();
      })
      .ajaxStop(function () {
        $('#wait').hide();
      });

    /* Save Order */
    $('.saveform').click(function () {
        $('#order-form').submit();
    });
    /*************/
    $('input[type=radio][name=sender_type]').change(function () {
        $.post("<?php echo $baseUrl; ?>/back/user/check_address", { sender_type: this.value }, function(data){
            $('#sender').val(data);
        })
    });
    /***************************/

    $( "input[type=radio][value=<?php echo $rs_order['shipping_type']; ?>" ).prop( "checked", true );

});
</script>
