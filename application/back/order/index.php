<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : รายการสั่งซื้อสินค้า';

$perpage = 25;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = ($page - 1) * $perpage;

$db = new database();

//For checking if there is no preorder in the order (in other word every products in the order in stock)
$product_preorder_sum = "SUM(EXISTS(SELECT products.id FROM products WHERE products.id=d.product_id AND products.product_status='P')) AS product_preorder_sum";

$option_or = array(
    "fields" => "o.id, o.order_datetime, o.receiver, o.tracking_no, o.ship_date, o.order_status, {$product_preorder_sum}",
    "table" => "orders AS o LEFT JOIN order_details AS d ON o.id = d.order_id INNER JOIN products AS p ON d.product_id = p.id",
    "order" => "o.id DESC",
    "limit" => "{$start},{$perpage}"
);

if($_SESSION[_ss . 'levelaccess'] == 'admin') {
    $option_or["condition"] = "1=1";
}
else
{
    $option_or["condition"] = "user_id={$_SESSION[_ss . 'id']}";
}

$search = (isset($_GET['search']))?$_GET['search']:'';
$status = (isset($_GET['status']))?$_GET['status']:'';

if(isset($search) && !empty($search)){
    $option_or["condition"] .= " AND (";
    $option_or["condition"] .= " o.id LIKE'%{$search}%' OR";
    $option_or["condition"] .= " DATE_FORMAT(o.order_datetime, '%d/%m/%Y') LIKE'%{$search}%' OR";
    $option_or["condition"] .= " o.receiver LIKE'%{$search}%' OR";
    $option_or["condition"] .= " o.tracking_no LIKE'%{$search}%' OR";
    $option_or["condition"] .= " DATE_FORMAT(o.ship_date, '%d/%m/%Y') LIKE'%{$search}%' OR";
    $option_or["condition"] .= " p.name LIKE'%{$search}%')";
}

if(isset($status) && !empty($status)){
    $option_or["condition"] .= " AND (o.order_status='{$status}')";
}

$option_or["condition"] .= " GROUP BY o.id, o.order_datetime, o.receiver, o.tracking_no, o.ship_date, o.order_status";

$query_or = $db->select($option_or);

