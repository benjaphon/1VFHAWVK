<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$option_pc = array(
    "table" => "products"
);
$query_pc = $db->select($option_pc);


$title = 'เพิ่มสินค้านำเข้า';
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

<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">เพิ่มข้อมูลใหม่</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/import">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="import-form" action="<?php echo $baseUrl; ?>/back/import/form_create" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="Product_product_id" class="col-sm-2 control-label required">สินค้า <span class="required">*</span></label>
                        <div class="col-sm-4">
                            <select id="product_id" name="product_id" class="selectpicker form-control input-sm" data-live-search="true">
                                <?php while ($rs_pc = $db->get($query_pc)) { ?>
                                    <option value="<?php echo $rs_pc['id']; ?>"><?php echo $rs_pc['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="qty" class="col-sm-2 control-label">จำนวน <span class="required">*</span></label>
                         <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" name="qty" autocomplete="off" name="qty" placeholder="ใส่จำนวน" data-validation="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Product_name" class="col-sm-2 control-label required">ค่าใช้จ่าย <span class="required">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" id="price" name="price" class="form-control input-sm" data-validation="number" data-validation-allowing="float" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note" class="col-sm-2 control-label">หมายเหตุ</label>
                        <div class="col-sm-4">
                            <input type="text" id="note" name="note" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="refund" class="col-sm-2 control-label">คืนเงิน</label>
                        <div class="col-sm-4">
                            <input type="checkbox" id="refund" name="refund" value="1">
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

<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<!-- selectbox -->
<script src="<?php echo $baseUrl; ?>/assets/js/bootstrap-select.js" type="text/javascript"></script>
  <script type="text/javascript">
    $(document).ready(function () {
        $("#save").click(function () {
            $("#import-form").submit();
            return false;
        });
        $("input[name=qty]").select();
    });
    $.validate();
    </script>