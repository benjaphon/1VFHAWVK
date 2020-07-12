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
$option_pd = array(
    "table" => "products",
    "condition" => "quantity > 0 AND flag_status = 1"
);

$query_pd = $db->select($option_pd);

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
            <h1 class="page-header">รายละเอียดการสั่งซื้อสินค้า</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data saveform" href="#">
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

                                echo "<option value='{$row['id']}'>{$product_name}</option>";
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
            <form action="<?php echo base_url(); ?>/back/order/form_create" id="order-form" method="post" enctype="multipart/form-data">
                <div class="form-group clearfix">  
                    <label for="shipping_type" class="text-bold required col-xs-12">ประเภทการส่ง</label>
                    <div class="col-xs-6">
                        <div class="radio">
                          <label><input type="radio" id="rdo_parcel" name="shipping_type" value="พัสดุธรรมดา" checked>พัสดุธรรมดา</label>
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
                          <label><input type="radio" name="shipping_type" value="FLASH EXPRESS">FLASH EXPRESS</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="shipping_type" value="Shopee">Shopee (ส่งกับ J&T เท่านั้น)</label>
                        </div>
						<div class="radio">
                          <label><input type="radio" name="shipping_type" value="J&T">'J&T'</label>
                        </div>
                    </div>
                </div>

                <div id="div_cover_page_file" style="display:none;" class="form-group col-xs-12 clearfix">
                    <label for="cover_page_file" class="text-bold required">ใบปะหน้า</label>
                    <input type="file" id="cover_page_file" name="cover_page_file" accept="application/pdf, image/jpg, image/jpeg, image/png">
                    <small class="text-muted">แนบใบปะหน้า</small>
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
                        <textarea class="form-control" rows="5" name="sender" id="sender" data-validation="required"></textarea>
                </div>

                <div class="form-group col-xs-12 clearfix">
                        <label for="receiver" class="text-bold required">ที่อยู่ผู้รับ</label>
                        <textarea class="form-control" rows="5" name="receiver" id="receiver" data-validation="required"></textarea>
                </div>

                <div class="form-group col-xs-12 clearfix">
                        <label for="note" class="text-bold">ข้อมูลเพิ่มเติม</label>
                        <textarea class="form-control" rows="5" name="note" id="note"></textarea>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data saveform" href="#">
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
    //$("#tbl_Order_Detail").DataTable();

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

        var $form = $("#AddOrderDetailForm");
        var url = '<?php echo $baseUrl; ?>/back/order/update_cart';

        //Cover Page File
        var check_1 = check_file($("input[name=cover_page_file]")[0]);
        if (!check_1) {
            return false;
        }

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
            $('#div_cover_page_file').show();
            $('#cover_page_file').attr('data-validation', 'required');

            
            old_sender_type = $('input[name=sender_type]:checked').val();
            $('input[name=sender_type][value=address_other]').prop('checked', true);
            $('input[name=sender_type]:not(:checked)').prop( "disabled", true );
            
        }

        //Shipping Type Shopee Cancel
        if (shipping_type_old_val=='Shopee') {
            $('#sender, #receiver').val('').attr('readonly', false)
            $('#div_cover_page_file').hide();
            $('#cover_page_file').removeAttr('data-validation');

            $("input[name=sender_type][value='"+old_sender_type+"']").prop("checked", true).change();
            $('input[name=sender_type]').prop( "disabled", false );
        }

        shipping_type_old_val = $(this).val();

    });
    /*************/
    $('input[name=sender_type]').change(function () {
        $.post("<?php echo $baseUrl; ?>/back/user/check_address", { sender_type: this.value }, function(data){
            $('#sender').val(data);
        });
    });
    /***************************/

    $('body').on('change', 'input[type=file]', function() {

        return check_file(this);

    });

});
</script>
