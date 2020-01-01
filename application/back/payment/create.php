<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();

$option_order = array(
    "table" => "orders",
    "condition" => "user_id={$_SESSION[_ss . 'id']} AND order_status='R'"
);
$query_order = $db->select($option_order);

// $sql_od = "SELECT d.*,p.id,p.name,p.kerry FROM order_details d INNER JOIN products p ";
// $sql_od .= "ON d.product_id=p.id ";
// $sql_od .="WHERE d.order_id='{$_GET['id']}' ";
// $query_od = $db->query($sql_od);
// $rows_count = $db->rows($query_od);

$title = 'แจ้งชำระเงินหลายรายการ';
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
.vertical-align {
    display: flex;
    align-items: center;
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
            <h1 class="page-header">แจ้งชำระเงินหลายรายการ</h1>
        </div>
    </div>
    <form id="payment_form" action="<?php echo $baseUrl; ?>/back/payment/form_create" method="post" enctype="multipart/form-data">
    <div class="row mt">
        <div class="col-lg-6">
            <div class="row">
                <div class="form-group col-md-6">
                    <!--R จองสินค้า
                        P ชำระเงินแล้ว
                        F รับออเดอร์
                        S ส่งแล้ว-->
                    <select id="select_status" name="status" onchange="list_order_id()" class="form-control">
                        <option value="R" selected="selected">ยังไม่ได้ชำระเงิน</option>
                        <option value="P">ชำระเงินแล้ว</option>
                        <option value="">ทั้งหมด</option>
                    </select>
                </div>
            </div>
            <div class="row vertical-align">
                <div class="col-xs-5">
                    <div class="form-group">
                      <label for="select_left">เลือกชำระเงิน (รหัสสั่งซื้อ)</label>
                      <select size="10" onclick="show_order_detail()" ondblclick="listbox_moveacross('select_left', 'select_right')" multiple class="form-control" name="order_id_choosed" id="select_left">
                        <?php while ($rs_order = $db->get($query_order)) { ?>
                            <option value="<?php echo $rs_order['id']; ?>"><?php echo $rs_order['id']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <span>
                        <a href="#" onclick="listbox_selectall('select_left', true)">all</a>
                         / 
                         <a href="#" onclick="listbox_selectall('select_left', false)">none</a>
                    </span>                    
                </div>
                <div class="col-xs-2" style="text-align:center;">
                    <span>
                        <a href="#" onclick="listbox_moveacross('select_left', 'select_right')">&gt;&gt;</a>
                        <br>
                        <a href="#" onclick="listbox_moveacross('select_right', 'select_left')">&lt;&lt;</a>
                    </span>
                </div>
                <div class="col-xs-5">
                    <div class="form-group">
                      <label for="select_right">ต้องการชำระเงิน (รหัสสั่งซื้อ)</label>
                      <select size="10" onclick="show_order_detail()" ondblclick="listbox_moveacross('select_right', 'select_left')"  multiple class="form-control" name="order_id_selected" id="select_right">
                      </select>
                    </div>
                    <span>
                        <a href="#" onclick="listbox_selectall('select_right', true)">all</a>
                         / 
                         <a href="#" onclick="listbox_selectall('select_right', false)">none</a>
                    </span>
                </div>
            </div>
            <div id="order_detail_table" class="row">
                
            </div>
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
                            <input type="text" id="pay_money" name="pay_money" value="<?php echo $grand_total_with_ship; ?>" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
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
                            <label for="detail" class="text-bold">เพิ่มเติม</label>
                            <input type="text" id="detail" name="detail" class="form-control" autocomplete="off">
                        </div>
                    </div>     


                
            </div>
        </div>
    </div>
    </form>
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
    function listbox_selectall(listID, isSelect) {
            var listbox = document.getElementById(listID);
            for(var count=0; count < listbox.options.length; count++) {
                listbox.options[count].selected = isSelect;
            }
    }

    function listbox_moveacross(sourceID, destID) {
        var src = document.getElementById(sourceID);
        var dest = document.getElementById(destID);

        for(var count=0; count < src.options.length; count++) {

            if(src.options[count].selected == true) {
                    var option = src.options[count];

                    var newOption = document.createElement("option");
                    newOption.value = option.value;
                    newOption.text = option.text;
                    newOption.selected = true;
                    try {
                            dest.add(newOption, null); //Standard
                            src.remove(count, null);
                    }catch(error) {
                            dest.add(newOption); // IE only
                            src.remove(count);
                    }
                    count--;
            }
        }
    }

    function show_order_detail() {
        
    }

    function list_order_id() {

    }

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

        // $("#ship_price").blur(function(){
        //     var ship_price = ($(this).val()) ? parseFloat($(this).val()) : 0;
            
        //     $("#grand_total").text(addCommas(payment_total));
        // });

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


