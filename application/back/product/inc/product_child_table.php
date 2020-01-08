<div id="user-grid" class="grid-view table-responsive">
    <table id="tbl_Product" class="table table-striped table-custom">
        <thead>
            <tr>
                <th id="user-grid_c0">
                    <a class="sort-link">#</a>
                </th>
                <th id="user-grid_c0">
                    <a class="sort-link">สินค้า</a>
                </th>
                <th id="user-grid_c0" style="display:none;">
                    <a class="sort-link">รายละเอียด</a>
                </th>
                <th id="user-grid_c0" style="display:none;">
                    <a class="sort-link">วันที่ส่ง</a>
                </th>
                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                <th id="user-grid_c1">
                    <a class="sort-link">ราคา</a>
                </th>
                <?php } ?>
                <th id="user-grid_c1">
                    <a class="sort-link">ราคาขายส่ง</a>
                </th>
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
                    <a class="sort-link">จำนวน</a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            while ($rs_pd = $db->get($query_child_pd)) { ++$i; ?>
                <tr>
                    <td>
                        <button onclick="copyToClipboardTable(<?php echo $i; ?>)">copy</button>
                    </td>
                    <td id="h_product_name_<?php echo $i; ?>"><?php echo $rs_pd['name']; ?></td>

                    <td id="div_desc_<?php echo $i; ?>" style="display:none;"><?php echo $rs_pd['description']; ?></td>

                    <td id="p_start_ship_date_<?php echo $i; ?>" style="display:none"><?php echo ($rs_pd['start_ship_date']!=null)? date('d/m/Y', strtotime($rs_pd['start_ship_date'])) : ''; ?></td>

                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                        <td id="p_cost_price_<?php echo $i; ?>"><?php echo $rs_pd['price']; ?></td>
                    <?php } ?>

                    <td id="p_wholesale_price_<?php echo $i; ?>"><?php echo $rs_pd['wholesale_price']; ?></td>

                    <td id="p_agent_price_<?php echo $i; ?>"><?php echo $rs_pd['agent_price']; ?></td>

                    <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                        <td><?php echo $rs_pd['sale_price']; ?></td>
                    <?php } ?>

                    <td id="p_shipping_<?php echo $i; ?>"><?php echo round($rs_pd['cal_parcel']); ?>/<?php echo round($rs_pd['cal_register']); ?>/<?php echo round($rs_pd['cal_EMS']); ?>/<?php echo round($rs_pd['kerry']); ?></td>
                    <td id="p_quantity_<?php echo $i; ?>"><?php echo $rs_pd['quantity']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
