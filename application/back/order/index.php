<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : รายการสั่งซื้อสินค้า';
$db = new database();

if($_SESSION[_ss . 'levelaccess'] == 'admin') {
    $option_or = array(
        "table" => "orders",
        "order" => "id DESC"
    );
}
else
{
    $option_or = array(
        "table" => "orders",
        "order" => "id DESC",
        "condition" => "user_id={$_SESSION[_ss . 'id']}"
    );
}

$query_or = $db->select($option_or);

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
        <div class="col-lg-12">
            <div class="subhead">
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
            </div>
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
                                <a class="sort-link">ที่อยู่ผู้รับ</a>
                            </th>
                            <th id="user-grid_c4">
                                <a class="sort-link">รายการสินค้า</a>
                            </th>
                            <th id="user-grid_c0">
                                <a class="sort-link">สถานะ</a>
                            </th>
                            <th class="button-column" id="user-grid_c6">&nbsp;</th>
                            <th id="user-grid_c1">
                                <a class="sort-link">เลขที่พัสดุ</a>
                            </th>
                            <th id="user-grid_c1">
                                <a class="sort-link">วันที่จัดส่ง</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        while ($rs_or = $db->get($query_or)) {
                            $tr = ($i % 2 == 0) ? "odd" : "even";
                            //สถานะ
                            /*
                            R จองสินค้า
                            P ชำรเงินแล้ว
                            F รับออเดอร์
                            S ส่งแล้ว
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
                                    $btnstat_del = '';
                                    break;
                                default:
                                    $order_status = 'ผิดพลาด';
                                    $btnorder = '-';
                                    $btnstat = 'disabled hidden';
                                    $btnstat_del = 'disabled hidden';
                                    break;
                            }

                            ?>
                            <tr class="<?php echo $tr; ?>">
                                <td>
                                    <a href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><?php echo $rs_or['id']; ?></a>
                                </td>
                                <td><?php echo thaidate($rs_or['order_datetime']); ?></td>
                                <td width="200px">
                                    <?php if (strlen($rs_or['receiver']) <= 100) {
                                                echo $rs_or['receiver'];
                                            } else {
                                                echo substr($rs_or['receiver'], 0, 100).'...';
                                            }    
                                    ?>
                                </td>
                                <td>
                                    <ul>
                                    <?php
                                        $sql_od = "SELECT d.*,p.id,p.name,p.start_ship_date FROM order_details d INNER JOIN products p ";
                                        $sql_od .= "ON d.product_id=p.id ";
                                        $sql_od .="WHERE d.order_id='{$rs_or['id']}' ";
                                        $query_od = $db->query($sql_od);

                                        while ($rs_od = $db->get($query_od)) { ?>

                                        <li>- <?php 

                                            if ($rs_od['start_ship_date']==null) {
                                                echo $rs_od['name'];
                                            }else{
                                                echo $rs_od['name']." (".date('d-m-Y', strtotime($rs_od['start_ship_date'])).")";
                                            }

                                        ?></li>

                                    <?php } ?>
                                    </ul>
                                </td>

                                <td><?php echo $order_status; ?></td>
                                <td class="button-column">

                                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin' && $rs_or['order_status']=='P'){ ?>
                                        <a class="btn btn-success btn-xs" title="ยืนยันการชำระเงิน" href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><i class="  glyphicon glyphicon-ok"></i> ยืนยันการชำระเงิน</a>
                                    <?php } ?>

                                    <?php if($rs_or['order_status']=='F' || $rs_or['order_status']=='S'){ ?>
                                        <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                            <a class="btn btn-success btn-xs" title="ส่งสินค้า" href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-send"></i> ส่งสินค้า</a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a class="btn btn-warning btn-xs <?php echo $btnstat;?>" title="<?php echo $btnorder;?>" href="<?php echo $baseUrl; ?>/back/order/payment/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-usd"></i> <?php echo $btnorder;?></a>
                                    <?php } ?>

                                    <a class="btn btn-info btn-xs" title="รายละเอียด" href="<?php echo $baseUrl; ?>/back/order/view/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>
                                    <a class="btn btn-warning btn-xs <?php echo $btnstat;?>" title="แก้ไข" href="<?php echo $baseUrl; ?>/back/order/update/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-edit"></i> แก้ไข</a>
                                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                        <a class="btn btn-success btn-xs" title="พิมพ์" href="<?php echo $baseUrl; ?>/back/order/print/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-print"></i> พิมพ์</a>
                                    <?php } ?>
                                    <a class="btn btn-danger btn-xs <?php echo $btnstat_del;?> confirm" title="ลบ" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
                                    <a class="btn btn-warning btn-xs" title="แจ้งปัญหา" href="<?php echo $baseUrl; ?>/back/report/view/<?php echo $rs_or['id']; ?>"><i class="glyphicon glyphicon-bullhorn"></i> แจ้งปัญหา</a>

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
                                <td><a href="http://emsbot.com/#/?s=<?php echo $rs_or['tracking_no'];?>" target="_blank"><?php echo $rs_or['tracking_no'];?></a></td>
                                <td><?php if(!empty($rs_or['ship_date'])) echo thaidate($rs_or['ship_date']); ?></td>
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
            $("#tbl_Order").DataTable({
                aaSorting: [],
                responsive: true,
                "language": {
                    "url": "<?php echo $baseUrl; ?>/assets/DataTables/lang/Thai.json"
                }
            });
        });
    </script>