<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : อัตราค่าจัดส่ง';

$db = new database();

$sql = "SELECT COUNT(id) AS wrCount FROM weight_range";
$query = $db->query($sql);
$rs = $db->get($query);
$perpage = $rs['wrCount'];

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = ($page - 1) * $perpage;



$option = array(
    "fields" => "s.*, wr.min_wg, wr.max_wg, bs.size_code",
    "table" => "shipping_rate AS s 
                LEFT JOIN weight_range AS wr ON s.weight_id = wr.id
                LEFT JOIN box_sizes AS bs ON s.boxsize_id = bs.id",
    "order" => "bs.size_index, s.weight_id ASC",
    "limit" => "{$start},{$perpage}",
    "condition" => "1=1"
);

$search = (isset($_GET['search']))?$_GET['search']:'';
$weight = (isset($_GET['weight']))?$_GET['weight']:'';
$size = (isset($_GET['size']))?$_GET['size']:'';

if(isset($search) && !empty($search)){
    $option["condition"] .= " AND (";
    $option["condition"] .= " s.id LIKE'%{$search}%' OR";
    $option["condition"] .= " bs.size_code LIKE'%{$search}%' OR";
    $option["condition"] .= " bs.size_name LIKE'%{$search}%' OR";
    $option["condition"] .= " wr.min_wg LIKE'%{$search}%' OR";
    $option["condition"] .= " wr.max_wg LIKE'%{$search}%' OR";
    $option["condition"] .= " s.parcel LIKE'%{$search}%' OR";
    $option["condition"] .= " s.register LIKE'%{$search}%' OR";
    $option["condition"] .= " s.ems LIKE'%{$search}%' OR";
    $option["condition"] .= " s.flash LIKE'%{$search}%' OR";
    $option["condition"] .= " s.jt LIKE'%{$search}%')";
}

if(isset($weight) && !empty($weight)){
    $option["condition"] .= " AND (s.weight_id='{$weight}')";
}

if(isset($size) && !empty($size)){
    $option["condition"] .= " AND (s.boxsize_id='{$size}')";
}

$query = $db->select($option);

unset($option["limit"]);
$option["fields"] = "COUNT(*) as num_row";
$query_pg = $db->select($option);
$rs_pg = $db->get($query_pg);
$total_record = $rs_pg["num_row"];
$total_page = ceil($total_record / $perpage);

$page_number = 10;
$page_start = $page - ceil($page_number/2);
$page_end = $page + ceil($page_number/2);

$page_start = ($page_start > 1) ? $page_start : 1;
$page_end = ($page_end < $total_page) ? $page_end : $total_page;

$option_weight = array(
    "table" => "weight_range",
);
$query_weight = $db->select($option_weight);

$option_size = array(
    "table" => "box_sizes",
);
$query_size = $db->select($option_size);

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
            <h1 class="page-header">ค่าจัดส่ง</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-6">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data"
                   href="<?php echo $baseUrl; ?>/back/shipping_rate/create">
                    <i class="glyphicon glyphicon-plus-sign"></i>
                    เพิ่มใหม่
                </a>
                <a role="button" class="btn btn-default" 
                   href="<?php echo $baseUrl; ?>/back/shipping_rate">
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
                    <select id="select_weight" name="weight" class="form-control">
                        <option value="">น้ำหนัก</option>
                        <?php while ($rs_weight = $db->get($query_weight)): ?>
                            <option value="<?php echo $rs_weight['id']; ?>"><?php echo $rs_weight['min_wg'].' - '.$rs_weight['max_wg']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select id="select_size" name="size" class="form-control">
                        <option value="">ขนาด</option>
                        <?php while ($rs_size = $db->get($query_size)): ?>
                            <option value="<?php echo $rs_size['id']; ?>"><?php echo $rs_size['size_code']; ?></option>
                        <?php endwhile; ?>
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
                                <a class="sort-link">น้ำหนักต่ำสุด</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">น้ำหนักสูงสุด</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">ขนาดกล่อง</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">Parcel</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">Register</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">EMS</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">Flash</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">JT</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rs = $db->get($query)) { ?>
                            <tr>
                                <td><?php echo $rs['min_wg']; ?></td>
                                <td><?php echo $rs['max_wg']; ?></td>
                                <td><?php echo $rs['size_code']; ?></td>
                                <td><?php echo $rs['parcel']; ?></td>
                                <td><?php echo $rs['register']; ?></td>
                                <td><?php echo $rs['ems']; ?></td>
                                <td><?php echo $rs['flash']; ?></td>
                                <td><?php echo $rs['jt']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <a class="btn btn-warning btn-sm" title="" href="<?php echo $baseUrl; ?>/back/shipping_rate/update/<?php echo $rs['id']; ?>"><i class="glyphicon glyphicon-edit"></i> แก้ไข</a>
                                    <a class="btn btn-danger btn-sm confirm" title="" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs['id'];?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $rs['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <form action="<?php echo $baseUrl; ?>/back/shipping_rate/delete" method="post">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                                                        <input type="hidden" name="shipping_id" value="<?php echo $rs['id']; ?>">
                                                        <button type="submit" class="btn btn-primary">ใช่ ยืนยันการลบ</button>
                                                    </form>
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
                            <a href="?page=1&search=<?php echo $search ?>&weight=<?php echo $weight; ?>&size=<?php echo $size; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                            <?php for($i=$page_start; ($i<$page_start+$page_number) && ($i<=$total_page); $i++){ ?>
                                <li><a href="?page=<?php echo $i; ?>&search=<?php echo $search ?>&weight=<?php echo $weight; ?>&size=<?php echo $size; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                        <li>
                            <a href="?page=<?php echo $total_page;?>&search=<?php echo $search ?>&weight=<?php echo $weight; ?>&size=<?php echo $size; ?>" aria-label="Next">
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

            $("#search").val("<?php echo $search; ?>");
            $("#select_weight").val("<?php echo $weight; ?>");
            $("#select_size").val("<?php echo $size; ?>");
            $("button[type=reset]").click(function(){
                $(this).parent("form")[0].reset();
                $("#btn_search").click();
            });
        });
    </script>