<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : สินค้า';

$perpage = 10;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = ($page - 1) * $perpage;

$db = new database();

$option_product = array(
    "fields" => "p.*, s.parcel AS cal_parcel, s.register AS cal_register, s.EMS AS cal_EMS",
    "table" => "products AS p LEFT JOIN shipping_rate AS s ON p.weight >= s.min_wg AND p.weight <= s.max_wg",
    "order" => "p.id DESC",
    "limit" => "{$start},{$perpage}",
    "condition" => "p.flag_status = 1"
);


$search = (isset($_GET['search']))?$_GET['search']:'';
$status = (isset($_GET['status']))?$_GET['status']:'';
$in_stock = (isset($_GET['in_stock']))?$_GET['in_stock']:'';

if(isset($search) && !empty($search)){
    $option_product["condition"] .= " AND (";
    $option_product["condition"] .= " p.id LIKE'%{$search}%' OR";
    $option_product["condition"] .= " p.name LIKE'%{$search}%' OR";
    $option_product["condition"] .= " p.price LIKE'%{$search}%' OR";
    $option_product["condition"] .= " p.agent_price LIKE'%{$search}%' OR";
    $option_product["condition"] .= " p.wholesale_price LIKE'%{$search}%' OR";
    $option_product["condition"] .= " p.sale_price LIKE'%{$search}%' OR";
    $option_product["condition"] .= " DATE_FORMAT(p.start_ship_date, '%d/%m/%Y') LIKE'%{$search}%' OR";
    $option_product["condition"] .= " p.quantity LIKE'%{$search}%')";
}

if(isset($status) && !empty($status)){
    $option_product["condition"] .= " AND (p.product_status='{$status}')";
}

if(isset($in_stock) && !empty($in_stock)){
    $option_product["condition"] .= " AND (p.quantity>0)";
}

$query_product = $db->select($option_product);

unset($option_product["limit"]);
$option_product["fields"] = "COUNT(*) as num_row";
$query_pg = $db->select($option_product);
$rs_pg = $db->get($query_pg);
$total_record = $rs_pg["num_row"];
$total_page = ceil($total_record / $perpage);

$page_number = 10;
$page_start = $page - ceil($page_number/2);
$page_end = $page + ceil($page_number/2);

$page_start = ($page_start > 1) ? $page_start : 1;
$page_end = ($page_end < $total_page) ? $page_end : $total_page;

