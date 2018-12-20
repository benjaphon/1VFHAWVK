<?php
/*
 * php code///////////**********************************************************
 */
if (!isset($_GET['id'])) {
    header("location:" . $baseUrl . "/back/product");
}

$db = new database();
$option_product = array(
    "table" => "products",
    "condition" => "id='{$_GET['id']}'"
);
$query_product = $db->select($option_product);
$rs_product = $db->get($query_product);

$option_img = array(
    "table" => "images",
    "condition" => "ref_id='{$_GET['id']}' AND filetype = 'product' "
);
$query_img = $db->select($option_img);


$title = 'รายละเอียดสินค้า : ' . $rs_product['name'];
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
<style>
@import "http://fonts.googleapis.com/css?family=Droid+Sans";
.abcd img{
width:200px;
padding:5px;
border:1px solid #e8debd
}
</style>
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">ข้อมูลสินค้า : <?php echo $rs_product['name']; ?></h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" class="search-button btn btn-danger" href="<?php echo $baseUrl; ?>/back/product">
                    <i class="glyphicon glyphicon-circle-arrow-left"></i>
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-sm-6">
                        <?php while ($rs_img = $db->get($query_img)) { ?>
                            <div id="div-image-<?php echo $rs_img['id']; ?>" class="abcd col-sm-4">
                                <a title="" download="<?php echo $rs_img['filename']; ?>" href="<?php echo $baseUrl ?>/assets/upload/product/<?php echo $rs_img['filename']; ?>"><img src="<?php echo $baseUrl ?>/assets/upload/product/<?php echo $rs_img['filename'];?>"></a>
                            </div>
                        <?php } ?>         
                    </div>
                </div>
                <?php  if (!empty($rs_product['video_filename'])) { ?>
                <div class="form-group">
                    <div class="col-sm-6">
                            <video width="400" controls>
                                <source src="<?php echo $baseUrl ?>/assets/upload/product/<?php echo $rs_product['video_filename']; ?>" id="video_here">
                                    Your browser does not support HTML5 video.
                            </video>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <p><span style="padding-right: 10px;">วันที่ส่งได้</span><?php echo date('d/m/Y', strtotime($rs_product['start_ship_date'])); ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <h4><b><?php echo $rs_product['name']; ?></b></h4>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <p><?php echo $rs_product['description']; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <p><span style="padding-right: 10px;">ราคาขายส่ง</span><?php echo $rs_product['wholesale_price']; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <p><span style="padding-right: 10px;">ราคา ตท</span><?php echo $rs_product['agent_price']; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <p><span style="padding-right: 10px;">ค่าส่ง (Kerry)</span><?php echo $rs_product['kerry']; ?></p>
                    </div>
                </div>
                
                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                <h3>ส่วนแสดงผลเฉพาะแอดมิน</h3>
                <div class="form-group">
                    <label for="price" class="col-sm-2 control-label text-bold">ราคาต้นทุน</label>
                    <div class="col-sm-4">
                        <label name="price" class="control-label"><?php echo $rs_product['price']; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sale_price" class="col-sm-2 control-label text-bold">ราคาขาย</label>
                    <div class="col-sm-4">
                        <label name="sale_price" class="control-label"><?php echo $rs_product['sale_price']; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="quantity" class="col-sm-2 control-label text-bold">คงเหลือ</label>
                    <div class="col-sm-4">
                        <label name="quantity" class="control-label"><?php echo $rs_product['quantity']; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="weight" class="col-sm-2 control-label text-bold">น้ำหนัก</label>
                    <div class="col-sm-4">
                        <label name="weight" class="control-label"><?php echo $rs_product['weight']; ?></label>
                    </div>
                </div>
                <?php } ?>


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
    $(document).ready(function() {
        CKEDITOR.replace('editor');
        CKEDITOR.config.readOnly = true;
    });
</script>