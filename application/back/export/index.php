<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : สินค้าส่งออก';
$db = new database();

$sql_od = "SELECT os.order_date, pd.name as pname, od.quantity, (od.price*od.quantity) as price FROM order_details od ";
$sql_od .= "INNER JOIN products pd ON od.product_id = pd.id ";
$sql_od .= "INNER JOIN orders os ON os.id = od.order_id ";
$sql_od .= "ORDER BY od.order_id DESC";

$query_od = $db->query($sql_od);
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

<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">รายการสินค้าส่งออก</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="btn btn-default" 
                   href="<?php echo $baseUrl; ?>/back/export">
                    <i class="glyphicon glyphicon-refresh"></i>
                    โหลดหน้าจอใหม่
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="user-grid" class="grid-view">
                <table class="table table-striped table-custom" id="tbl_Export">
                    <thead>
                        <tr>
                            <th>
                                <a class="sort-link">วันที่ส่งออก</a>
                            </th>
                            <th>
                                <a class="sort-link">สินค้า</a>
                            </th>
                            <th>
                                <a class="sort-link">จำนวน</a>
                            </th>
                            <th  style="text-align: right;">
                                <a class="sort-link">ราคา(บาท)</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        while ($rs_od = $db->get($query_od)) {
                            $tr = ($i % 2 == 0) ? "odd" : "even";
                            ?>
                            <tr class="<?php echo $tr; ?>">
                                <td><?php echo thaidate($rs_od['order_date']); ?></td>
                                <td><?php echo $rs_od['pname']; ?></td>
                                <td><?php echo $rs_od['quantity']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($rs_od['price'],2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
            $("#tbl_Export").DataTable().fnSort([[0,'desc']]);
        });
    </script>