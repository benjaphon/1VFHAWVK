<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$option_user = array(
    "table" => "users",
    "condition" => "id='{$_GET['id']}'"
);
$query_user = $db->select($option_user);
$rs_user = $db->get($query_user);


$title = 'รายละเอียดผู้ใช้งาน : ' . $rs_user['username'];
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
            <h1 class="page-header">ข้อมูลผู้ใช้ : <?php echo $rs_user['username']; ?></h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <!--<a role="button" id="save" class="btn btn-success new-data" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    Save
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/user">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    Cancel
                </a>-->
                <a role="button" class="search-button btn btn-danger" href="<?php echo $baseUrl; ?>/back/user">
                    <i class="glyphicon glyphicon-circle-arrow-left"></i>
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <input type="hidden" name="role" value="admin">
                <div class="form-group">
                    <label class="col-sm-2 control-label text-bold" for="username">Username</label>
                    <div class="col-sm-4">
                        <label class="control-label"><?php echo $rs_user['username'];?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-bold" for="role">สิทธิ์</label>
                    <div class="col-sm-4">
                        <label class="control-label"><?php echo $rs_user['role'];?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-bold" for="address">ที่อยู่</label>
                    <div class="col-sm-4">
                        <label class="control-label"><?php echo $rs_user['address'];?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-bold" for="created_at">สร้างเมื่อ</label>
                    <div class="col-sm-4">
                        <label class="control-label"><?php echo $rs_user['created_at'];?></label>
                    </div>
                </div>
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