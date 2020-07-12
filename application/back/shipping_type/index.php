<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();

$option = array(
    "table" => "shipping_type"
);

$query = $db->select($option);

$title = 'แก้ไขประเภทการจัดส่ง';
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
            <h1 class="page-header">แก้ไขประเภทการจัดส่ง</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/shipping_type">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="shipping-form" action="<?php echo $baseUrl; ?>/back/shipping_type/form_update" method="post" enctype="multipart/form-data">
                    <?php while ($row = $db->get($query)): ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="shipping_type"><?php echo $row['name']; ?></label>
                            <div class="col-sm-4">
                                <input name="shipping_type[]" type="checkbox" data-toggle="toggle" data-on="WS" data-off="WO" value="<?php echo $row['id']; ?>" <?php echo ($row['is_ws'])?'checked':''; ?> >
                            </div>
                        </div>
                    <?php endwhile; ?>
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