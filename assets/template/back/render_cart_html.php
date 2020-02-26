<?php

$db = new database();
$total_weight = 0;
$i = 0;
$product_qty = 0;
$kerry_shipping = 0;

//ตรวจสอบว่ามีสินค้าในตระกร้าหรือไม่ ถ้ามี ก็จะหาผลรวม ของจำนวนสินค้า ของแต่ละรายการทั้งหมด
$item_count = isset($_SESSION[_ss . "cart"]) ? count($_SESSION[_ss . "cart"]) : 0;
if ($item_count > 0 && isset($_SESSION[_ss . "qty"])) {
    $me_qty = 0;
    foreach ($_SESSION[_ss . "qty"] as $me_item) {
        $me_qty = $me_qty + $me_item;
    }
} else {
    $me_qty = 0;
}

//ตรวจสอบว่ามีสินค้าในตระกร้าหรือไม่ ถ้ามี ก็จะนำรหัสสินค้าแต่ละรายการที่มีในตระกร้าไปคิวรี่ select ขึ้นมาเก็บไว้ในตัวแปร
$me_count = 0;
if (isset($_SESSION[_ss . "cart"]) and $item_count > 0) {
    $items_id = "";
    foreach ($_SESSION[_ss . "cart"] as $item_id) {
        $items_id .= $item_id . ",";
    }
    $input_item = rtrim($items_id, ",");
    $option_ct = array(
        "table" => "products",
        "condition" => "id IN ({$input_item})"
    );
    $query_ct = $db->select($option_ct);
    $me_count = $db->rows($query_ct);
}

//ตรวจสอบว่ามีรายการสินค้าหรือไม่ ถ้ามีก็จะแสดงส่วนหัวของตารางแสดงตระกร้าสินค้า ถ้าไม่มีก็จะแสดงผลว่าไม่มีสินค้าในตระกร้า
if ($me_count > 0) { ?>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>รูปสินค้า</th>
            <th>สินค้า</th>
            <th style="text-align:center;">ราคา/หน่วย</th>
            <th style="width: 100px;text-align: center;">จำนวน</th>
            <th style="text-align:center;">จำนวนเงินรวม</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>

<?php

//วนลูปแสดงสินค้าในแต่ละรายการที่คิวรี่เก็บไว้ในตัวแปรข้างต้น
while ($rs_ct = $db->get($query_ct)) {

//ใช้รหัสสินค้าค้นหา session key ที่ใช้อ้างอิงรายการสินค้าแต่ละแถวในตระกร้าสินค้า
$key = array_search($rs_ct["id"], $_SESSION[_ss . "cart"]);

//หาจำนวนสินค้าที่มีในตระกร้าสินค้าของรายการ
$product_qty = $_SESSION[_ss . "qty"][$key];

//ดึงราคาสินค้าค่าขนส่งสินค้าด้วย kerry ของสินค้ามาเก็บไว้ในตัวแปรเพื่อนำไปแสดงผล
$kerry_shipping = !empty($rs_ct["kerry"])?$rs_ct["kerry"]:0;

//ดึงรูปแบบสินค้ามาแสดงในตระกร้า
$option_img = array(
    "table" => "images",
    "condition" => "ref_id='{$rs_ct['id']}' AND filetype='product'",
    "order" => "id",
    "limit" => "1"
);
$query_img = $db->select($option_img);

if($db->rows($query_img) > 0){
    $rs_img = $db->get($query_img);
    $filename_img = $rs_img["filename"];
}
else {
    $filename_img = "ecimage.jpg";
}

/*
 * ส่วนแสดงผลของสินค้าในตระกร้าสินค้าแต่ละแถว
 * แสดง รูปสินค้า/ชื่อสินค้า/ราคาต่อหน่วย/จำนวน/เงินรวม/action และรายละเอียดสินค้าของแต่ละรายการสินค้า
 */

 ?>


    <tr>
        <td>
            <a href="<?php echo "{$baseUrl}/assets/upload/product/{$filename_img}"; ?>" class="fancybox">
                <img src="<?php echo "{$baseUrl}/assets/upload/product/sm_{$filename_img}"; ?>" class="img-responsive" alt="Responsive image">
            </a>
        </td>
        <td>
            <?php 

                $product_name = $rs_ct['name'];

                if (isset($rs_ct['parent_product_id'])) {
                    $option_pd_parent = array(
                        "table" => "products",
                        "condition" => "id={$rs_ct['parent_product_id']}"
                    );

                    $query_pd_parent = $db->select($option_pd_parent);
                    $rs_pd_parent = $db->get($query_pd_parent);

                    $product_name = $rs_pd_parent['name'] . ' ' . $rs_ct['name'];
                }

                echo $product_name;
            ?>
        </td>
        <td style="text-align:right;"><?php echo number_format($_SESSION[_ss . 'price'][$key], 2) ?></td>
        <td>
            <input style="text-align:center;" type="text" name="qtyupdate[<?php echo $i; ?>]" value="<?php echo $_SESSION[_ss . 'qty'][$key]; ?>" class="form-control input-sm" autocomplete="off" data-validation="number" data-validation-allowing="float">
            <input type="hidden" name="arr_key_<?php echo $i; ?>" value="<?php echo $key; ?>">
            <input type="hidden" name="product_id_<?php echo $i; ?>" value="<?php echo $rs_ct['id']; ?>">
        </td>
        <td style="text-align:right;"><?php echo number_format($_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key], 2); ?></td>
        <td style="text-align:center;">
            <button type="button" class="btn btn-danger btn_delete_cart" href="<?php echo "{$baseUrl}/back/order/delete_cart"; ?>" value="<?php echo $rs_ct['id']; ?>">
                <span class='glyphicon glyphicon-trash'></span>
                ลบทิ้ง
            </button>
        </td>
    </tr>
    <tr>
        <td style="text-align:right;">รายละเอียดสินค้า</td>
        <td colspan="5">
            <input type="text" name="note[<?php echo $i; ?>]" value="<?php echo $_SESSION[_ss . 'note'][$key]; ?>" class="form-control input-sm">
        </td>
    </tr>

<?php 

$i++; }

