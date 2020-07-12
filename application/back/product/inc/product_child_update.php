<?php
$db = new database();
$option_sizes = array(
    "table" => "box_sizes",
);
$query_sizes = $db->select($option_sizes);
?>

<div id="product_child_<?php echo $product_child_id?>" class="panel panel-default">
    <input type="hidden" name="child_id[]" value="<?php echo $row['id']; ?>">
    <div data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $product_child_id?>" class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $product_child_id?>">
                สินค้าย่อย <?php echo $product_child_id?> (<?php echo $row['name']; ?>)
            </a>
            <a data-toggle="collapse" product-child-id="<?php echo $product_child_id?>" value="<?php echo $row['id']; ?>"  class="close product-child" >&times </a>
        </h4>
    </div>
    <div id="collapse_<?php echo $product_child_id?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="form-group">
                <label for="child_name" class="col-sm-4 control-label required">ชื่อสินค้า</label>
                <div class="col-sm-8">
                    <input type="text" name="child_name[]" value="<?php echo $row['name']; ?>" class="form-control input-sm" data-validation="required" >
                </div>
            </div>
            <div class="form-group">
                <label for="child_price" class="col-sm-4 control-label required">ราคา</label>
                <div class="col-sm-8">
                    <input type="text" name="child_price[]" value="<?php echo $row['price']; ?>" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_wholesale_price" class="col-sm-4 control-label required">ราคาขายส่ง</label>
                <div class="col-sm-8">
                    <input type="text" name="child_wholesale_price[]" value="<?php echo $row['wholesale_price']; ?>" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_agent_price" class="col-sm-4 control-label required">ราคา ตท.</label>
                <div class="col-sm-8">
                    <input type="text" name="child_agent_price[]" value="<?php echo $row['agent_price']; ?>" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_sale_price" class="col-sm-4 control-label required">ราคาขาย</label>
                <div class="col-sm-8">
                    <input type="text" name="child_sale_price[]" value="<?php echo $row['sale_price']; ?>" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_quantity" class="col-sm-4 control-label">จำนวน</label>
                <div class="col-sm-8">
                    <input type="text" name="child_quantity[]" value="<?php echo $row['quantity']; ?>" class="form-control input-sm" data-validation="number">
                </div>
            </div>
            <div class="form-group">
                <label for="child_weight" class="col-sm-4 control-label">น้ำหนัก</label>
                <div class="col-sm-8">
                    <input type="text" name="child_weight[]" value="<?php echo $row['weight']; ?>" class="form-control input-sm" data-validation="number">
                </div>
            </div>
            <div class="form-group">
                <label for="child_box_size" class="col-sm-4 control-label">ขนาดกล่อง</label>
                <div class="col-sm-8">
                    <select class="form-control input-sm" name="child_box_size[]">
                        <?php while ($size = $db->get($query_sizes)){
                            $selected = ($size['id']==$row['boxsize_id'])?'selected':'';
                            echo "<option value='{$size['id']}' {$selected}>{$size['size_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>