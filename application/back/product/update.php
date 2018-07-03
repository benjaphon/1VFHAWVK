<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$option_pd = array(
    "table" => "products",
    "condition" => "id='{$_GET['id']}' "
);
$query_pd = $db->select($option_pd);
$rs_pd = $db->get($query_pd);

$title = 'แก้ไขสินค้า : ' .$rs_pd['name'];
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
<link href="<?php echo $baseUrl; ?>/assets/css/jquery-ui.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">แก้ไขข้อมูล <?php echo $rs_pd['name']; ?></h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/product">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="product-form" action="<?php echo $baseUrl; ?>/back/product/form_update" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $rs_pd['id'];?>">
                    <div class="form-group">
                        <label for="product_image" class="col-sm-2 control-label required">รูปภาพประจำสินค้า</label>
                        <div class="col-sm-4">
                            <img src="<?php echo $baseUrl ?>/assets/upload/product/md_<?php echo $rs_pd['url_picture'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">รูปภาพใหม่</label>
                        <div class="col-sm-4">
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label required">ชื่อสินค้า</label>
                        <div class="col-sm-4">
                            <input type="text" id="name" name="name" class="form-control input-sm" data-validation="required" value="<?php echo $rs_pd['name']; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label required">ราคา</label>
                        <div class="col-sm-4">
                            <input type="text" id="price" name="price" class="form-control input-sm" data-validation="number" data-validation-allowing="float" value="<?php echo $rs_pd['price']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="agent_price" class="col-sm-2 control-label required">ราคา ตท.</label>
                        <div class="col-sm-4">
                            <input type="text" id="agent_price" name="agent_price" class="form-control input-sm" data-validation="number" data-validation-allowing="float" value="<?php echo $rs_pd['agent_price']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sale_price" class="col-sm-2 control-label required">ราคาขาย</label>
                        <div class="col-sm-4">
                            <input type="text" id="sale_price" name="sale_price" class="form-control input-sm" data-validation="number" data-validation-allowing="float" value="<?php echo $rs_pd['sale_price']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ค่าส่ง (ธรรมดา/ลงทะเบียน/EMS/KERRY)</label>
                        <div class="col-sm-1">
                            <input type="text" name="parcel" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['parcel']; ?>" data-validation-allowing="float">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="registered" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['registered']; ?>" data-validation-allowing="float">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ems" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['ems']; ?>" data-validation-allowing="float">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="kerry" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['kerry']; ?>" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_ship_date" class="col-sm-2 control-label">วันที่ส่งได้</label>
                        <div class="col-sm-4">
                            <input type="text" id="start_ship_date" name="start_ship_date" class="form-control input-sm" value="<?php echo date('d-m-Y', strtotime($rs_pd['start_ship_date'])); ?>" autocomplete='off'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-sm-2 control-label">จำนวน</label>
                        <div class="col-sm-4">
                            <input type="text" id="quantity" name="quantity" value="<?php echo $rs_pd['quantity']; ?>" class="form-control input-sm" data-validation="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight" class="col-sm-2 control-label">น้ำหนัก</label>
                        <div class="col-sm-4">
                            <input type="text" id="weight" name="weight" value="<?php echo $rs_pd['weight']; ?>" class="form-control input-sm" data-validation="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="editor" name="description" class="form-control input-sm"><?php echo $rs_pd['description']; ?></textarea>
                        </div>
                    </div>
                </form>
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
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replace('editor');
        $( "#start_ship_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $("#save").click(function() {
            $("#product-form").submit();
            return false;
        });
    });
    $.validate();
</script>