/*
 * แสดงผลส่วนท้ายของตระกร้าสินค้า ส่วนสรุปผลจำนวนเงินรวมราคาสินค้าทั้งตระกร้า 
 * ก่อนและหลังรวมค่าขนส่ง, น้ำหนักรวมสินค้าทั้งตระกร้า, แสดงประเภทและราคาค่าขนส่ง
 */

 ?>

    <tr>
        <td colspan="6" style="text-align: right;">
            <h4>รวมเงิน(ยังไม่รวมค่าส่ง) <?php echo number_format($_SESSION[_ss . 'total_price']); ?> บาท</h4>
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: right;">
            <h4>น้ำหนักรวม <?php echo number_format($_SESSION[_ss . 'total_weight']); ?> กรัม</h4>
        </td>
    <tr>
    <tr>
        <td colspan="6" style="text-align: right;">
            <h4>
                <span id="wrap_shipping_text">คำนวณค่าส่ง ประเภท <span id="sp_shipping_type"></span> <span id="sp_shipping_rate"></span> บาท</span>
                <span id="wrap_shipping_warning">น้ำหนักเกิน 30 กิโลกรัม โปรดรอสอบถามแอดมิน!</span>
            </h4>
            <!--<a href="<?php echo "{$baseUrl}/back/order/ship_rate"; ?>" target="_blank">ตารางอัตราค่าส่ง</a>-->
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: right;">
            <h4>รวมเงินทั้งหมด(รวมค่าส่ง) <span id="sp_total_price"></span> บาท</h4>
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: right;">
            <button type="button" class="btn btn-primary recal">
                <span class="glyphicon glyphicon-refresh"></span>
                คำนวณสินค้าใหม่
            </button>
        </td>
    </tr>
    </tbody>
</table>

<?php

