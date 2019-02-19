<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();

$title = 'เพิ่มผู้ใช้ใหม่';
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
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/user">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="user-form" action="<?php echo $baseUrl; ?>/back/user/form_create" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="username">Username</label>
                        <div class="col-sm-4">
                            <input class="form-control input-sm" maxlength="50" name="username" id="username" type="text" data-validation="required" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="password">Password</label>
                        <div class="col-sm-4">
                            <input class="form-control input-sm" maxlength="50" name="password" id="password" type="password" data-validation="required" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="password_confirm">Password Confirm</label>
                        <div class="col-sm-4">
                            <input class="form-control input-sm" maxlength="50" name="password_confirm" id="password_confirm" type="password" data-validation="required" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="address">ที่อยู่</label>
                        <div class="col-sm-4">
                            <textarea class="form-control input-sm" maxlength="200" rows="3" name="address" id="address" type="text" value="<?php echo $rs_user['address'];?>"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="role">ประเภทสมาชิก</label>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="role" id="role">
                                <option value="user">ผู้ใช้ทั่วไป</option>
                                <option value="admin">ผู้ดูแลระบบ</option>
                            </select>
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

            //validate
            if( $("#password").val() != $("#password_confirm").val() ){
                alert("password ไม่ตรงกัน");
                return false;
            }

            $("#user-form").submit();
            return false;
        });
    });
    $.validate();
</script>