unset($option_or["limit"]);
$query_rows = $db->select($option_or);
$total_record = $db->rows($query_rows);
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
    #tbl_Order ul{
        padding: 0;
    }

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
            <h1 class="page-header">รายการสั่งซื้อสินค้า</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-6">
            <div class="form-group">
                <a role="button" class="btn btn-success new-data"
                   href="<?php echo $baseUrl; ?>/back/order/create">
                    <i class="fa fa-plus"></i>
                    เพิ่มใหม่
                </a>
                <a role="button" class="btn btn-default" 
                   href="<?php echo $baseUrl; ?>/back/order">
                    <i class="glyphicon glyphicon-refresh"></i>
                    โหลดหน้าจอใหม่
                </a>
                <a role="button" class="btn btn-primary"
                   href="<?php echo $baseUrl; ?>/back/payment/create">
                    <i class="fa fa-credit-card"></i>
                    แจ้งชำระเงินหลายรายการ
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <form class="form-inline pull-lg-right" method="get">
                <div class="form-group">
                    <input id="search" name="search" type="text" class="form-control form-control-lg" placeholder="Search Here">
                </div>
                <div class="form-group">
                    <!--R จองสินค้า
                        P ชำระเงินแล้ว
                        F รับออเดอร์
                        S ส่งแล้ว-->
                    <select id="select_status" name="status" class="form-control">
                        <option value="">สถานะ</option>
                        <option value="R">จองสินค้า</option>
                        <option value="P">ชำระเงินแล้ว</option>
                        <option value="F">รับออเดอร์</option>
                        <option value="S">ส่งแล้ว</option>
                    </select>
                </div>
                <button id="btn_search" type="submit" class="btn btn-primary">ค้นหา</button>
                <button type="reset" class="btn btn-primary">เคลียร์</button>
            </form>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="user-grid" class="grid-view">
                <table class="table table-striped table-custom" id="tbl_Order">
                    <thead>
                        <tr>
                            <th id="user-grid_c1">
                                <a class="sort-link">รหัสสั่งซื้อ</a>
                            </th>
                            <th id="user-grid_c4">
                                <a class="sort-link">วันที่สั่งซื้อ</a>
                            </th>
                            <th id="user-grid_c4">
                                <a class="sort-link">รายการสินค้า</a>
                            </th>
                            <th id="user-grid_c4">
                                <a class="sort-link">ที่อยู่ผู้รับ</a>
                            </th>
                            <th id="user-grid_c1">
                                <a class="sort-link">เลขที่พัสดุ</a>
                            </th>
                            <th id="user-grid_c1">
                                <a class="sort-link">วันที่จัดส่ง</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">สถานะ</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        while ($rs_or = $db->get($query_or)) {
                            //สถานะ
                            /*
                            R จองสินค้า
                            P ชำระเงินแล้ว
                            F รับออเดอร์
                            S ส่งแล้ว
							D โอนเงินคืน
                            */
                            switch ($rs_or['order_status']) {
                                case 'R':
                                    $order_status = 'จองสินค้า';
                                    $btnorder = 'แจ้งชำระเงิน';
                                    $btnstat = '';
                                    $btnstat_del = '';
                                    break;
                                case 'P':
                                    $order_status = 'ชำระเงินแล้ว';
                                    $btnorder = 'ชำระเงินแล้ว';
                                    $btnstat = 'disabled hidden';
                                    $btnstat_del = 'disabled hidden';
                                    break;
                                case 'F':
                                    $order_status = 'รับออเดอร์';
                                    $btnorder = 'ชำระเงินแล้ว';
                                    $btnstat = 'disabled hidden';
                                    $btnstat_del = 'disabled hidden';
                                    break;
                                case 'S':
                                    $order_status = 'ส่งแล้ว';
                                    $btnorder = 'ชำระเงินแล้ว';
                                    $btnstat = 'disabled hidden';
                                    $btnstat_del = 'disabled hidden';
                                    break;
                                case 'X':
                                    $order_status = 'เกินกว่ากำหนด';
                                    $btnorder = 'เกินกว่ากำหนด';
                                    $btnstat = 'disabled hidden';
                                    $btnstat_del = 'disabled hidden';
                                    break;
								case 'D':
                                    $order_status = 'โอนเงินคืน';
                                    $btnorder = 'โอนเงินคืน';
                                    $btnstat = 'disabled hidden';
                                    $btnstat_del = 'disabled hidden';
                                    break;
                                default:
                                    $order_status = 'ผิดพลาด';
                                    $btnorder = '-';
                                    $btnstat = 'disabled hidden';
                                    $btnstat_del = 'disabled hidden';
                                    break;
                            }

                            ?>
                            <tr <?php if ($rs_or['order_status']=='R' && $rs_or['product_preorder_sum']==0) echo "class='order-in-stock'"; ?> >
                                <td>
                                    <a href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><?php echo $rs_or['id']; ?></a>
                                </td>
                                <td><?php echo thaidate($rs_or['order_datetime']); ?></td>
                                <td>
                                    <ul>
                                    <?php
                                        $sql_od = "SELECT d.*,p.id,p.name,p.start_ship_date,p.product_status FROM order_details d INNER JOIN products p ";
                                        $sql_od .= "ON d.product_id=p.id ";
                                        $sql_od .="WHERE d.order_id='{$rs_or['id']}' ";
                                        $query_od = $db->query($sql_od);

                                        while ($rs_od = $db->get($query_od)) { ?>

                                        <li>- <?php 

                                            if ($rs_od['start_ship_date']==null) {
                                                $product_name = $rs_od['name'];
                                            }else{
                                                $product_name = $rs_od['name']." (".date('d-m-Y', strtotime($rs_od['start_ship_date'])).")";
                                            }

                                            if ($rs_od['product_status']=='S') {
                                                $product_name = "<span class='product-in-stock'>".$product_name."</span>";
                                            }

                                            echo $product_name;

                                        ?></li>

                                    <?php } ?>
                                    </ul>
                                </td>
                                <td width="200px">
                                    <p data-toggle="tooltip" title="<?php echo $rs_or['receiver']; ?>">
                                    <?php if (strlen($rs_or['receiver']) <= 100) {
                                                echo $rs_or['receiver'];
                                            } else {
                                                echo substr($rs_or['receiver'], 0, 100).'...';
                                            }    
                                    ?>
                                    </p>
                                </td>
                                <td><a href="http://emsbot.com/#/?s=<?php echo $rs_or['tracking_no'];?>" target="_blank"><?php echo $rs_or['tracking_no'];?></a></td>
                                <td><?php if(!empty($rs_or['ship_date'])) echo thaidate($rs_or['ship_date']); ?></td>
                                <td><?php echo $order_status; ?></td>
                            </tr>
                            <tr>
                                <td colspan="7" style="border-top: 0px;">
                                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin' && $rs_or['order_status']=='P'){ ?>
                                        <a class="btn btn-success btn-sm" title="ยืนยันการชำระเงิน" href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><i class="  glyphicon glyphicon-ok"></i> ยืนยันการชำระเงิน</a>
                                    <?php } ?>

                                    <?php if($rs_or['order_status']=='F' || $rs_or['order_status']=='S'){ ?>
                                        <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                            <a class="btn btn-success btn-sm" title="ส่งสินค้า" href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-send"></i> ส่งสินค้า</a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a class="btn btn-warning btn-sm mr-1 <?php echo $btnstat;?>" title="<?php echo $btnorder;?>" href="<?php echo $baseUrl; ?>/back/order/payment/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-usd"></i> <?php echo $btnorder;?></a>
                                    <?php } ?>

                                    <a class="btn btn-info btn-sm" title="รายละเอียด" href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>
                                    <a class="btn btn-warning btn-sm <?php echo $btnstat;?>" title="แก้ไข" href="<?php echo $baseUrl; ?>/back/order/update/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-edit"></i> แก้ไข</a>
                                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                        <a class="btn btn-success btn-sm" title="พิมพ์" href="<?php echo $baseUrl; ?>/back/order/print/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-print"></i> พิมพ์</a>
                                    <?php } ?>
                                    <a class="btn btn-danger btn-sm <?php echo $btnstat_del;?> confirm" title="ลบ" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
                                    <a class="btn btn-warning btn-sm" title="แจ้งปัญหา" href="<?php echo $baseUrl; ?>/back/report/view/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-bullhorn"></i> แจ้งปัญหา</a>

                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $rs_or['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <a role="button" class="btn btn-primary" href="<?php echo $baseUrl; ?>/back/order/delete/<?php echo $rs_or['id']; ?>">ใช่ ยืนยันการลบ</a>
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
                            <a href="?page=1&search=<?php echo $search ?>&status=<?php echo $status ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                            <?php for($i=$page_start; ($i<$page_start+$page_number) && ($i<=$total_page); $i++){ ?>
                                <li><a href="?page=<?php echo $i; ?>&search=<?php echo $search ?>&status=<?php echo $status ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                        <li>
                            <a href="?page=<?php echo $total_page;?>&search=<?php echo $search ?>&status=<?php echo $status ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
  </section><!--/wrapper -->
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
            /*$("#tbl_Order").DataTable({
                aaSorting: [],
                responsive: true,
                "language": {
                    "url": "<?php echo $baseUrl; ?>/assets/DataTables/lang/Thai.json"
                }
            });*/

            $("#select_status").val("<?php echo $status; ?>");
            $("#search").val("<?php echo $search; ?>");
            $("button[type=reset]").click(function(){
                $(this).parent("form")[0].reset();
                $("#btn_search").click();
            });
        });
    </script>