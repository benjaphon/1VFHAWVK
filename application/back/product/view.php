<?php
/*
 * php code///////////**********************************************************
 */
if (!isset($_GET['id'])) {
    header("location:" . $baseUrl . "/back/product");
}

$db = new database();
$option_product = array(
    "fields" => "p.*, s.parcel AS cal_parcel, s.register AS cal_register, s.EMS AS cal_EMS",
    "table" => "products AS p LEFT JOIN shipping_rate AS s ON p.weight >= s.min_wg AND p.weight <= s.max_wg",
    "condition" => "p.id='{$_GET['id']}'"
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
                        <p id="p_start_ship_date">วันที่ส่งได้ <?php echo date('d/m/Y', strtotime($rs_product['start_ship_date'])); ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <h4 id="h_product_name"><b><?php echo $rs_product['name']; ?></b></h4>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div id="div_desc"><?php echo $rs_product['description']; ?></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <p id="p_price">ราคา <?php echo $rs_product['agent_price']; ?> บาท</p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <p id="p_shipping">ค่าส่ง <?php echo round($rs_product['cal_parcel']); ?>/<?php echo round($rs_product['cal_register']); ?>/<?php echo round($rs_product['cal_EMS']); ?>/<?php echo round($rs_product['kerry']); ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <button onclick="copyToClipboard()">คัดลอกข้อความ</button>
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
                    <label for="price" class="col-sm-2 control-label text-bold">ราคาขายส่ง</label>
                    <div class="col-sm-4">
                        <label name="price" class="control-label"><?php echo $rs_product['wholesale_price']; ?></label>
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

    function copyToClipboard() {
        var $temp = $("<textarea cols='40' rows='5'>");
        $("body").append($temp);
        start_ship_date = $('#p_start_ship_date').text();
        product_name = $('#h_product_name').text();
        desc = $('#div_desc').text();
        price = $('#p_price').text();
        shipping = $('#p_shipping').text();
        $temp.val(start_ship_date+'\n\n'+product_name+'\n\n'+desc+'\n\n'+price+'\n\n'+shipping).select();
        document.execCommand("copy");
        $temp.remove();
        alert("คัดลอกข้อความแล้ว");
    }
</script>