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
                    <label for="product_image" class="col-sm-2 control-label text-bold">รูปภาพประจำสินค้า</label>
                    <div class="col-sm-4">
                        <img src="<?php echo $baseUrl ?>/assets/upload/product/md_<?php echo $rs_product['url_picture'];?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label text-bold">ชื่อสินค้า</label>
                    <div class="col-sm-4">
                        <label name="name" class="control-label"><?php echo $rs_product['name']; ?></label>
                    </div>
                </div>
                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                <div class="form-group">
                    <label for="price" class="col-sm-2 control-label text-bold">ราคา</label>
                    <div class="col-sm-4">
                        <label name="price" class="control-label"><?php echo $rs_product['price']; ?></label>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="agent_price" class="col-sm-2 control-label text-bold">ราคา ตท.</label>
                    <div class="col-sm-4">
                        <label name="agent_price" class="control-label"><?php echo $rs_product['agent_price']; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sale_price" class="col-sm-2 control-label text-bold">ราคาขาย</label>
                    <div class="col-sm-4">
                        <label name="sale_price" class="control-label"><?php echo $rs_product['sale_price']; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-bold">ค่าส่ง (ธรรมดา/ลงทะเบียน/EMS/KERRY)</label>
                    <div class="col-sm-1">
                        <label name="parcel" class="control-label"><?php echo $rs_product['parcel']; ?></label>
                    </div>
                    <div class="col-sm-1">
                        <label name="registered" class="control-label"><?php echo $rs_product['registered']; ?></label>
                    </div>
                    <div class="col-sm-1">
                        <label name="ems" class="control-label"><?php echo $rs_product['ems']; ?></label>
                    </div>
                    <div class="col-sm-1">
                        <label name="kerry" class="control-label"><?php echo $rs_product['kerry']; ?></label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="start_ship_date" class="col-sm-2 control-label text-bold">วันที่ส่งได้</label>
                    <div class="col-sm-4">
                        <label name="start_ship_date" class="control-label"><?php echo date('d-m-Y', strtotime($rs_product['start_ship_date'])); ?></label>
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
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea id="editor" name="description" class="form-control input-sm"><?php echo $rs_product['description']; ?></textarea>
                    </div>
                </div>
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