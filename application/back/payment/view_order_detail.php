<table class="table" style="font-size: 12px;">
    <thead>
        <tr>
            <th>ชื่อสินค้า</th>
            <th style="text-align: right;">ราคา(บาท)</th>
            <th style="text-align: right;">จำนวน</th>
            <th style="text-align: right;">รวม</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $product_qty = 0;
        $kerry_shipping = 0;
        $grand_total = 0;
        $grand_total_weight = 0;
        while ($rs_od = $db->get($query_od)) {
            $product_qty = $rs_od['quantity'];
            $kerry_shipping = !empty($rs_od['kerry'])?$rs_od['kerry']:0;

            $total_price = $rs_od['price'] * $rs_od['quantity'];
            $grand_total += $total_price;

            $total_weight = $rs_od['weight'] * $rs_od['quantity'];
            $grand_total_weight += $total_weight;

            array_push($_SESSION[_ss . 'cart'], $rs_od['product_id']);

            ?>
            <tr>
                <td><?php echo $rs_od['name']; ?></td>
                <td style="text-align: right;"><?php echo number_format($rs_od['price'], 2); ?></td>
                <td style="text-align: right;"><?php echo $rs_od['quantity']; ?></td>
                <td style="text-align: right;"><?php echo number_format($total_price, 2); ?></td>
            </tr>
        <?php } 
        
        $isCal = true;

        if ($rs_order['ship_price']==null) {

            $shipping_rate = shipping_calculation();
            $shipping_type = $rs_order['shipping_type'];

            if ($rows_count > 1 || $product_qty > 1){
                $kerry_shipping = 0;
            }

            $mapping = [
                "พัสดุธรรมดา" => $shipping_rate['parcel'],
                "ลงทะเบียน" => $shipping_rate['register'],
                "EMS" => $shipping_rate['ems'],
                "FLASH EXPRESS" => $shipping_rate['flash'],
                "J&T" => $shipping_rate['jt'],
                "KERRY" => $kerry_shipping
            ];

            $shipping_fees = $mapping[$shipping_type];

            if ($shipping_fees == -1) {
                $shipping_fees = 0;
                $isCal = false;
            }
        
        } else {
            $shipping_fees = $rs_order['ship_price'];
        }

        $grand_total_with_ship = $grand_total + $shipping_fees;
        
        ?>
        <tr class="info">
            <td colspan="3"></td>
            <td colspan="2" style="text-align: right;">
                
                <label for="pay_money" class="text-bold control-label required">ค่าส่ง (ตามเงื่อนไข)</label>
                <h4>
                    <input type="hidden" id="hidden_ship_price" value="<?php echo $shipping_fees; ?>">
                    <p><?php echo $shipping_fees; ?> บาท</p>
                </h4>
            </td>
        </tr>
        <tr class="info">
            <input type="hidden" id="hidden_grand_weight" value="<?php echo $grand_total_weight ?>">
            <td colspan="5" style="text-align: right;">
                <h4>
                    <?php if ($isCal){ ?>
                        <p>น้ำหนักรวม <?php echo number_format($grand_total_weight); ?> กรัม กล่องไซต์ <?php echo $rs_order['boxsize_code']; ?></p>
                    <?php } else { ?>
                        <p>ไม่สามารถคำนวณค่าส่งได้ โปรดสอบถามค่าส่งจากแอดมิน!</p>
                    <?php } ?>
                </h4>
                
            </td>
        </tr>
        <tr class="info">
            <input type="hidden" id="hidden_grand_total" value="<?php echo $grand_total_with_ship ?>">
            <td colspan="5" style="text-align: right;"><h4><strong>รวมทั้งหมด <span id="grand_total"><?php echo number_format($grand_total_with_ship); ?></span> บาท</strong></h4></td>
        </tr>
        <tr class="info">
            <td colspan="5">ที่อยู่ผู้ส่ง :<br><?php echo $rs_order['sender']; ?></td>
        </tr>
        <tr class="info">
            <td colspan="5">ที่อยู่ผู้รับ :<br><?php echo $rs_order['receiver']; ?></td>
        </tr>
        <tr class="info">
            <td colspan="5">ประเภทการส่ง :<br><?php echo $rs_order['shipping_type']; ?></td>
        </tr>
    </tbody>
</table>