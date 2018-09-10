<?php
/*
 * php code///////////**********************************************************
 */

unset($_SESSION[_ss . 'cart']);
unset($_SESSION[_ss . 'qty']);
unset($_SESSION[_ss . 'price']);
unset($_SESSION[_ss . 'wholesale_price']);
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
                                if ($row['start_ship_date']==null) {
                                    $product = $row['name'];
                                }else{
                                    $product = $row['name']." (".date('d-m-Y', strtotime($row['start_ship_date'])).")";
                                }

                                echo "<option value='".$row['id'].",".$row['agent_price'].",".$row['weight'].",".$row['wholesale_price']."'>".$product."</option>";
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
            <form action="<?php echo base_url(); ?>/back/order/form_create" id="order-form" method="post">
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
    $(document).on('click','.btn_delete_cart',function(){
        var url = $(this).attr("href");
        var val_product_id = $(this).attr("value");

        $.post(url, { product_id: val_product_id },function(data){
            $("#divTable").html(data);
        });

        $( ".modal-backdrop" ).remove();

    });
    /***********************/
  
    /* Calculate All Price */
    $(document).on('click','.recal',function(){
        event.preventDefault();

        var $form = $("#AddOrderDetailForm");
        var url = '<?php echo $baseUrl; ?>/back/order/update_cart';

        $.post(url, $form.serialize(),function(data){
            $("#divTable").html(data);           
        });  
    });
    /***************************/

    /* Save Order */
    $('.saveform').click(function () {
        event.preventDefault();

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
    /*************/
    $('input[type=radio][name=sender_type]').change(function () {
        $.post("<?php echo $baseUrl; ?>/back/user/check_address", { sender_type: this.value }, function(data){
            $('#sender').val(data);
        })
    });
    /***************************/

});
</script>