//$uri = $_SERVER['REQUEST_URI']; // url

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
            <h1 class="page-header">จัดการสินค้า</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-6">
            <div class="subhead">
                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                <a role="button" class="btn btn-success new-data"
                   href="<?php echo $baseUrl; ?>/back/product/create">
                    <i class="fa fa-plus"></i>
                    เพิ่มใหม่
                </a>
                <?php } ?>
                <a role="button" class="btn btn-default" 
                   href="<?php echo $baseUrl; ?>/back/product">
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
                    <select id="select_status" name="status" class="form-control">
                        <option value="">สถานะ</option>
                        <option value="P">พรีออเดอร์</option>
                        <option value="S">พร้อมส่ง</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="btn btn-primary">
                        <input type="checkbox" name="in_stock" id="chk_in_stock" value="true" autocomplete="off"> In stock
                    </label>
                </div>
                
                <button id="btn_search" type="submit" class="btn btn-primary">ค้นหา</button>
                <button type="reset" class="btn btn-primary">เคลียร์</button>
            </form>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="user-grid" class="grid-view table-responsive">
                <table id="tbl_Product" class="table table-striped table-custom">
                    <thead>
                        <tr>
                            <th id="user-grid_c0">
                                <a class="sort-link">รูปภาพ</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">สินค้า</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">สถานะ</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">วันที่ส่งได้</a>
                            </th>
                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                <th id="user-grid_c1">
                                    <a class="sort-link">ราคา</a>
                                </th>
                            <?php } ?>
                            <th id="user-grid_c1">
                                <a class="sort-link">ราคาขายส่ง</a>
                            </th>
                            <th id="user-grid_c1">
                                <a class="sort-link">ราคา ตท.</a>
                            </th>
                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                <th id="user-grid_c1">
                                    <a class="sort-link">ราคาขาย</a>
                                </th>
                            <?php } ?>
                            <th id="user-grid_c1">
                                <a class="sort-link">ค่าส่ง</a>
                            </th>
                            <th id="user-grid_c3">
                                <a class="sort-link">คงเหลือ</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        while ($rs_pd = $db->get($query_product)) {

                            $option_img = array(
                                "table" => "images",
                                "condition" => "ref_id='{$rs_pd['id']}' AND filetype='product'",
                                "order" => "id",
                                "limit" => "1"
                            );
                            $query_img = $db->select($option_img);
                            
                            if($db->rows($query_img) > 0){
                                $rs_img = $db->get($query_img);
                                $filename_img = $rs_img['filename'];
                            }
                            else {
                                $filename_img = 'ecimage.jpg';
                            }

                            ?>
                            <tr>
                                <td>         
                                    <a href="<?php echo base_url(); ?>/assets/upload/product/<?php echo $filename_img; ?>" class="fancybox">
                                        <img src="<?php echo base_url(); ?>/assets/upload/product/sm_<?php echo $filename_img; ?>" class="img-responsive" alt="Responsive image">
                                    </a>
                                </td>
                                <td>
                                    <a class="load_data" href="<?php echo $baseUrl; ?>/back/product/view/<?php echo $rs_pd['id']; ?>"><?php echo $rs_pd['name']; ?></a>
                                </td>

                                <td>
                                    <?php 

                                    switch ($rs_pd['product_status']) {
                                        case 'P':
                                            echo 'พรีออเดอร์';
                                            break;
                                        case 'S':
                                            echo 'พร้อมส่ง';
                                            break;
                                        default:
                                            echo 'NONE';
                                            break;
                                    }
                                    
                                    ?>
                                </td>

                                <td>
                                    <?php echo ($rs_pd['start_ship_date']!=null)? date('d/m/Y', strtotime($rs_pd['start_ship_date'])) : ''; ?>
                                </td>
                                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                    <td><?php echo $rs_pd['price']; ?></td>
                                <?php } ?>
                                <td><?php echo $rs_pd['wholesale_price']; ?></td>
                                <td><?php echo $rs_pd['agent_price']; ?></td>
                                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                    <td><?php echo $rs_pd['sale_price']; ?></td>
                                <?php } ?>
                                <td><?php echo round($rs_pd['cal_parcel']); ?>/<?php echo round($rs_pd['cal_register']); ?>/<?php echo round($rs_pd['cal_EMS']); ?>/<?php echo round($rs_pd['kerry']); ?></td>
                                <td><?php echo $rs_pd['quantity']; ?></td>
                            </tr>
                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                            <tr>
                                <td colspan="9">
                                    
                                    <a class="btn btn-info btn-sm" title="" href="<?php echo $baseUrl; ?>/back/product/view/<?php echo $rs_pd['id']; ?>"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>
                                    <a class="btn btn-warning btn-sm" title="" href="<?php echo $baseUrl; ?>/back/product/update/<?php echo $rs_pd['id']; ?>"><i class="glyphicon glyphicon-edit"></i> แก้ไข</a>
                                    <a class="btn btn-primary btn-sm" title="" href="#" data-toggle="modal" data-target="#duplicateModal<?php echo $rs_pd['id'];?>"><i class="glyphicon glyphicon-duplicate"></i> สำเนา</a>
                                    <a class="btn btn-danger btn-sm confirm" title="" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_pd['id'];?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
                                    <a class="btn btn-success btn-sm" href="<?php echo $baseUrl; ?>/back/product/form_change_product_status/<?php echo $rs_pd['id']; ?>?page=<?php echo $page; ?>"><i class="glyphicon glyphicon-refresh"></i> Change Status</a>

                                    <!-- Modal Duplicate -->
                                    <div class="modal fade" id="duplicateModal<?php echo $rs_pd['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background:#337ab7;">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">แจ้งเตือนการทำสำเนาข้อมูล</h4>
                                                </div>
                                                <div class="modal-body">
                                                    คุณยืนยันต้องการจะทำสำเนาสินค้านี้ ใช่หรือไม่?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                                                    <a role="button" class="btn btn-primary" href="<?php echo $baseUrl; ?>/back/product/form_duplicate/<?php echo $rs_pd['id']; ?>">ใช่ ยืนยันการทำสำเนา</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="deleteModal<?php echo $rs_pd['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <a role="button" class="btn btn-primary" href="<?php echo $baseUrl; ?>/back/product/delete/<?php echo $rs_pd['id']; ?>">ใช่ ยืนยันการลบ</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination pull-lg-right">
                        <li>
                            <a href="?page=1&search=<?php echo $search ?>&status=<?php echo $status ?>&in_stock=<?php echo $in_stock ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                            <?php for($i=$page_start; ($i<$page_start+$page_number) && ($i<=$total_page); $i++){ ?>
                                <li><a href="?page=<?php echo $i; ?>&search=<?php echo $search ?>&status=<?php echo $status ?>&in_stock=<?php echo $in_stock ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                        <li>
                            <a href="?page=<?php echo $total_page;?>&search=<?php echo $search ?>&status=<?php echo $status ?>&in_stock=<?php echo $in_stock ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
  </section><! --/wrapper -->
</section><!-- /MAIN CONTENT -->

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
        /*$("#tbl_Product").DataTable({
            aaSorting: [],
            responsive: true,
            "language": {
                "url": "<?php echo $baseUrl; ?>/assets/DataTables/lang/Thai.json"
            }
        });*/
        $('a.fancybox').fancybox();

        $("#chk_in_stock").prop('checked', '<?php echo $in_stock; ?>');
        $("#select_status").val("<?php echo $status; ?>");
        $("#search").val("<?php echo $search; ?>");
        $("button[type=reset]").click(function(){
            $(this).parent("form")[0].reset();
            $("#btn_search").click();
        });
    });
</script>