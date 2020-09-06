<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();

$option = array(
    "table" => "settings"
);

$query = $db->select($option);

$title = 'การตั้งค่าทั่วไป';
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
            <h1 class="page-header">การตั้งค่าทั่วไป</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/settings">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="setting-form" action="<?php echo $baseUrl; ?>/back/settings/form_update" method="post" enctype="multipart/form-data">
                    <?php while ($row = $db->get($query)): ?>

                        <div class="form-group">
                            <input type="hidden" name="hidden_meta[]" value="<?php echo $row['id']; ?>">
                            <label class="col-sm-2 control-label" for="<?php echo $row['meta']; ?>"><b><?php echo ucfirst($row['meta']); ?> :</b></label>
                            <div class="col-sm-4">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" data-toggle="toggle" name="setting_<?php echo $row['id']; ?>" id="<?php echo $row['meta']; ?>" value="true" <?php echo ($row['value']=="true") ? "checked" : "" ?> >
                                </div>
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

            $("#setting-form").submit();
            return false;
        });
    });
    $.validate();
</script>