<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : ผู้ใช้';

$perpage = 10;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = ($page - 1) * $perpage;

$db = new database();

$option_user = array(
    "table" => "users",
    "order" => "id DESC",
    "limit" => "{$start},{$perpage}",
    "condition" => "1=1"
);

$search = (isset($_GET['search']))?$_GET['search']:'';
$role = (isset($_GET['role']))?$_GET['role']:'';

if(isset($search) && !empty($search)){
    $option_user["condition"] .= " AND (";
    $option_user["condition"] .= " id LIKE'%{$search}%' OR";
    $option_user["condition"] .= " username LIKE'%{$search}%' OR";
    $option_user["condition"] .= " DATE_FORMAT(created_at, '%d/%m/%Y') LIKE'%{$search}%')";
}

if(isset($role) && !empty($role)){
    $option_user["condition"] .= " AND (role='{$role}')";
}

$query_user = $db->select($option_user);

unset($option_user["limit"]);
$option_user["fields"] = "COUNT(*) as num_row";
$query_pg = $db->select($option_user);
$rs_pg = $db->get($query_pg);
$total_record = $rs_pg["num_row"];
$total_page = ceil($total_record / $perpage);

$page_number = 10;
$page_start = $page - ceil($page_number/2);
$page_end = $page + ceil($page_number/2);

$page_start = ($page_start > 1) ? $page_start : 1;
$page_end = ($page_end < $total_page) ? $page_end : $total_page;

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
    @media (min-width: 1200px) {
        .pull-lg-right {
            float: right;
        }
    }
</style>
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
        <div class="col-lg-6">
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
        <div class="col-lg-6">
            <form class="form-inline pull-lg-right" method="get">
                <div class="form-group">
                    <input id="search" name="search" type="text" class="form-control form-control-lg" placeholder="Search Here">
                </div>
                <div class="form-group">
                    <select id="select_role" name="role" class="form-control">
                        <option value="">สิทธิ์</option>
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
                <button id="btn_search" type="submit" class="btn btn-primary">ค้นหา</button>
                <button type="reset" class="btn btn-primary">เคลียร์</button>
            </form>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="user-grid" class="grid-view table-responsive">
                <table class="table table-striped table-custom" id="tbl_User">
                    <thead>
                        <tr>
                            <th id="user-grid_c0">
                                <a class="sort-link">รหัสผู้ใช้</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">ชื่อผู้ใช้</a>
                            </th><th id="user-grid_c4">
                                <a class="sort-link">สิทธิ์</a>
                            </th><th id="user-grid_c5">
                                <a class="sort-link">สร้างเมื่อ</a>
                            </th>
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
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <a class="btn btn-info btn-sm" title="" href="<?php echo $baseUrl; ?>/back/user/profile/<?php echo $rs_user['id']; ?>"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>
                                    <a class="btn btn-warning btn-sm" title="" href="<?php echo $baseUrl; ?>/back/user/update/<?php echo $rs_user['id']; ?>"><i class="glyphicon glyphicon-edit"></i> แก้ไข</a>
                                    <a class="btn btn-danger btn-sm confirm" title="" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_user['id'];?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
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
                <nav>
                    <ul class="pagination pull-lg-right">
                        <li>
                            <a href="?page=1&search=<?php echo $search ?>&role=<?php echo $role ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                            <?php for($i=$page_start; ($i<$page_start+$page_number) && ($i<=$total_page); $i++){ ?>
                                <li><a href="?page=<?php echo $i; ?>&search=<?php echo $search ?>&role=<?php echo $role ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                        <li>
                            <a href="?page=<?php echo $total_page;?>&search=<?php echo $search ?>&role=<?php echo $role ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
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
            /*$("#tbl_User").DataTable( {
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": "<?php echo $baseUrl; ?>/assets/DataTables/lang/Thai.json"
                }
            } );*/

            $("#select_role").val("<?php echo $role; ?>");
            $("#search").val("<?php echo $search; ?>");
            $("button[type=reset]").click(function(){
                $(this).parent("form")[0].reset();
                $("#btn_search").click();
            });
        });
    </script>