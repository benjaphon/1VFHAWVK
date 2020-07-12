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
            <!-- <div class="row">
                <div class="form-group col-md-6">
                    <select id="select_status" name="status" onchange="list_order_id()" class="form-control">
                        <option value="R" selected="selected">ยังไม่ได้ชำระเงิน</option>
                        <option value="P">ชำระเงินแล้ว</option>
                        <option value="">ทั้งหมด</option>
                    </select>
                </div>
            </div> -->
            <div class="row vertical-align">
                <div class="col-xs-5">
                    <div class="form-group">
                      <label for="select_left">รหัสสั่งซื้อที่ยังไม่ได้ชำระเงิน</label>
                      <select id="select_left" size="10" class="form-control">
                        <?php while ($rs_order = $db->get($query_order)) { ?>
                            <option order_id="<?php echo $rs_order['id']; ?>"><?php echo $rs_order['id']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <!-- <span>
                        <a role="button" onclick="listbox_selectall('select_left', true)">all</a>
                         / 
                         <a role="button" onclick="listbox_selectall('select_left', false)">none</a>
                    </span> -->                
                </div>
                <div class="col-xs-2" style="text-align:center;">
                    <div class="form-group">
                        <button type="button" onclick="listbox_moveacross('select_left', 'select_right')" class="btn btn-primary">&gt;&gt;</button>
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="listbox_moveacross('select_right', 'select_left')" class="btn btn-primary">&lt;&lt;</button>
                    </div>
                </div>
                <div class="col-xs-5">
                    <div class="form-group">
                      <label for="select_right">รหัสสั่งซื้อที่ต้องการชำระเงิน</label>
                      <select id="select_right" name="order_id_selected[]" size="10" class="form-control">
                      </select>
                    </div>
                    <!-- <span>
                        <a role="button" onclick="listbox_selectall('select_right', true)">all</a>
                         / 
                         <a role="button" onclick="listbox_selectall('select_right', false)">none</a>
                    </span> -->
                </div>
            </div>
            <div class="row">
                <div id="order_detail_table" class="col-xs-12">

                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-horizontal" style="margin-top: 10px;">

                    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">สรุปรายการ</h3>
                    </div>
                    <ul class="list-group">
                        <input type="hidden" name="grand_order_total">
                        <li class="list-group-item"><strong>รวมทุกออเดอร์</strong> : <span id="grand_order_total"></span> บาท</li>
                        <!-- <li class="list-group-item"><strong>น้ำหนักรวม</strong> : <span id="grand_order_weight"></span> กรัม</li>
                        <li class="list-group-item"><strong>ค่าส่งรวม</strong> : <span id="grand_ship_price"></span> บาท</li> -->
                    </ul>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">การชำระเงิน</h3>
                    </div>
                    <div class="panel-body">

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
                                <input type="file" name="image[]" accept="image/*">
                                <input type="file" name="image[]" accept="image/*">
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
                        <button type="submit" class="btn btn-success btn-block saveform">แจ้งชำระเงิน</button>

                    </div>
                </div>
    
            </div>
        </div>
    </div>
    </form>
  </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->

<!-- AJAX Loading -->
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
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

<link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/jquery.datetimepicker.css" type="text/css" />

<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<script type='text/javascript' src="<?php echo $baseUrl; ?>/assets/js/jquery.datetimepicker.js"></script>

<script>
    // function listbox_selectall(listID, isSelect) {
    //     var listbox = document.getElementById(listID);
    //     for(var count=0; count < listbox.options.length; count++) {
    //         listbox.options[count].selected = isSelect;
    //     }
    // }

    function listbox_moveacross(sourceID, destID) {
        var src = document.getElementById(sourceID);
        var dest = document.getElementById(destID);

        for(var count=0; count < src.options.length; count++) {

            if(src.options[count].selected == true) {
                    var option = src.options[count];

                    var newOption = document.createElement("option");
                    newOption.value = option.value;
                    newOption.text = option.text;
                    newOption.setAttribute('order_id', option.getAttribute('order_id'));
                    newOption.setAttribute('total', option.getAttribute('total'));
                    newOption.setAttribute('weight', option.getAttribute('weight'));
                    newOption.setAttribute('ship_price', option.getAttribute('ship_price'));
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

        summary_price();
    }

    function summary_price() {
        var grand_order_total = 0;
        var grand_order_weight = 0;
        var grand_ship_price = 0;
        $("#select_right option").each(function(){
            grand_order_total += parseInt($(this).attr('total'));
            grand_order_weight += parseInt($(this).attr('weight'));
            grand_ship_price += parseInt($(this).attr('ship_price'));
        });

        $("input[name='grand_order_total']").val(grand_order_total);
        $('#grand_order_total').text(grand_order_total).digits();
        $('#grand_order_weight').text(grand_order_weight).digits();
        $('#grand_ship_price').text(grand_ship_price).digits();
        $('#pay_money').attr("placeholder", grand_order_total);
    }

    $('#select_left, #select_right').change(function(){
        $option_selected = $("option:selected", this);
        var value = $(this).val();

        var url = '<?php echo $baseUrl; ?>/back/payment/form_gen_order_detail';

        $.get(url, {order_id: value}, function (data) {
            $('#order_detail_table').html(data);
            var current_total = parseInt($(data).find('#hidden_grand_total').val());
            var current_weight = parseInt($(data).find('#hidden_grand_weight').val());
            var current_ship_price = parseInt($(data).find('#hidden_ship_price').val());
            var arr = [$option_selected.attr('order_id'), current_total, current_ship_price];

            $option_selected.val(arr.join(", "));

            $option_selected.attr('total', current_total);
            $option_selected.attr('weight', current_weight);
            $option_selected.attr('ship_price', current_ship_price);
        });
        
    });

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

            if ($("#select_right option").length == 0) {
                alert("กรุณาเลือกรหัสสั่งซื้อที่ต้องการชำระเงินอย่างน้อย 1 รายการค่ะ");
                return false;
            }

            $("#select_right").attr("multiple", true);
            $("#select_right option").prop("selected", true);

            $("#payment_form").submit();
        });

        // $("#ship_price").blur(function(){
        //     var ship_price = ($(this).val()) ? parseFloat($(this).val()) : 0;
            
        //     $("#grand_total").text(addCommas(payment_total));
        // });

        $('body').on('change', "input[type='file']", function() {
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
    $(document).on({
        ajaxStart: function() { $("#overlay").fadeIn(300); },
        ajaxStop: function() { $("#overlay").fadeOut(300); }    
    });
</script>


