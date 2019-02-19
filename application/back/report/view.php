<?php
/*
 * php code///////////**********************************************************
 */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location:" . $baseUrl . "/back/order");
}

$db = new database();
$option_rq = array(
    "table" => "request",
    "condition" => "order_id = {$_GET['id']}"
);

$query_rq = $db->select($option_rq);
$rows_rq = $db->rows($query_rq);
$rs_rq = $db->get($query_rq);

$title = 'แจ้งปัญหา';
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

</style>


<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->
<!--main content start-->

<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">แจ้งปัญหา</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data saveform" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-6">
        <?php if($rows_rq == 0){ ?>
            <form action="<?php echo base_url(); ?>/back/report/create" id="report-create-form" method="post">
                <input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>">
                <div class="form-group col-xs-12 clearfix">
                    <label for="problem" class="text-bold required">ปัญหาที่พบ</label>
                    <textarea class="form-control" rows="5" name="problem" id="problem" data-validation="required"></textarea>
                </div>
            </form>
        <?php } else { 

            switch ($rs_rq['status']) {
                case 'A':
                    $status_name = 'แจ้งปัญหาแล้ว';
                    break;
                case 'P':
                    $status_name = 'ดำเนินการ';
                    break;
                case 'F':
                    $status_name = 'เสร็จสิ้น';
                    break;
                case 'C':
                    $status_name = 'ยกเลิก';
                    break;
                default:
                    $status_name = 'ขัดข้อง';
                    break;
            }

            ?>
            <form action="<?php echo base_url(); ?>/back/report/update" id="report-update-form" method="post">
                <input type="hidden" name="order_id" value="<?php echo $rs_rq['order_id']; ?>">
                <?php switch ($rs_rq['status']) {
                        case "A":
                            if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                            <div class="form-group col-xs-12 clearfix">
                                <label for="status_name" class="text-bold">สถานะ</label>
                                <label name="status_name" class="control-label"><?php echo $status_name; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="problem" class="text-bold">ปัญหาที่พบ</label><br>
                                <label name="problem" class="control-label"><?php echo $rs_rq['problem']; ?></label>
                            </div>
                            <div class="form-group col-xs-12 clearfix">
                                <label for="result" class="text-bold required">การแก้ไข</label>
                                <textarea class="form-control" rows="5" name="result" id="result" data-validation="required"><?php echo $rs_rq['result']; ?></textarea>
                            </div>
                            <div class="form-group col-xs-12 clearfix">
                                <label for="status" class="text-bold required">สถานะ</label>
                                <select class="form-control input-sm" name="status" id="status">
                                    <!--<option value="A">Active</option>-->
                                    <option value="P" selected>Process</option>
                                    <option value="F">Final</option>
                                    <option value="C">Cancel</option>
                                </select>
                            </div>

                            <?php } else { ?>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="status_name" class="text-bold">สถานะ</label>
                                <label name="status_name" class="control-label"><?php echo $status_name; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="problem" class="text-bold required">ปัญหาที่พบ</label>
                                <textarea class="form-control" rows="5" name="problem" id="problem" data-validation="required"><?php echo $rs_rq['problem']; ?></textarea>
                            </div>

                            <?php }
                            break;
                        case "P": ?>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="status_name" class="text-bold">สถานะ</label>
                                <label name="status_name" class="control-label"><?php echo $status_name; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="problem" class="text-bold">ปัญหาที่พบ</label><br>
                                <label name="problem" class="control-label"><?php echo $rs_rq['problem']; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="result" class="text-bold">การแก้ไข</label><br>
                                <label name="result" class="control-label"><?php echo $rs_rq['result']; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="status" class="text-bold required">สถานะ</label>
                                <select class="form-control input-sm" name="status" id="status">
                                    <option value="A">Active</option>
                                    <!--<option value="P">Process</option>-->
                                    <option  value="F" selected>Final</option>
                                    <option value="C">Cancel</option>
                                </select>
                            </div>

                            <?php
                            break;
                        case "F": ?>
                            
                            <div class="form-group col-xs-12 clearfix">
                                <label for="status_name" class="text-bold">สถานะ</label>
                                <label name="status_name" class="control-label"><?php echo $status_name; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="problem" class="text-bold">ปัญหาที่พบ</label><br>
                                <label name="problem" class="control-label"><?php echo $rs_rq['problem']; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="result" class="text-bold">การแก้ไข</label><br>
                                <label name="result" class="control-label"><?php echo $rs_rq['result']; ?></label>
                            </div>

                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                            <div class="form-group col-xs-12 clearfix">
                                <label for="status" class="text-bold required">สถานะ</label>
                                <select class="form-control input-sm" name="status" id="status">
                                    <option value="A">Active</option>
                                    <option value="P">Process</option>
                                    <option value="F" selected>Final</option>
                                    <option value="C">Cancel</option>
                                </select>
                            </div>

                            <?php }
                            break;
                        case "C": ?>
                            
                            <div class="form-group col-xs-12 clearfix">
                                <label for="status_name" class="text-bold">สถานะ</label>
                                <label name="status_name" class="control-label"><?php echo $status_name; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="problem" class="text-bold">ปัญหาที่พบ</label><br>
                                <label name="problem" class="control-label"><?php echo $rs_rq['problem']; ?></label>
                            </div>

                            <div class="form-group col-xs-12 clearfix">
                                <label for="result" class="text-bold">การแก้ไข</label><br>
                                <label name="result" class="control-label"><?php echo $rs_rq['result']; ?></label>
                            </div>

                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                            <div class="form-group col-xs-12 clearfix">
                                <label for="status" class="text-bold required">สถานะ</label>
                                <select class="form-control input-sm" name="status" id="status">
                                    <option value="A">Active</option>
                                    <option value="P" selected>Process</option>
                                    <option value="F">Final</option>
                                    <!--<option value="C">Cancel</option>-->
                                </select>
                            </div>
                            <?php }
                            break;
                        default:
                            echo "การดำเนินการขัดข้อง";
                    } ?>

            </form>
        <?php } ?>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data saveform" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
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
    //$("#tbl_Order_Detail").DataTable();

    /* Element Event */

    $.ajaxSetup({
        type: 'POST',
        cache: false,
        headers: { "cache-control": "no-cache" }
    });

    /*****************/

    /* Save Order */
    $('.saveform').click(function () {
        <?php if($rows_rq == 0){ ?>
            $('#report-create-form').submit();
        <?php } else { ?>
            $('#report-update-form').submit();
        <?php } ?>
        return false;
    });
    /*************/


});
</script>
