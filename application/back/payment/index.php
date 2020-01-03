<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ชำระเงินหลายรายการ';

$perpage = 10;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = ($page - 1) * $perpage;

$db = new database();

$option_pm_bulk = array(
    "fields" => "pm_b.*, u.username",
    "table" => "payment_bulks AS pm_b INNER JOIN users AS u ON pm_b.user_id = u.id",
    "order" => "pm_b.id DESC",
    "limit" => "{$start},{$perpage}",
    "condition" => "1=1"
);

if($_SESSION[_ss . 'levelaccess'] == 'admin') {
    $option_pm_bulk["condition"] = "1=1";
}
else
{
    $option_pm_bulk["condition"] = "pm_b.user_id={$_SESSION[_ss . 'id']}";
}

$search = (isset($_GET['search']))?$_GET['search']:'';

if(isset($search) && !empty($search)){
    $option_pm_bulk["condition"] .= " AND (";
    $option_pm_bulk["condition"] .= " pm_b.id LIKE'%{$search}%' OR";
    $option_pm_bulk["condition"] .= " pm_b.pay_type LIKE'%{$search}%' OR";
    $option_pm_bulk["condition"] .= " pm_b.detail LIKE'%{$search}%' OR";
    $option_pm_bulk["condition"] .= " DATE_FORMAT(pm_b.created_at, '%d/%m/%Y') LIKE'%{$search}%')";
}

$query_pm_bulk = $db->select($option_pm_bulk);

unset($option_pm_bulk["limit"]);
$option_pm_bulk["fields"] = "COUNT(*) as num_row";
$query_pg = $db->select($option_pm_bulk);
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
            <h1 class="page-header">ชำระเงินหลายรายการ</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-6">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data"
                   href="<?php echo $baseUrl; ?>/back/payment/create">
                    <i class="fa fa-plus"></i>
                    เพิ่มใหม่
                </a>
                <a role="button" class="btn btn-default" 
                   href="<?php echo $baseUrl; ?>/back/payment">
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
                <button id="btn_search" type="submit" class="btn btn-primary">ค้นหา</button>
                <button type="reset" class="btn btn-primary">เคลียร์</button>
            </form>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="payment-grid" class="grid-view table-responsive">
                <table class="table table-striped table-custom" id="tbl_payment">
                    <thead>
                        <tr>
                            <th id="payment-grid_c0">
                                <a class="sort-link">รหัสชำระเงิน</a>
                            </th>
                            <th id="payment-grid_c0">
                                <a class="sort-link">ช่องทางการชำระเงิน</a>
                            </th>
                            <th id="payment-grid_c0">
                                <a class="sort-link">เพิ่มเติม</a>
                            </th>
                            <th id="payment-grid_c5">
                                <a class="sort-link">สร้างเมื่อ</a>
                            </th>
                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin') { ?>
                            <th id="payment-grid_c5">
                                <a class="sort-link">สร้างโดย</a>
                            </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rs_pm_bulk = $db->get($query_pm_bulk)) { ?>
                            <tr>
                                <td>
                                    <a class="load_data" href="<?php echo $baseUrl; ?>/back/payment/view/<?php echo $rs_pm_bulk['id']; ?>"><?php echo $rs_pm_bulk['id']; ?></a>
                                </td>
                                <td><?php echo $rs_pm_bulk['pay_type']; ?></td>
                                <td><?php echo $rs_pm_bulk['detail']; ?></td>
                                <td><?php echo thaidate($rs_pm_bulk['created_at']); ?></td>
                                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin') { ?>
                                    <td><?php echo $rs_pm_bulk['username']; ?></td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td colspan="5">

                                    
                                    <?php //Check if there is any order to confirm payment

                                    $sql = "SELECT COUNT(*) AS payment_order_count FROM payment_bulks AS pm_b ";
                                    $sql .= "INNER JOIN payments AS pm ON pm_b.id = pm.bulk_id ";
                                    $sql .= "INNER JOIN orders AS o ON pm.order_id = o.id ";
                                    $sql .= "WHERE pm_b.id = {$rs_pm_bulk['id']} AND o.order_status = 'P'";

                                    $query = $db->query($sql);
                                    $row = $db->get($query);
                                    $Is_there_payment_confirm = ($row['payment_order_count'] > 0);

                                    ?>

                                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin' && $Is_there_payment_confirm){ ?>
                                        <a class="btn btn-success btn-sm" title="ยืนยันการชำระเงิน" href="<?php echo $baseUrl; ?>/back/payment/view/<?php echo $rs_pm_bulk['id']; ?>"><i class="  glyphicon glyphicon-ok"></i> ยืนยันการชำระเงิน</a>
                                    <?php } ?>
                                    <a class="btn btn-info btn-sm" title="" href="<?php echo $baseUrl; ?>/back/payment/view/<?php echo $rs_pm_bulk['id']; ?>"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>
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
            $("#search").val("<?php echo $search; ?>");
            $("button[type=reset]").click(function(){
                $(this).parent("form")[0].reset();
                $("#btn_search").click();
            });
        });
    </script>