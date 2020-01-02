<div id="product_child_<?php echo $product_child_id?>" style="display:none" class="panel panel-default">
    <div data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $product_child_id?>" class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $product_child_id?>">
                สินค้าย่อย <?php echo $product_child_id?>
            </a>
            <a data-toggle="collapse" product-child-id="<?php echo $product_child_id?>"  class="close product-child" >&times </a>
        </h4>
    </div>
    <div id="collapse_<?php echo $product_child_id?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="form-group">
                <label for="child_name" class="col-sm-4 control-label required">ชื่อสินค้า</label>
                <div class="col-sm-8">
                    <input type="text" name="child_name[]" class="form-control input-sm" data-validation="required" >
                </div>
            </div>
            <div class="form-group">
                <label for="child_price" class="col-sm-4 control-label required">ราคา</label>
                <div class="col-sm-8">
                    <input type="text" name="child_price[]" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_wholesale_price" class="col-sm-4 control-label required">ราคาขายส่ง</label>
                <div class="col-sm-8">
                    <input type="text" name="child_wholesale_price[]" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_agent_price" class="col-sm-4 control-label required">ราคา ตท.</label>
                <div class="col-sm-8">
                    <input type="text" name="child_agent_price[]" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_sale_price" class="col-sm-4 control-label required">ราคาขาย</label>
                <div class="col-sm-8">
                    <input type="text" name="child_sale_price[]" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_kerry" class="col-sm-4 control-label">ค่าส่ง (KERRY)</label>
                <div class="col-sm-8">
                    <input type="text" name="child_kerry[]" value="0" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                </div>
            </div>
            <div class="form-group">
                <label for="child_quantity" class="col-sm-4 control-label">จำนวน</label>
                <div class="col-sm-8">
                    <input type="text" name="child_quantity[]" value="0" class="form-control input-sm" data-validation="number">
                </div>
            </div>
            <div class="form-group">
                <label for="child_weight" class="col-sm-4 control-label">น้ำหนัก</label>
                <div class="col-sm-8">
                    <input type="text" name="child_weight[]" value="0" class="form-control input-sm" data-validation="number">
                </div>
            </div>
        </div>
    </div>
</div>