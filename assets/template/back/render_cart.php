<?php

$db = new database();
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
	                <th>&nbsp;</th>
	            </tr>
	        </thead>
        <tbody>";

$i = 0;
$total_price = 0;
while ($rs_ct = $db->get($query_ct)) {
$key = array_search($rs_ct['id'], $_SESSION[_ss . 'cart']);
$total_price = $total_price + ($_SESSION[_ss . 'price'][$key] * $_SESSION[_ss . 'qty'][$key]);

echo   "<tr>
            <td>
                <a href='{$baseUrl}/assets/upload/product/{$rs_ct['url_picture']}' data-imagelightbox='a'>
                    <img src='{$baseUrl}/assets/upload/product/sm_{$rs_ct['url_picture']}' class='img-responsive' alt='Responsive image'>
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

echo    "<tr>
            <td colspan='6' style='text-align: right;'>
                <h4>จำนวนเงินรวมทั้งหมด ".number_format($total_price)." บาท</h4>
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
} else {
echo   "<div class='alert alert-danger' role='alert' style='margin:15px;'>
	        ไม่มีสินค้าในตะกร้าสินค้า <b>กรุณาเพิ่มสินค้าด้านบน</b>
	    </div>";
}

?>