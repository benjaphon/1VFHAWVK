<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : สินค้านำเข้า';
$db = new database();

$sql_pi = "SELECT pi.id, pi.import_date, p.name as pname, pi.quantity, pi.price, pi.note FROM product_import pi ";
$sql_pi .= "INNER JOIN products p ON pi.product_id = p.id ORDER BY pi.id DESC";

$query_pi = $db->query($sql_pi);
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
            <h1 class="page-header">รายการสินค้านำเข้า</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="btn btn-success new-data"
                   href="<?php echo $baseUrl; ?>/back/import/create">
                    <i class="fa fa-plus"></i>
                    เพิ่มใหม่
                </a>
                <a role="button" class="btn btn-default" 
                   href="<?php echo $baseUrl; ?>/back/import">
                    <i class="glyphicon glyphicon-refresh"></i>
                    โหลดหน้าจอใหม่
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="user-grid" class="grid-view">
                <table class="table table-striped table-custom" id="tbl_Import">
                    <thead>
                        <tr>
                            <th>
                                <a class="sort-link">วันที่สั่งซื้อ</a>
                            </th>
                            <th>
                                <a class="sort-link">สินค้า</a>
                            </th>
                            <th>
                                <a class="sort-link">จำนวน</a>
                            </th>
                            <th  style="text-align: right;">
                                <a class="sort-link">ค่าใช้จ่าย(บาท)</a>
                            </th>
                            <th class="button-column">&nbsp;</th>
                            <th>
                                <a class="sort-link">หมายเหตุ</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        while ($rs_pi = $db->get($query_pi)) {
                            $tr = ($i % 2 == 0) ? "odd" : "even";
                            ?>
                            <tr class="<?php echo $tr; ?>">
                                <td><?php echo thaidate($rs_pi['import_date']); ?></td>
                                <td><?php echo $rs_pi['pname']; ?></td>
                                <td><?php echo $rs_pi['quantity']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($rs_pi['price'],2); ?></td>
                                <td class="button-column">
                                    <a class="btn btn-danger btn-xs confirm" title="" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_pi['id']; ?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $rs_pi['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <a role="button" class="btn btn-primary" href="<?php echo $baseUrl; ?>/back/import/form_delete/<?php echo $rs_pi['id']; ?>">ใช่ ยืนยันการลบ</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $rs_pi['note']; ?></td>
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
            $("#tbl_Import").DataTable({aaSorting: []});
        });
    </script>