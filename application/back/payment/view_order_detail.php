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

            ?>
            <tr>
                <td><?php echo $rs_od['name']; ?></td>
                <td style="text-align: right;"><?php echo number_format($rs_od['price'], 2); ?></td>
                <td style="text-align: right;"><?php echo $rs_od['quantity']; ?></td>
                <td style="text-align: right;"><?php echo number_format($total_price, 2); ?></td>
            </tr>
        <?php } ?>
        <tr class="info">
            <td colspan="3"></td>
            <td colspan="2" style="text-align: right;">
                
                <label for="pay_money" class="text-bold control-label required">ค่าส่ง (ตามเงื่อนไข)</label>

                <?php 

                    $option_shipping = array(
                        "table" => "shipping_rate",
                        "condition" => "'{$grand_total_weight}' >= min_wg AND '{$grand_total_weight}' <= max_wg "
                    );
                    $query_shipping = $db->select($option_shipping);
                    $rs_shipping = $db->get($query_shipping);

                    $shipping_fees = 0;
                    $grand_total_with_ship = 0;

                    if ($rs_order['ship_price']==null) {
                        switch ($rs_order['shipping_type']) {
                            case 'พัสดุธรรมดา':
                                $shipping_fees = $rs_shipping['parcel'];
                                break;
                            case 'ลงทะเบียน':
                                $shipping_fees = $rs_shipping['register'];
                                break;
                            case 'EMS':
                                $shipping_fees = $rs_shipping['EMS'];
                                break;
                            case 'FLASH EXPRESS':
                                $shipping_fees = $rs_shipping['Flash'];
                                break;
                            case 'KERRY':
                                if ($rows_count == 1 && $product_qty == 1){
                                    $shipping_fees = $kerry_shipping;
                                }
                                break;
                        }

                        $grand_total_with_ship = $grand_total + $shipping_fees;
                    }else{
                        $shipping_fees = $rs_order['ship_price'];
                        $grand_total_with_ship = $grand_total + $shipping_fees;
                    }

                ?>

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
                    <?php if ($db->rows($query_shipping) > 0){ ?>
                        <p>น้ำหนักรวม <?php echo number_format($grand_total_weight); ?> กรัม</p>
                    <?php } else { ?>
                        <p>น้ำหนักเกิน 10 กิโลกรัม โปรดรอสอบถามแอดมิน!</p>
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