<?php
/*
 * php code///////////**********************************************************
 */

unset($_SESSION[_ss . 'cart']);
unset($_SESSION[_ss . 'qty']);
unset($_SESSION[_ss . 'price']);
unset($_SESSION[_ss . 'wholesale_price']);
unset($_SESSION[_ss . 'agent_price']);
unset($_SESSION[_ss . 'sale_price']);
unset($_SESSION[_ss . 'weight']);
unset($_SESSION[_ss . 'note']);
unset($_SESSION[_ss . 'total_price']);
unset($_SESSION[_ss . 'total_weight']);
unset($_SESSION[_ss . 'temp_qty']);

$db = new database();

$option_order = array(
    "table" => "orders",
    "condition" => "id='{$_GET['id']}' "
);
$query_order = $db->select($option_order);
$rs_order = $db->get($query_order);

$option_pd = array(
    "table" => "products",
    "condition" => "quantity > 0 AND flag_status = 1"
);

$query_pd = $db->select($option_pd);

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
        <form class="form-horizontal" id="AddOrderDetailForm">
                <div class="form-group">
                    <label class="col-sm-1 control-label">สินค้า</label>
                    <div class="col-sm-7">
                        <select class="selectpicker form-control" data-live-search="true" name="product_id">
                            <?php while ($row = $db->get($query_pd)){
                                
                                $product_name = $row['name'];

                                if (isset($row['parent_product_id'])) {
                                    $option_pd_parent = array(
                                        "table" => "products",
                                        "condition" => "id={$row['parent_product_id']}"
                                    );

                                    $query_pd_parent = $db->select($option_pd_parent);
                                    $rs_pd_parent = $db->get($query_pd_parent);

                                    $product_name = $rs_pd_parent['name'] . ' ' . $row['name'];
                                }

                                if (isset($row['start_ship_date'])) {
                                    $product_name .= " (".date('d-m-Y', strtotime($row['start_ship_date'])).")";
                                }

                                echo "<option value='".$row['id'].",".$row['agent_price'].",".$row['weight'].",".$row['wholesale_price'].",".$row['sale_price']."'>".$product_name."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" value="1" name="qty" autocomplete="off" name="qty" placeholder="ใส่จำนวน" data-validation="number">
                    </div>
                    <div class="col-sm-2">
                         <button type="button" class="btn btn-success btn-block add-cart">เพิ่ม</button>
                    </div>
                </div>
                <div id="divTable">
                    <div class='alert alert-danger' role='alert' style='margin:15px;'>
                        ไม่มีสินค้าในตะกร้าสินค้า <b>กรุณาเพิ่มสินค้าด้านบน</b>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            <form action="<?php echo base_url(); ?>/back/order/form_update" id="order-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $rs_order['id'];?>">
                <div class="form-group clearfix">  
                    <label for="shipping_type" class="text-bold required col-xs-12">ประเภทการส่ง</label>
                    <div class="col-xs-6">
                        <div class="radio">
                          <label><input type="radio" id="rdo_parcel" name="shipping_type" value="พัสดุธรรมดา">พัสดุธรรมดา</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" id="rdo_register" name="shipping_type" value="ลงทะเบียน">ลงทะเบียน</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="EMS">EMS</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="shipping_type" value="อื่นๆ">อื่นๆ</label>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="KERRY">KERRY</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="FLASH EXPRESS">เอกชน (สำหรับสินค้าจำนวนมาก แบบสต็อค)</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="shipping_type" value="Shopee">Shopee</label>
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
                          <label><input type="radio" name="sender_type" value="address_other" data-validation="required">ที่อยู่อื่นๆ (ระบุ)</label>
                        </div>
                        <textarea class="form-control" rows="5" name="sender" id="sender" data-validation="required"><?php echo $rs_order['sender']; ?></textarea>
                        
                        <?php if (!empty($rs_order['sender_filename'])) { ?>
                            <br>
                            <a class="fancybox"  href="<?php echo $baseUrl ?>/assets/upload/order/<?php echo $rs_order['sender_filename']; ?>" role="button">ไฟล์แนบ ที่อยู่ผู้ส่ง</a>
                            <a download="<?php echo $rs_order['sender_filename']; ?>" href="<?php echo $baseUrl ?>/assets/upload/order/<?php echo $rs_order['sender_filename']; ?>"> (ดาวน์โหลด)</a>
                            <br><br>
                            <input type="hidden" name="sender_filename_hidden" value="<?php echo $rs_order['sender_filename']; ?>">
                        <?php } ?>

                        <input type="file" name="sender_file" id="sender_file" style="display:none" accept="application/pdf, image/jpg, image/jpeg, image/png">
                </div>

                <div class="form-group col-xs-12 clearfix">
                        <label for="receiver" class="text-bold required">ที่อยู่ผู้รับ</label>
                        <textarea class="form-control" rows="5" name="receiver" id="receiver" data-validation="required"><?php echo $rs_order['receiver']; ?></textarea>     

                        <?php if (!empty($rs_order['receiver_filename'])) { ?>
                            <br>
                            <a class="fancybox" href="<?php echo $baseUrl ?>/assets/upload/order/<?php echo $rs_order['receiver_filename']; ?>" role="button">ไฟล์แนบ ที่อยู่ผู้รับ</a>
                            <a download="<?php echo $rs_order['receiver_filename']; ?>" href="<?php echo $baseUrl ?>/assets/upload/order/<?php echo $rs_order['receiver_filename']; ?>"> (ดาวน์โหลด)</a>
                            <br><br>
                            <input type="hidden" name="receiver_filename_hidden" value="<?php echo $rs_order['receiver_filename']; ?>">
                        <?php } ?>
                          
                        <input type="file" name="receiver_file" id="receiver_file" style="display:none" accept="application/pdf, image/jpg, image/jpeg, image/png">
                </div>

                <div class="form-group col-xs-12 clearfix">
                        <label for="note" class="text-bold">ข้อมูลเพิ่มเติม</label>
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
  </section><!--/wrapper -->
</section><!-- /MAIN CONTENT -->

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
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<!-- selectbox -->
<script src="<?php echo $baseUrl; ?>/assets/js/bootstrap-select.js" type="text/javascript"></script>

<script>

function check_file($input_file) {

    var check = true;
    var file_list = $input_file.files

    if (file_list && file_list[0]) {

        var file = file_list[0];

        switch (file.type) {
            case 'application/pdf':
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/png':
                break;
            default:
                alert("นามสกุลไฟล์ไม่ถูกต้องค่ะ");
                check = false;
        }

        if(file.size > 1000000){
            alert("ขนาดไฟล์ห้ามใหญ่เกิน 1MB ค่ะ");
            check = false;
        }

    }

    return check;

}

$(document).ready(function(){

    /* Initial */
    $('a.fancybox').fancybox();
    $.validate();
    $(document).on({
        ajaxStart: function() { $("#overlay").fadeIn(300); },
        ajaxStop: function() { $("#overlay").fadeOut(300); }    
    });

    var url = '<?php echo $baseUrl; ?>/back/order/temp_cart';

    $.post(url, { id:'<?php echo $_GET['id'] ?>' }, function(data){
        $("#divTable").html(data);           
    });

    $("input[name=qty]").select();

    /* Element Event */

    $("select[name=product_id]").change(function(){
        $("input[name=qty]").select();
    })

    $.ajaxSetup({
        type: 'POST',
        cache: false,
        headers: { "cache-control": "no-cache" }
    });

    /*****************/

    /* Add Product Detail */
    $(document).on('click','.add-cart',function(){
        event.preventDefault();

        var $form = $("#AddOrderDetailForm");
        var url = '<?php echo $baseUrl; ?>/back/order/add_cart';

        $.post(url, $form.serialize(), function(data){
            $("#divTable").html(data);           
        });

        $("input[name=qty]").select();
    });
    /***********************/

    /* Delete Product Detail */
    $(document).on('click','.btn_delete_cart',function(e){

        e.preventDefault();

        var url = $(this).attr("href");
        var val_product_id = $(this).attr("value");

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.value) {

                $.post(url, { product_id: val_product_id },function(data){
                    $("#divTable").html(data);
                });
            }
        });

    });
    /***********************/
  
    /* Calculate All Price */
    $(document).on('click','.recal',function(event){
        event.preventDefault();

        var $form = $("#AddOrderDetailForm");
        var url = '<?php echo $baseUrl; ?>/back/order/update_cart';

        $.post(url, $form.serialize(),function(data){
            $("#divTable").html(data);           
        });  
    });
    /***************************/

   /* Save Order */
   $('.saveform').click(function (event) {
        event.preventDefault();

        //Sender File
        if (!$('input[name=sender_filename_hidden]').val()) {
            var check_1 = check_file($("input[name=sender_file]")[0]);
            if (!check_1) {
                return false;
            }
        }
        
        //Receiver File
        if (!$('input[name=receiver_filename_hidden]').val()) {
            var check_2 = check_file($("input[name=receiver_file]")[0]);
            if (!check_2) {
                return false;
            }
        }
        
        var $form = $("#AddOrderDetailForm");
        var url = '<?php echo $baseUrl; ?>/back/order/update_cart';
        //update cart
        $.post(url, $form.serialize(),function(data){
            $("#divTable").html(data);
            //save order
            $.post("<?php echo $baseUrl; ?>/back/order/check_stock", "",function(data){
                if (data) {
                    alert(data);
                }else{
                    $('#order-form').submit();
                }     
            });

        });  
    });
    /****************************/
    var shipping_type_old_val;
    var old_sender_type;
    $('input[name=shipping_type]').change(function () {

        //Shipping Type Shopee Set
        if ($(this).val()=='Shopee') {
            $('#sender, #receiver').val('shopee ข้อมูลตามใบปะหน้า').attr('readonly', true);

            if (!$('input[name=sender_filename_hidden]').val()) {
                $('#sender_file').attr('data-validation', 'required');
            }

            if (!$('input[name=receiver_filename_hidden]').val()) {
                $('#receiver_file').attr('data-validation', 'required');
            }

            $('#sender_file, #receiver_file').show();
            
            old_sender_type = $('input[name=sender_type]:checked').val();
            $('input[name=sender_type][value=address_other]').prop('checked', true);
            $('input[name=sender_type]:not(:checked)').prop( "disabled", true );
            
        }

        //Shipping Type Shopee Cancel
        if (shipping_type_old_val=='Shopee') {
            $('#sender, #receiver').val('').attr('readonly', false)
            $('#sender_file, #receiver_file').removeAttr('data-validation').hide();

            $("input[name=sender_type][value='"+old_sender_type+"']").prop("checked", true).change();
            $('input[name=sender_type]').prop( "disabled", false );
        }

        shipping_type_old_val = $(this).val();

    });
    /*************/
    $('input[name=sender_type]').change(function () {
        $.post("<?php echo $baseUrl; ?>/back/user/check_address", { sender_type: this.value }, function(data){
            $('#sender').val(data);
        })
    });
    /***************************/

    $( "input[name=shipping_type][value='<?php echo $rs_order['shipping_type']; ?>']" ).prop( "checked", true ).change();;
    $( "input[name=sender_type][value='<?php echo $rs_order['sender_type']; ?>']" ).prop( "checked", true );

    $('body').on('change', 'input[type=file]', function() {

        return check_file(this);

    });

});
</script>
