<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();

$payment_bulk_id = $_GET['id'];

$option_pm_b = array(
    "fields" => "pm_b.*, u.username",
    "table" => "payment_bulks AS pm_b INNER JOIN users AS u ON pm_b.user_id = u.id",
    "condition" => "pm_b.id={$payment_bulk_id}"
);
$query_pm_b = $db->select($option_pm_b);
$rs_pm_b = $db->get($query_pm_b);

$option_payment_img = array(
    "table" => "images",
    "condition" => "ref_id = '{$payment_bulk_id}' AND images.filetype = 'payment' "
);
$query_payment_img = $db->select($option_payment_img);

$title = 'การชำระเงินหลายรายการ';
/*
 * php code///////////**********************************************************
 */

/*
 * header***********************************************************************
 */
require 'assets/template/back/header.php';
/*
 * header***********************************************************************
 */
?>
<!-- **********************************************************************************************************************************************************
MAIN CONTENT
*********************************************************************************************************************************************************** -->
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">การชำระเงินหลายรายการ</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-6">
            <div class="row vertical-align">
                <div class="col-xs-6">
                    <div class="form-group">
                      <label for="select_payment">รหัสสั่งซื้อที่ชำระเงินแล้ว</label>
                      <select id="select_payment" size="10" class="form-control">

                        <?php

                            $option_order = array(
                                "fields" => "*, pm_b.id AS pm_b_id, pm.id AS pm_id, o.id AS o_id",
                                "table" => "payment_bulks AS pm_b INNER JOIN payments AS pm ON pm_b.id = pm.bulk_id INNER JOIN orders AS o ON pm.order_id = o.id",
                                "condition" => "pm_b.id={$payment_bulk_id}"
                            );
                            $query_order = $db->select($option_order);

                        ?>

                        <?php while ($rs_order = $db->get($query_order)) { ?>
                            <option order_id="<?php echo $rs_order['o_id']; ?>"><?php echo $rs_order['o_id']; ?></option>
                        <?php } ?>
                      </select>
                    </div>           
                </div>
            </div>
            <div class="row">
                <div id="order_detail_table" class="col-xs-12">

                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-horizontal" style="margin-top: 10px;">

                    
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">สรุปรายการ</h3>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>รวมทุกออเดอร์</strong> : <?php echo number_format($rs_pm_b['grand_order_total'], 2); ?> บาท</li>
                        <!-- <li class="list-group-item"><strong>น้ำหนักรวม</strong> : <span id="grand_order_weight"></span> กรัม</li>
                        <li class="list-group-item"><strong>ค่าส่งรวม</strong> : <span id="grand_ship_price"></span> บาท</li> -->
                    </ul>
                </div>


                <div class="form-horizontal" style="margin-top: 10px;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">รายละเอียดการชำระเงิน</h3>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>ช่องทางการชำระเงิน</strong> : <?php echo $rs_pm_b['pay_type']; ?></li>
                            <li class="list-group-item"><strong>จำนวน</strong> : <?php echo number_format($rs_pm_b['pay_money'],2); ?></li>
                            <li class="list-group-item"><strong>เพิ่มเติม</strong> : <?php echo $rs_pm_b['detail']; ?></li>
                            <li class="list-group-item"><strong>หลักฐานการโอน</strong> : 
    
                                <?php while ($rs_img = $db->get($query_payment_img)) { ?>
                                    <p>
                                        <a href="<?php echo $baseUrl ?>/assets/upload/payment/<?php echo $rs_img['filename']; ?>" class="fancybox">
                                            <img src="<?php echo $baseUrl ?>/assets/upload/payment/md_<?php echo $rs_img['filename'];?>" class="img-responsive" alt="Responsive image">
                                        </a>
                                    </p>
                                <?php } ?>
    
                            </li>
                            <li class="list-group-item"><strong>วันที่ชำระเงิน</strong> : <?php echo thaidate($rs_pm_b['created_at'], true); ?></li>
                            <li class="list-group-item"><strong>ชำระโดย</strong> : <?php echo $rs_pm_b['username']; ?></li>

                            <?php //Check if there is any order to confirm payment
                        
                                $sql = "SELECT COUNT(*) AS payment_order_count FROM payment_bulks AS pm_b ";
                                $sql .= "INNER JOIN payments AS pm ON pm_b.id = pm.bulk_id ";
                                $sql .= "INNER JOIN orders AS o ON pm.order_id = o.id ";
                                $sql .= "WHERE pm_b.id = {$payment_bulk_id} AND o.order_status = 'P'";

                                $query = $db->query($sql);
                                $row = $db->get($query);
                                $Is_there_payment_confirm = ($row['payment_order_count'] > 0);

                            ?>

                            <?php if($_SESSION[_ss . 'levelaccess'] == 'admin' && $Is_there_payment_confirm) { ?>
                            <form action="<?php echo $baseUrl; ?>/back/payment/form_confirm_payment" method="post">
                                <input type="hidden" name="payment_bulk_id" value="<?php echo $payment_bulk_id; ?>">
                                <li class="list-group-item">
                                    <button type="submit" class="btn btn-success btn-block">ยืนยันการชำระเงิน</button>
                                </li>
                            </form>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
  </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->

<!-- AJAX Loading -->
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>

<?php
/*
 * footer***********************************************************************
 */
require 'assets/template/back/footer.php';
/*
 * footer***********************************************************************
 */
?>

<link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/jquery.datetimepicker.css" type="text/css" />

<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<script type='text/javascript' src="<?php echo $baseUrl; ?>/assets/js/jquery.datetimepicker.js"></script>

<script>
    
    $('#select_payment').change(function(){
        $option_selected = $("option:selected", this);
        var value = $(this).val();

        var url = '<?php echo $baseUrl; ?>/back/payment/form_gen_order_detail';

        $.get(url, {order_id: value}, function (data) {
            $('#order_detail_table').html(data);
        });
        
    });

    $('a.fancybox').fancybox();
    $.validate();
    $(document).on({
        ajaxStart: function() { $("#overlay").fadeIn(300); },
        ajaxStop: function() { $("#overlay").fadeOut(300); }    
    });
</script>


