<?php

$db = new database();
$total_weight = 0;
$item_count = isset($_SESSION[_ss . 'cart']) ? count($_SESSION[_ss . 'cart']) : 0;
if ($item_count > 0 && isset($_SESSION[_ss . 'qty'])) {
    $me_qty = 0;
    foreach ($_SESSION[_ss . 'qty'] as $me_item) {
        $me_qty = $me_qty + $me_item;
    }
} else {
    $me_qty = 0;
}

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
} else {
    $me_count = 0;
}

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
$total_price = 0;
while ($rs_ct = $db->get($query_ct)) {
$key = array_search($rs_ct['id'], $_SESSION[_ss . 'cart']);

/*$total_price += ($_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key]);
$total_weight += ($_SESSION[_ss . 'weight'][$key] * $_SESSION[_ss . 'qty'][$key]);*/

//Select Product Picture
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

echo   "<tr>
            <td>
                <a href='{$baseUrl}/assets/upload/product/{$filename_img}' data-imagelightbox='a'>
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
                    <span id='wrap_shipping_warning'>น้ำหนักเกิน 10 กิโลกรัม โปรดรอสอบถามแอดมิน!</span>
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

//Calculation Shipping Rate Show
$option_shipping = array(
    "table" => "shipping_rate",
    "condition" => "'{$_SESSION[_ss . 'total_weight']}' >= min_wg AND '{$_SESSION[_ss . 'total_weight']}' <= max_wgparcel "
);
$query_shipping = $db->select($option_shipping);
$rs_shipping = $db->get($query_shipping);

echo    "<script>
            if (".$_SESSION[_ss . 'total_weight']." > 2000) {
                $('#rdo_register').attr('disabled', true);

                if($('#rdo_register').prop('checked'))
                    $('#rdo_parcel').prop('checked', true);
            }else{
                $('#rdo_register').attr('disabled', false);
            }

            $('input[name=shipping_type]').change(function() {

                var shipping_rate = 0; 

                if (".$db->rows($query_shipping)." == 0){
                    $('#wrap_shipping_text').hide();
                    $('#wrap_shipping_warning').show();
                } else {
                    $('#wrap_shipping_text').show();
                    $('#wrap_shipping_warning').hide();

                    var shipping_type = $('input[name=shipping_type]:checked').val();
                    $('#sp_shipping_type').text(shipping_type);

                    switch(shipping_type) {
                        case 'พัสดุธรรมดา':
                            shipping_rate = ".$rs_shipping['parcel']."
                            $('#sp_shipping_rate').text(shipping_rate);
                            break;
                        case 'ลงทะเบียน':
                            shipping_rate = ".$rs_shipping['register']."
                            $('#sp_shipping_rate').text(shipping_rate);
                            break;
                        case 'EMS':
                            shipping_rate = ".$rs_shipping['EMS']."
                            $('#sp_shipping_rate').text(shipping_rate);
                            break;
                        default:
                            $('#sp_shipping_rate').text('0');
                            break;
                    }
                }

                $('#sp_total_price').text(addCommas(".$_SESSION[_ss . 'total_price']."+shipping_rate));

            });

            $('input[name=shipping_type]').change();

        </script>";

} else {
echo   "<div class='alert alert-danger' role='alert' style='margin:15px;'>
	        ไม่มีสินค้าในตะกร้าสินค้า <b>กรุณาเพิ่มสินค้าด้านบน</b>
	    </div>";
}

?>