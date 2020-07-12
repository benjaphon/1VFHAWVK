<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();

$option_wr = array(
    "table" => "weight_range"
);

$query_wr = $db->select($option_wr);

$option_bs = array(
    "table" => "box_sizes"
);

$query_bs = $db->select($option_bs);

$title = 'เพิ่มอัตราค่าจัดส่ง';
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
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">เพิ่มอัตราค่าจัดส่ง</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/shipping_rate">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="shipping-form" action="<?php echo $baseUrl; ?>/back/shipping_rate/form_create" method="post">

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="weight_range">ช่วงน้ำหนัก</label>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="weight_range" id="weight_range">
                                <?php while ($row = $db->get($query_wr)){
                                    echo "<option value='{$row['id']}'>{$row['min_wg']} - {$row['max_wg']}</option>";
                                }?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="box_size">ขนาดกล่อง</label>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="box_size" id="box_size">
                                <?php while ($row = $db->get($query_bs)){
                                    echo "<option value='{$row['id']}'>{$row['size_code']}</option>";
                                }?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="parcel">Parcel</label>
                        <div class="col-sm-4">
                            <input type="text" id="parcel" name="parcel" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="register">Register</label>
                        <div class="col-sm-4">
                            <input type="text" id="register" name="register" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="ems">EMS</label>
                        <div class="col-sm-4">
                            <input type="text" id="ems" name="ems" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="flash">Flash</label>
                        <div class="col-sm-4">
                            <input type="text" id="flash" name="flash" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="jt">JT</label>
                        <div class="col-sm-4">
                            <input type="text" id="jt" name="jt" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
  </section>
</section>
  <!--main content end-->

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
<script type="text/javascript">
    $(document).ready(function () {
        $("#save").click(function () {

            $("#shipping-form").submit();
            return false;
        });
    });
    $.validate();
</script>