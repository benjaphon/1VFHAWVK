<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$title = 'Nine Thing';
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
      <div class="col-lg-9 main-chart">
        <!-- Admin Only -->
        <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
          <div class="row mt">
            <div class="col-lg-12">
                <h1 class="page-header">สินค้าขายดี</h1>
            </div>
          </div>
          <div class="row mt">
          <?php
            $sql_pd_good = "SELECT pd.name, pd.image, SUM(od.quantity) as sum_quantity FROM order_details od ";
            $sql_pd_good .= "INNER JOIN products pd ON od.product_id = pd.id ";
            $sql_pd_good .= "GROUP BY pd.name, pd.image ";
            $sql_pd_good .= "ORDER BY sum_quantity DESC ";
            $sql_pd_good .= "LIMIT 5";

            $query_pd_good = $db->query($sql_pd_good);
          ?>
          <?php $i = 0; while ($rs_pd_good = $db->get($query_pd_good)) { ?>
            <div class="col-md-2 col-sm-2 <?php echo ($i==0) ? "col-md-offset-1" : "" ?> box0">
              <div class="box1">
                <img src="<?php echo base_url(); ?>/assets/upload/product/<?php echo $rs_pd_good['image'] ?>" class="img-responsive" alt="Responsive image">
                <h3><?php echo $rs_pd_good['sum_quantity'] ?> ชิ้น</h3>
              </div>
              <p><?php echo $rs_pd_good['name'] ?></p>
            </div>
          <?php $i++; } ?>
          </div><!-- /row mt -->
          <div class="row mt">
            <div class="col-lg-12">
                <h1 class="page-header">สินค้าขายไม่ดี</h1>
            </div>
          </div>
          <div class="row mt">
          <?php
            $sql_pd_bad = "SELECT pd.name, pd.image, SUM(od.quantity) as sum_quantity FROM order_details od ";
            $sql_pd_bad .= "INNER JOIN products pd ON od.product_id = pd.id ";
            $sql_pd_bad .= "GROUP BY pd.name, pd.image ";
            $sql_pd_bad .= "ORDER BY sum_quantity ";
            $sql_pd_bad .= "LIMIT 5";

            $query_pd_bad = $db->query($sql_pd_bad);
          ?>
          <?php $i = 0; while ($rs_pd_bad = $db->get($query_pd_bad)) { ?>
            <div class="col-md-2 col-sm-2 <?php echo ($i==0) ? "col-md-offset-1" : "" ?> box0">
              <div class="box1">
                <img src="<?php echo base_url(); ?>/assets/upload/product/<?php echo $rs_pd_bad['image'] ?>" class="img-responsive" alt="Responsive image">
                <h3><?php echo $rs_pd_bad['sum_quantity'] ?> ชิ้น</h3>
              </div>
              <p><?php echo $rs_pd_bad['name'] ?></p>
            </div>
          <?php $i++; } ?>
          </div><!-- /row mt -->
        <?php } ?>
        <!-- Close Admin Only -->
          <div class="row mt">
            <div class="col-lg-12">
                <h1 class="page-header">ยอดขายเดือนปัจจุบัน</h1>
            </div>
          </div>
          <div class="row mt">
          <?php
            $m = date('n');
            if($_SESSION[_ss . 'levelaccess'] == 'admin'){
              $option_sum_total_per_month = array(
                "table" => "orders",
                "fields" => "SUM(total) as sum_total",
                "condition" => "MONTH(order_date)={$m}"
              );
            } else {
              $option_sum_total_per_month = array(
                "table" => "orders",
                "fields" => "SUM(total) as sum_total",
                "condition" => "MONTH(order_date)={$m} AND user_id={$_SESSION[_ss . 'id']}"
              );
            }
            $query_sum_total_per_month = $db->select($option_sum_total_per_month);
            $rs_sum_total_per_month = $db->get($query_sum_total_per_month);

            if($_SESSION[_ss . 'levelaccess'] == 'admin'){
              $option_sum_quantity_per_month = array(
                "table" => "order_details od INNER JOIN orders os ON os.id = od.order_id",
                "fields" => "SUM(od.quantity) as sum_quantity",
                "condition" => "MONTH(os.order_date)={$m}"
              );
            } else {
              $option_sum_quantity_per_month = array(
                "table" => "order_details od INNER JOIN orders os ON os.id = od.order_id",
                "fields" => "SUM(od.quantity) as sum_quantity",
                "condition" => "MONTH(os.order_date)={$m} AND user_id={$_SESSION[_ss . 'id']}"
              );
            }
            $query_sum_quantity_per_month = $db->select($option_sum_quantity_per_month);
            $rs_sum_quantity_per_month = $db->get($query_sum_quantity_per_month);
          ?>
            <div class="col-md-12 col-sm-12 mb">
              <!-- REVENUE PANEL -->
              <div class="grey-panel pn">
                <div class="grey-header">
                </div>
                <div class="chart mt">
                  <div class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="80%" data-line-width="1" data-line-color="#fff" data-spot-color="#fff" data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="5" data-data="[200,135,667,333,526,996,564,123,890,464,655]"></div>
                </div>
                <!-- Admin Only -->
                <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                  <div class="col-lg-6">
                    <h2><?php echo number_format($rs_sum_total_per_month['sum_total'],2); ?><br/>บาท / เดือน</h2>
                  </div>
                  <div class="col-lg-6">
                    <h2><?php echo number_format($rs_sum_quantity_per_month['sum_quantity'],0); ?><br/>ชิ้น / เดือน</h2>
                  </div>
                <?php } else { ?>
                  <div class="col-lg-12">
                    <h2><?php echo number_format($rs_sum_quantity_per_month['sum_quantity'],0); ?><br/>ชิ้น / เดือน</h2>
                  </div>
                <?php } ?>
              </div>
            </div><!-- /col-md-4 -->
          </div><!-- /row -->
        <!-- Admin Only -->
        <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
          <div class="row mt">
            <div class="col-lg-12">
                <h1 class="page-header">ตัวแทนจำหน่าย</h1>
            </div>
          </div>
          <div class="row">
          <?php
            $sql_us_sell = "SELECT @curRow := @curRow + 1 AS row_number, us.username, us.firstname, us.lastname, us.email, us.phone, us.user_type, SUM(os.total) as sum_total FROM users us ";
            $sql_us_sell .= "JOIN (SELECT @curRow := 0) r ";
            $sql_us_sell .= "INNER JOIN orders os ON os.user_id = us.id ";
            $sql_us_sell .= "GROUP BY us.id ";
            $sql_us_sell .= "ORDER BY sum_total DESC";

            $query_us_sell = $db->query($sql_us_sell);
          ?>      
            <div class="col-md-12">
                <div class="grid-view">
                  <table class="table table-striped table-custom" id="tbl_User_Sell">
                      <thead>
                      <tr>
                          <th><a class="sort-link">#</a></th>
                          <th style="text-align: right;"><a class="sort-link">Sales Summary</a></th>
                          <th><a class="sort-link">Username</a></th>
                          <th><a class="sort-link">First Name</a></th>
                          <th><a class="sort-link">Last Name</a></th>
                          <th><a class="sort-link">Email</a></th>
                          <th><a class="sort-link">Phone</a></th>
                          <th><a class="sort-link">User Type</a></th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php while ($rs_us_sell = $db->get($query_us_sell)) { ?>
                          <tr>
                              <td><?php echo $rs_us_sell['row_number']; ?></td>
                              <td style="text-align: right;"><?php echo number_format($rs_us_sell['sum_total'],2); ?></td>
                              <td><?php echo $rs_us_sell['username']; ?></td>
                              <td><?php echo $rs_us_sell['firstname']; ?></td>
                              <td><?php echo $rs_us_sell['lastname']; ?></td>
                              <td><?php echo $rs_us_sell['email']; ?></td>
                              <td><?php echo $rs_us_sell['phone']; ?></td>
                              <td><?php echo $rs_us_sell['user_type']; ?></td>
                          </tr>
                        <?php } ?>
                      </tbody>
                  </table>
                </div>
            </div><!-- /col-md-12 -->
          </div><!-- row -->
          <div class="tab-pane">
            <div class="row mt">
            <?php
              $option_sum_total = array(
                "table" => "orders",
                "fields" => "SUM(total) as sum_total"
              );
              $query_sum_total = $db->select($option_sum_total);
              $rs_sum_total = $db->get($query_sum_total);

              $option_sum_charge = array(
                "table" => "product_import",
                "fields" => "SUM(price) as sum_charge"
              );
              $query_sum_charge = $db->select($option_sum_charge);
              $rs_sum_charge = $db->get($query_sum_charge);

              $option_refund = array(
                "table" => "product_import",
                "fields" => "SUM(price) as sum_price, COUNT(id) as count_refund",
                "condition" => "refund=1"
              );
              $query_refund = $db->select($option_refund);
              $rs_refund = $db->get($query_refund);
            ?>
              <div class="col-lg-6">
                <div class="content-panel">
                  <h4><i class="fa fa-angle-right"></i> รายรับ - รายจ่าย</h4>
                    <div class="panel-body text-center">
                        <canvas id="ctx_pie" height="400" width="400"></canvas>
                    </div>
                </div>
              </div>
              <div class="col-lg-6">
                  <div class="content-panel">
                    <h4><i class="fa fa-angle-right"></i> คืนเงิน</h4>
                    <div class="panel-body text-center">
                        <h2><?php echo $rs_refund['sum_price']; ?> บาท / <?php echo $rs_refund['count_refund']; ?> ครั้ง</h2>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        <?php } ?>
        <!-- Close Admin Only -->
      </div>
      <div class="col-lg-3 ds">
        <!--<h3>สินค้าใกล้หมด</h3>
        <?php

          $option_head_pd = array(
            "table" => "products",
            "condition" => "quantity<=20",
            "limit" => 5,
            "order" => "quantity"
          );

          $query_head_pd = $db_head->select($option_head_pd);
          $rows_head = $db_head->rows($query_head_pd);

        ?>
         <?php while ($rs_head_pd = $db_head->get($query_head_pd)) { ?>
        <div class="desc">

          <div class="thumb">
            <a href="<?php echo $baseUrl; ?>/back/product/update/<?php echo $rs_head_pd['id']; ?>"><img alt="avatar" src="<?php echo $baseUrl; ?>/assets/upload/product/sm_<?php echo $rs_head_pd['image']; ?>"></a>
          </div>
          <div class="details col-md-offset-2">
            <p>คงเหลือ <?php echo $rs_head_pd['quantity']; ?> ชิ้น<br/>
               <a href="<?php echo $baseUrl; ?>/back/product/update/<?php echo $rs_head_pd['id']; ?>"><?php echo $rs_head_pd['name']; ?></a>
            </p>
          </div>
        </div>
        <?php } ?>-->
        <!-- CALENDAR-->
        <div id="calendar" class="mb affix" ><!--data-spy="affix" data-offset-top="200"-->
            <div class="panel darkblue-panel no-margin">
                <div class="panel-body">
                    <div id="date-popover" class="popover top" style="cursor: pointer; disadding: block; margin-left: 33%; margin-top: -50px; width: 175px;">
                        <div class="arrow"></div>
                        <h3 class="popover-title" style="disadding: none;"></h3>
                        <div id="date-popover-content" class="popover-content"></div>
                    </div>
                    <div id="my-calendar"></div>
                </div>
            </div>
        </div><!-- / calendar -->
      </div><!-- /col-lg-3 --> 
    </section>
</section>
<!--main content end-->

<?php
/*
 * footer***********************************************************************
 */
require 'assets/template/back/footer.php';
/*
 * footer***********************************************************************
 */

 ?>

<!--script for this page-->
<script src="<?php echo $baseUrl; ?>/assets/js/chart-master/Chart.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#tbl_User_Sell").DataTable();
        
    });
</script>

<script>
var ctx_pie = document.getElementById("ctx_pie");
new Chart(ctx_pie, {
    type: 'pie',
    data: {
        labels: [
            "รายรับ",
            "รายจ่าย"
        ],
        datasets: [
            {
                data: [<?php echo $rs_sum_total['sum_total'] .','. $rs_sum_charge['sum_charge']; ?>],
                backgroundColor: [
                    "#36A2EB",
                    "#FF6384"
                ]
            }]
    }
});
</script>