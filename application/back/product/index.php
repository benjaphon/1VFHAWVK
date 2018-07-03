<?php
/*
 * php code///////////**********************************************************
 */
$title = 'ระบบจัดการร้านค้า : สินค้า';
$db = new database();

$sql_pd = "SELECT * FROM products ";
$sql_pd .= "WHERE flag_status = 1 ";
$sql_pd .= "ORDER BY id DESC";

$query_pd = $db->query($sql_pd);

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
    #imagelightbox
    {
        position: fixed;
        z-index: 9999;

        -ms-touch-action: none;
        touch-action: none;
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
        <div class="col-lg-12">
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
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div id="user-grid" class="grid-view">
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
                                <a class="sort-link">วันที่ส่งได้</a>
                            </th>
                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                <th id="user-grid_c1">
                                    <a class="sort-link">ราคา</a>
                                </th>
                            <?php } ?>
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
                            <th class="button-column" id="user-grid_c6">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        while ($rs_pd = $db->get($query_pd)) {
                            $tr = ($i % 2 == 0) ? "odd" : "even";
                            ?>
                            <tr class="<?php echo $tr; ?>">
                                <td>         
                                    <a href="<?php echo base_url(); ?>/assets/upload/product/<?php echo !empty($rs_pd['url_picture'])?$rs_pd['url_picture']:'ecimage.jpg'; ?>" data-imagelightbox="a">
                                        <img src="<?php echo base_url(); ?>/assets/upload/product/sm_<?php echo !empty($rs_pd['url_picture'])?$rs_pd['url_picture']:'ecimage.jpg'; ?>" class="img-responsive" alt="Responsive image">
                                    </a>
                                </td>
                                <td>
                                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                    <a class="load_data" href="<?php echo $baseUrl; ?>/back/product/view/<?php echo $rs_pd['id']; ?>"><?php echo $rs_pd['name']; ?></a>
                                    <?php } else echo $rs_pd['name']; ?>
                                </td>
                                <td>
                                    <?php echo ($rs_pd['start_ship_date']!=null)? date('d-m-Y', strtotime($rs_pd['start_ship_date'])) : ''; ?>
                                </td>
                                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                    <td><?php echo $rs_pd['price']; ?></td>
                                <?php } ?>
                                <td><?php echo $rs_pd['agent_price']; ?></td>
                                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                    <td><?php echo $rs_pd['sale_price']; ?></td>
                                <?php } ?>
                                <td><?php echo round($rs_pd['parcel']).'/'.round($rs_pd['registered']).'/'.round($rs_pd['ems']).'/'.round($rs_pd['kerry']);; ?></td>
                                <td><?php echo $rs_pd['quantity']; ?></td>
                                <td class="button-column">
                                    <!--<a class="btn btn-info btn-xs load_data" title="" href="<?php echo $baseUrl; ?>/back/product/view/<?php echo $rs_pd['id']; ?>" target="_blank"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>-->
                                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                                    <a class="btn btn-info btn-xs load_data" title="" href="<?php echo $baseUrl; ?>/back/product/view/<?php echo $rs_pd['id']; ?>"><i class="glyphicon glyphicon-zoom-in"></i> รายละเอียด</a>
                                    <a class="btn btn-warning btn-xs load_data" title="" href="<?php echo $baseUrl; ?>/back/product/update/<?php echo $rs_pd['id']; ?>"><i class="glyphicon glyphicon-edit"></i> แก้ไข</a>
                                    <a class="btn btn-danger btn-xs confirm" title="" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_pd['id'];?>"><i class="glyphicon glyphicon-remove"></i> ลบ</a>
                                    <?php } ?>
                                    <!-- Modal -->
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
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/imagelightbox.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#tbl_Product").DataTable({
            aaSorting: [],
            responsive: true,
            "language": {
                "url": "<?php echo $baseUrl; ?>/assets/DataTables/lang/Thai.json"
            }
        });
        $('a').imageLightbox();
    });
</script>