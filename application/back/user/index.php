<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : ผู้ใช้';
$db = new database();

$option_user = array(
    "table" => "users"

);
$query_user = $db->select($option_user);

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
            <h1 class="page-header">จัดการผู้ใช้</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data"
                   href="<?php echo $baseUrl; ?>/back/user/create">
                    <i class="glyphicon glyphicon-plus-sign"></i>
                    เพิ่มใหม่
                </a>
                <a role="button" class="btn btn-default" 
                   href="<?php echo $baseUrl; ?>/back/user">
                    <i class="glyphicon glyphicon-refresh"></i>
                    โหลดหน้าจอใหม่
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="user-grid" class="grid-view">
                <table class="table table-striped table-custom" id="tbl_User">
                    <thead>
                        <tr>
                            <th id="user-grid_c0">
                                <a class="sort-link">#</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">Username</a>
                            </th><th id="user-grid_c4">
                                <a class="sort-link">สิทธิ์</a>
                            </th><th id="user-grid_c5">
                                <a class="sort-link">สร้างเมื่อ</a>
                            </th>
                            <th class="button-column" id="user-grid_c6">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rs_user = $db->get($query_user)) { ?>
                            <tr>
                                <td><?php echo $rs_user['id']; ?></td>
                                <td>
                                    <a class="load_data" href="<?php echo $baseUrl; ?>/back/user/profile/<?php echo $rs_user['id']; ?>"><?php echo $rs_user['username']; ?></a>
                                </td>
                                <td><?php echo $rs_user['role']; ?></td>
                                <td><?php echo thaidate($rs_user['created_at']); ?></td>
                                <td class="button-column">
                                    <a class="btn btn-info btn-xs load_data" title="" href="<?php echo $baseUrl; ?>/back/user/profile/<?php echo $rs_user['id']; ?>"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>
                                    <a class="btn btn-warning btn-xs load_data" title="" href="<?php echo $baseUrl; ?>/back/user/update/<?php echo $rs_user['id']; ?>"><i class="glyphicon glyphicon-edit"></i> แก้ไข</a>
                                    <a class="btn btn-danger btn-xs confirm" title="" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_user['id'];?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $rs_user['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">แจ้งเตือนการลบข้อมูล</h4>
                                                </div>
                                                <div class="modal-body">
                                                    คุณยืนยันต้องการจะลบข้อมูลนี้ ใช่หรือไม่?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                                                    <a role="button" class="btn btn-primary" href="<?php echo $baseUrl; ?>/back/user/delete/<?php echo $rs_user['id']; ?>">ใช่ ยืนยันการลบ</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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

    <script type="text/javascript">
        $(document).ready(function () {
            $("#tbl_User").DataTable( {
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": "<?php echo $baseUrl; ?>/assets/DataTables/lang/Thai.json"
                }
            } );
        });
    </script>