//การคำนวณหาอัตราค่าขนส่งของแต่ละประเภทการขนส่งเพื่อนำไปแสดงผลส่วนท้ายของตระกร้าสินค้า
$option_shipping = array(
    "table" => "shipping_rate",
    "condition" => "{$_SESSION[_ss . 'total_weight']} >= min_wg AND {$_SESSION[_ss . 'total_weight']} <= max_wg "
);
$query_shipping = $db->select($option_shipping);
$rs_shipping = $db->get($query_shipping);
$rows_shipping = $db->rows($query_shipping);

$parcel_shipping = 0;
$register_shipping = 0;
$EMS_shipping = 0;
$Flash_shipping = 0;
$JT_shipping = 0;
$Shopee_shipping = 0;


if($rows_shipping > 0){
    $parcel_shipping = $rs_shipping['parcel'];
    $register_shipping = $rs_shipping['register'];
    $EMS_shipping = $rs_shipping['EMS'];
	$Flash_shipping = $rs_shipping['Flash'];
	$JT_shipping = $rs_shipping['JT'];
	$Shopee_shipping = $rs_shipping['JT'];
}

?>

<script>

    function Update_shipping_type_price() {

        var shipping_rate = 0; 

        if (<?php echo $rows_shipping; ?> == 0){
            $("#wrap_shipping_text").hide();
            $("#wrap_shipping_warning").show();
        } else {
            $("#wrap_shipping_text").show();
            $("#wrap_shipping_warning").hide();

            var shipping_type = $("input[name=shipping_type]:checked").val();
            $("#sp_shipping_type").text(shipping_type);

            switch(shipping_type) {
                case "พัสดุธรรมดา":
                    shipping_rate = <?php echo $parcel_shipping; ?>;
                    $("#sp_shipping_rate").text(shipping_rate);
                    break;
                case "ลงทะเบียน":
                    shipping_rate = <?php echo $register_shipping; ?>;
                    $("#sp_shipping_rate").text(shipping_rate);
                    break;
                case "EMS":
                    shipping_rate = <?php echo $EMS_shipping; ?>;
                    $("#sp_shipping_rate").text(shipping_rate);
                    break;
                case "FLASH EXPRESS":
                    shipping_rate = <?php echo $Flash_shipping; ?>;
                    $("#sp_shipping_rate").text(shipping_rate);
                    break;
				case "J&T":
                    shipping_rate = <?php echo $JT_shipping; ?>;
                    $("#sp_shipping_rate").text(shipping_rate);
                    break;
			
                case "KERRY":
                    if (<?php echo $me_count; ?> == 1 && <?php echo $product_qty; ?> == 1){
                        shipping_rate = <?php echo $kerry_shipping; ?>;
                        $("#sp_shipping_rate").text(shipping_rate);
                    } else {
                        $("#wrap_shipping_text").hide();
                        $("#wrap_shipping_warning").show();
                        $("#wrap_shipping_warning").text("โปรดสอบถามค่าส่งจากแอดมิน!");
                    }
                    break;
                default:
                    $("#sp_shipping_rate").text("0");
                    break;
            }
        }

        $("#sp_total_price").text(addCommas(<?php echo $_SESSION[_ss . 'total_price']; ?>+shipping_rate));
        
    }

    if (<?php echo $_SESSION[_ss . 'total_weight']; ?> > 30000) {
        $("#rdo_register").attr("disabled", true);
        if($("#rdo_register").prop("checked"))
            $("#rdo_parcel").prop("checked", true);
    }else{
        $("#rdo_register").attr("disabled", false);
    }

    $("input[name=shipping_type]").change(function() {

        Update_shipping_type_price();

    });

    Update_shipping_type_price();

    $("a.fancybox").fancybox();

</script>

<?php } else { ?>

<div class="alert alert-danger" role="alert" style="margin:15px;">
    ไม่มีสินค้าในตะกร้าสินค้า <b>กรุณาเพิ่มสินค้าด้านบน</b>
</div>

<?php } ?>