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


$title = 'แก้ไขผู้ใช้งาน : ' . $rs_user['username'];
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
            <h1 class="page-header">แก้ไขข้อมูล <?php echo $rs_user['username']; ?></h1>
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
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="user-form" action="<?php echo $baseUrl; ?>/back/user/form_update/<?php echo $rs_user['id']; ?>" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="username">Username</label>
                        <div class="col-sm-4">
                            <input class="form-control input-sm" maxlength="50" name="username" id="username" type="text" value="<?php echo $rs_user['username'];?>" data-validation="required" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="old_password">Old Password</label>
                        <div class="col-sm-4">
                            <input class="form-control input-sm" maxlength="50" name="old_password" id="old_password" type="password" data-validation="required" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="new_password">New Password</label>
                        <div class="col-sm-4">
                            <input class="form-control input-sm" maxlength="50" name="new_password" id="new_password" type="password" data-validation="required" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="new_password_confirm">New Password Confirm</label>
                        <div class="col-sm-4">
                            <input class="form-control input-sm" maxlength="50" name="new_password_confirm" id="new_password_confirm" type="password" data-validation="required" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="address">ที่อยู่</label>
                        <div class="col-sm-4">
                            <textarea class="form-control input-sm" maxlength="200" rows="3" name="address" id="address" type="text"><?php echo $rs_user['address'];?></textarea>
                        </div>
                    </div>
                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="role">ประเภทสมาชิก</label>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="role" id="role">
                                <option value="user">ผู้ใช้ทั่วไป</option>
                                <option value="agent_vip">ตัวแทน vip</option>
                                <option value="admin">ผู้ดูแลระบบ</option>
                            </select>
                        </div>
                    </div>
                    <?php } else { ?>
                        <input type="hidden" name="role" value="<?php echo $rs_user['role']; ?>">
                    <?php } ?>
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

<script type="text/javascript" src="<?php echo $baseUrl; ?>/js/jquery.form-validator.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#save").click(function() {

            //validate
            $.post("<?php echo $baseUrl; ?>/back/user/check_old_password", { id: <?php echo $rs_user['id'];?>, old_password: $("#old_password").val() }, function(data){
                if(data=='true'){
                    if( $("#new_password").val() != $("#new_password_confirm").val() ){
                        alert("password ไม่ตรงกัน");
                        return false;
                    }

                    $("#user-form").submit();
                    return false;
                } else {
                    alert("password ไม่ถูกต้อง");
                    return false;
                }
            })

            
        });

        $("#role").val("<?php echo $rs_user['role']; ?>");
    });
    $.validate();
</script>