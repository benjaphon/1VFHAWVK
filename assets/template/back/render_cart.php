<?php
/*
 * Name: render cart file.
 * Description: สำหรับแสดงผลสินค้าที่มีในตระกร้าแต่ละครั้งที่เกิด Action Insert/Update/Delete กับตระกร้าสินค้า ก็จะต้อง Render ไฟล์นี้เสมอ
 * Author: Benjaphon
 * Last Modified: Benjaphon
 */

$db = new database();
$total_weight = 0;

//ตรวจสอบว่ามีสินค้าในตระกร้าหรือไม่ ถ้ามี ก็จะหาผลรวม ของจำนวนสินค้า ของแต่ละรายการทั้งหมด
$item_count = isset($_SESSION[_ss . 'cart']) ? count($_SESSION[_ss . 'cart']) : 0;
if ($item_count > 0 && isset($_SESSION[_ss . 'qty'])) {
    $me_qty = 0;
    foreach ($_SESSION[_ss . 'qty'] as $me_item) {
        $me_qty = $me_qty + $me_item;
    }
} else {
    $me_qty = 0;
}

//ตรวจสอบว่ามีสินค้าในตระกร้าหรือไม่ ถ้ามี ก็จะนำรหัสสินค้าแต่ละรายการที่มีในตระกร้าไปคิวรี่ select ขึ้นมาเก็บไว้ในตัวแปร
$me_count = 0;
if (isset($_SESSION[_ss . 'cart']) and $item_count > 0) {
    $items_id = "";
    foreach ($_SESSION[_ss . 'cart'] as $item_id) {
        $items_id = $items_id . $item_id . ",";
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
if ($me_count > 0) { 
echo   "<table class='table table-bordered table-striped'>
	        <thead>
	            <tr>
	                <th>รูปสินค้า</th>
	                <th>สินค้า</th>
	                <th style='text-align:center;'>ราคา/หน่วย</th>
	                <th style='width: 100px;text-align: center;''>จำนวน</th>
                    <th style='text-align:center;'>จำนวนเงินรวม</th>
                    <!--<th style='text-align:center;'>น้ำหนักรวม (กรัม)</th>-->
	                <th>&nbsp;</th>
	            </tr>
	        </thead>
        <tbody>";

$i = 0;
$product_qty = 0;
$kerry_shipping = 0;
$total_price = 0;

//วนลูปแสดงสินค้าในแต่ละรายการที่คิวรี่เก็บไว้ในตัวแปรข้างต้น
while ($rs_ct = $db->get($query_ct)) {

//ใช้รหัสสินค้าค้นหา session key ที่ใช้อ้างอิงรายการสินค้าแต่ละแถวในตระกร้าสินค้า
$key = array_search($rs_ct['id'], $_SESSION[_ss . 'cart']);

//หาจำนวนสินค้าที่มีในตระกร้าสินค้าของรายการ
$product_qty = $_SESSION[_ss . 'qty'][$key];

//ดึงราคาสินค้าค่าขนส่งสินค้าด้วย kerry ของสินค้ามาเก็บไว้ในตัวแปรเพื่อนำไปแสดงผล
$kerry_shipping = (isset($rs_ct['kerry']) && trim($rs_ct['kerry']) <> '')?$rs_ct['kerry']:0;

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
    $filename_img = $rs_img['filename'];
}
else {
    $filename_img = 'ecimage.jpg';
}

/*
 * ส่วนแสดงผลของสินค้าในตระกร้าสินค้าแต่ละแถว
 * แสดง รูปสินค้า/ชื่อสินค้า/ราคาต่อหน่วย/จำนวน/เงินรวม/action และรายละเอียดสินค้าของแต่ละรายการสินค้า
 */
echo   "<tr>
            <td>
                <a href='{$baseUrl}/assets/upload/product/{$filename_img}' class='fancybox'>
                    <img src='{$baseUrl}/assets/upload/product/sm_{$filename_img}' class='img-responsive' alt='Responsive image'>
                </a>
            </td>
            <td>{$rs_ct['name']}</td>
            <td style='text-align:right;'>".number_format($_SESSION[_ss . 'price'][$key], 2)."</td>
            <td>
                <input style='text-align:center;' type='text' name='qtyupdate[{$i}]' value='{$_SESSION[_ss . 'qty'][$key]}' class='form-control input-sm' autocomplete='off' data-validation='number' data-validation-allowing='float'>
                <input type='hidden' name='arr_key_{$i}' value='{$key}'>
                <input type='hidden' name='product_id_{$i}' value='{$rs_ct['id']}'>
            </td>
            <td style='text-align:right;'>".number_format($_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key], 2)."</td>
            <!--<td style='text-align:right;'>".number_format($_SESSION[_ss . 'weight'][$key] * $_SESSION[_ss . 'qty'][$key])."</td>-->
            <td style='text-align:center;'>
                <button type='button' data-toggle='modal' data-target='#deleteModal{$rs_ct['id']}' class='btn btn-danger'>
                    <span class='glyphicon glyphicon-trash'></span>
                    ลบทิ้ง
                </button>
            </td>
        </tr>
        <tr>
            <td style='text-align:right;'>รายละเอียดสินค้า</td>
            <td colspan='5'>
                <input type='text' name='note[{$i}]' value='{$_SESSION[_ss . 'note'][$key]}' class='form-control input-sm'>
            </td>
        </tr>
        <!-- Modal -->
        <div class='modal fade' id='deleteModal{$rs_ct['id']}' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                        <h4 class='modal-title' id='myModalLabel'>แจ้งเตือนการลบข้อมูล</h4>
                    </div>
                    <div class='modal-body'>
                        คุณยืนยันต้องการจะลบข้อมูลนี้ ใช่หรือไม่?
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>ไม่ใช่</button>
                        <button type='button' class='btn btn-primary btn_delete_cart' href='{$baseUrl}/back/order/delete_cart' data-dismiss='modal' value='{$rs_ct['id']}'>ใช่ ยืนยันการลบ</button>
                    </div>
                </div>
            </div>
        </div>";
$i++; }

/*
 * แสดงผลส่วนท้ายของตระกร้าสินค้า ส่วนสรุปผลจำนวนเงินรวมราคาสินค้าทั้งตระกร้า 
 * ก่อนและหลังรวมค่าขนส่ง, น้ำหนักรวมสินค้าทั้งตระกร้า, แสดงประเภทและราคาค่าขนส่ง
 */
echo    "
        <tr>
            <td colspan='6' style='text-align: right;'>
                <h4>รวมเงิน(ยังไม่รวมค่าส่ง) ".number_format($_SESSION[_ss . 'total_price'])." บาท</h4>
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align: right;'>
                <h4>น้ำหนักรวม ".number_format($_SESSION[_ss . 'total_weight'])." กรัม</h4>
            </td>
        <tr>
        <tr>
            <td colspan='6' style='text-align: right;'>
                <h4>
                    <span id='wrap_shipping_text'>คำนวณค่าส่ง ประเภท <span id='sp_shipping_type'/> <span id='sp_shipping_rate'/> บาท</span>
                    <span id='wrap_shipping_warning'>น้ำหนักเกิน 30 กิโลกรัม โปรดรอสอบถามแอดมิน!</span>
                </h4>
                <!--<a href='{$baseUrl}/back/order/ship_rate' target='_blank'>ตารางอัตราค่าส่ง</a>-->
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align: right;'>
                <h4>รวมเงินทั้งหมด(รวมค่าส่ง) <span id='sp_total_price'/> บาท</h4>
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align: right;'>
                <button type='button' class='btn btn-primary recal'>
                    <span class='glyphicon glyphicon-refresh'></span>
                    คำนวณสินค้าใหม่
                </button>
            </td>
        </tr>
        </tbody>
    </table>";

//การคำนวณหาอัตราค่าขนส่งของแต่ละประเภทการขนส่งเพื่อนำไปแสดงผลส่วนท้ายของตระกร้าสินค้า
$option_shipping = array(
    "table" => "shipping_rate",
    "condition" => "'{$_SESSION[_ss . 'total_weight']}' >= min_wg AND '{$_SESSION[_ss . 'total_weight']}' <= max_wg "
);
$query_shipping = $db->select($option_shipping);
$rs_shipping = $db->get($query_shipping);
$rows_shipping = $db->rows($query_shipping);

$parcel_shipping = 0;
$register_shipping = 0;
$EMS_shipping = 0;
$Flash_shipping = 0;

if($rows_shipping > 0){
    $parcel_shipping = $rs_shipping['parcel'];
    $register_shipping = $rs_shipping['register'];
    $EMS_shipping = $rs_shipping['EMS'];
	$Flash_shipping = $rs_shipping['Flash'];
}

echo    "<script>
            if (".$_SESSION[_ss . 'total_weight']." > 30000) {
                $('#rdo_register').attr('disabled', true);
                if($('#rdo_register').prop('checked'))
                    $('#rdo_parcel').prop('checked', true);
            }else{
                $('#rdo_register').attr('disabled', false);
            }

            $('input[name=shipping_type]').change(function() {

                var shipping_rate = 0; 

                if (".$rows_shipping." == 0){
                    $('#wrap_shipping_text').hide();
                    $('#wrap_shipping_warning').show();
                } else {
                    $('#wrap_shipping_text').show();
                    $('#wrap_shipping_warning').hide();

                    var shipping_type = $('input[name=shipping_type]:checked').val();
                    $('#sp_shipping_type').text(shipping_type);

                    switch(shipping_type) {
                        case 'พัสดุธรรมดา':
                            shipping_rate = ".$parcel_shipping.";
                            $('#sp_shipping_rate').text(shipping_rate);
                            break;
                        case 'ลงทะเบียน':
                            shipping_rate = ".$register_shipping.";
                            $('#sp_shipping_rate').text(shipping_rate);
                            break;
                        case 'EMS':
                            shipping_rate = ".$EMS_shipping.";
                            $('#sp_shipping_rate').text(shipping_rate);
                            break;
						case 'FLASH EXPRESS':
                            shipping_rate = ".$Flash_shipping.";
                            $('#sp_shipping_rate').text(shipping_rate);
                            break;
                        case 'KERRY':
                            if (".$me_count." == 1 && ".$product_qty." == 1){
                                shipping_rate = ".$kerry_shipping.";
                                $('#sp_shipping_rate').text(shipping_rate);
                            } else {
                                $('#wrap_shipping_text').hide();
                                $('#wrap_shipping_warning').show();
                                $('#wrap_shipping_warning').text('โปรดสอบถามค่าส่งจากแอดมิน!');
                            }
                            break;
                        default:
                            $('#sp_shipping_rate').text('0');
                            break;
                    }
                }

                $('#sp_total_price').text(addCommas(".$_SESSION[_ss . 'total_price']."+shipping_rate));

            });

            $('input[name=shipping_type]').change();

            $('a.fancybox').fancybox();

        </script>";

} else {
echo   "<div class='alert alert-danger' role='alert' style='margin:15px;'>
	        ไม่มีสินค้าในตะกร้าสินค้า <b>กรุณาเพิ่มสินค้าด้านบน</b>
	    </div>";
}

?>