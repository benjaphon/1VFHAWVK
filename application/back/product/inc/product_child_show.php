<div id="product_child_<?php echo $product_child_id; ?>" class="panel panel-default">
    <div data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $product_child_id; ?>" class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $product_child_id; ?>">
                สินค้าย่อย <?php echo $product_child_id; ?>
            </a>
            <a data-toggle="collapse" product-child-id="<?php echo $product_child_id; ?>"  class="close product-child" >&times </a>
        </h4>
    </div>
    <div id="collapse_<?php echo $product_child_id; ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="form-group">
                <div class="col-sm-6">
                    <p id="p_start_ship_date_<?php echo $product_child_id; ?>">วันที่ส่งได้ <?php echo date('d/m/Y', strtotime($row['start_ship_date'])); ?></p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <?php

                        $product_name = $row['name'];

                        if (isset($row['parent_product_id'])) {
                            $option_pd_parent = array(
                                "table" => "products LEFT JOIN box_sizes ON products.box_size = box_sizes.id",
                                "condition" => "id={$row['parent_product_id']}"
                            );

                            $query_pd_parent = $db->select($option_pd_parent);
                            $rs_pd_parent = $db->get($query_pd_parent);

                            $product_name = $rs_pd_parent['name'] . ' ' . $row['name'];
                        }

                    ?>
                    <h4 id="h_product_name_<?php echo $product_child_id; ?>"><b><?php echo $product_name; ?></b></h4>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <div id="div_desc_<?php echo $product_child_id; ?>"><?php echo $row['description']; ?></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <p id="p_wholesale_price_<?php echo $product_child_id; ?>">ราคาส่ง <?php echo $row['wholesale_price']; ?> บาท</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <p id="p_agent_price_<?php echo $product_child_id; ?>">ราคา <?php echo $row['agent_price']; ?> บาท</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <p id="p_quantity_<?php echo $product_child_id; ?>">จำนวน <?php echo $row['quantity']; ?></p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <p id="p_shipping_<?php echo $product_child_id; ?>">ค่าส่ง <?php echo round($row['cal_parcel']); ?>/<?php echo round($row['cal_register']); ?>/<?php echo round($row['cal_EMS']); ?>/<?php echo round($row['kerry']); ?></p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <button onclick="copyToClipboard(<?php echo $product_child_id; ?>)">คัดลอกข้อความ</button>
                </div>
            </div>
            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
            <h3>ส่วนแสดงผลเฉพาะแอดมิน</h3>
            <div class="form-group">
                <label for="price" class="col-sm-2 control-label text-bold">ราคาต้นทุน</label>
                <div class="col-sm-4">
                    <label name="price" class="control-label"><?php echo $row['price']; ?></label>
                </div>
            </div>
            <div class="form-group">
                <label for="price" class="col-sm-2 control-label text-bold">ราคาขายส่ง</label>
                <div class="col-sm-4">
                    <label name="price" class="control-label"><?php echo $row['wholesale_price']; ?></label>
                </div>
            </div>
            <div class="form-group">
                <label for="sale_price" class="col-sm-2 control-label text-bold">ราคาขาย</label>
                <div class="col-sm-4">
                    <label name="sale_price" class="control-label"><?php echo $row['sale_price']; ?></label>
                </div>
            </div>
            <div class="form-group">
                <label for="quantity" class="col-sm-2 control-label text-bold">คงเหลือ</label>
                <div class="col-sm-4">
                    <label name="quantity" class="control-label"><?php echo $row['quantity']; ?></label>
                </div>
            </div>
            <div class="form-group">
                <label for="weight" class="col-sm-2 control-label text-bold">น้ำหนัก</label>
                <div class="col-sm-4">
                    <label name="weight" class="control-label"><?php echo $row['weight']; ?></label>
                </div>
            </div>
            <div class="form-group">
                <label for="size_name" class="col-sm-2 control-label text-bold">ขนาดกล่อง</label>
                <div class="col-sm-4">
                    <label name="size_name" class="control-label"><?php echo $row['size_name']; ?></label>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>