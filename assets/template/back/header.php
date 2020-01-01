<?php
/*
 * php code///////////**********************************************************
 */
$db_head = new database();

/************************** สินค้าใกล้หมด ******************/

$option_head_pd = array(
  "table" => "products",
  "condition" => "quantity<=20 AND quantity>0 AND flag_status=1",
  "limit" => 5,
  "order" => "quantity"
);

$query_head_pd = $db_head->select($option_head_pd);
$rows_head = $db_head->rows($query_head_pd);

/************************** สินค้ามาใหม่ ***************************/

$option_head_pd_2 = array(
  "table" => "products",
  "condition" => "flag_status=1",
  "limit" => 5,
  "order" => "created_at DESC"
);

$query_head_pd_2 = $db_head->select($option_head_pd_2);
$rows_head_2 = $db_head->rows($query_head_pd_2);

/*
 * php code///////////**********************************************************
 */
?>

<!DOCTYPE html>
<html lang="en">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="language" content="en" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/assets/css/bootstrap.min.css">
    <!--external css-->
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/assets/font-awesome/css/font-awesome.css"  />
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/assets/lineicons/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/assets/css/jquery.fancybox.min.css" />

    <!-- Custom styles for this template -->
    <link href="<?php echo $baseUrl; ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?php echo $baseUrl; ?>/assets/css/style-custom.css?v=1.6" rel="stylesheet">
    <link href="<?php echo $baseUrl; ?>/assets/css/style-responsive.css" rel="stylesheet">

    <!--<script src="<?php echo $baseUrl; ?>/assets/js/chart-master/Chart.js"></script>-->
    <!-- DATA TABLES -->
    <link href="<?php echo $baseUrl; ?>/assets/DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
    <!-- selectbox -->
    <link href="<?php echo $baseUrl; ?>/assets/css/bootstrap-select.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            <!--logo start-->
            <a href="<?php echo $baseUrl; ?>/back/order" class="logo"><b>ระบบจัดการร้านค้า</b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- inbox dropdown start-->
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                            <i class="fa fa-exclamation-triangle"></i>
                            <span class="badge bg-theme"><?php echo $rows_head; ?></span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">สินค้าใกล้หมด <?php echo $rows_head; ?> รายการ</p>
                            </li>
                            <?php while ($rs_head_pd = $db_head->get($query_head_pd)) {
                            
                            $option_head_img = array(
                                "table" => "images",
                                "condition" => "ref_id='{$rs_head_pd['id']}' AND filetype='product'",
                                "order" => "id",
                                "limit" => "1"
                            );
                            $query_head_img = $db_head->select($option_head_img);
                            
                            if($db_head->rows($query_head_img) > 0){
                                $rs_head_img = $db_head->get($query_head_img);
                                $filename_head_img = $rs_head_img['filename'];
                            }
                            else {
                                $filename_head_img = 'ecimage.jpg';
                            }
                                
                            ?>
                            <li>
                                <div>
                                    <a href="<?php echo $baseUrl; ?>/back/product/view/<?php echo $rs_head_pd['id']; ?>">
                                        <span class="photo"><img alt="avatar" src="<?php echo $baseUrl; ?>/assets/upload/product/thumb_<?php echo $filename_head_img; ?>"></span>
                                        <span class="subject">
                                        <span><?php echo $rs_head_pd['name']; ?></span>
                                        </span>
                                        <span class="message">
                                            คงเหลือ <?php echo $rs_head_pd['quantity']; ?> ชิ้น
                                        </span>
                                    </a>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                            <i class="fa fa-bell"></i>
                            <span class="badge bg-theme"><?php echo $rows_head_2; ?></span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">สินค้ามาใหม่ <?php echo $rows_head_2; ?> รายการ</p>
                            </li>
                            <?php while ($rs_head_pd_2 = $db_head->get($query_head_pd_2)) { 
                                
                            $option_head_img = array(
                                "table" => "images",
                                "condition" => "ref_id='{$rs_head_pd_2['id']}' AND filetype='product'",
                                "order" => "id",
                                "limit" => "1"
                            );
                            $query_head_img = $db_head->select($option_head_img);
                            
                            if($db_head->rows($query_head_img) > 0){
                                $rs_head_img = $db_head->get($query_head_img);
                                $filename_head_img = $rs_head_img['filename'];
                            }
                            else {
                                $filename_head_img = 'ecimage.jpg';
                            }

                            ?>
                            <li>
                                <div>
                                    <a href="<?php echo $baseUrl; ?>/back/product/view/<?php echo $rs_head_pd_2['id']; ?>">
                                        <span class="photo"><img alt="avatar" src="<?php echo $baseUrl; ?>/assets/upload/product/thumb_<?php echo $filename_head_img; ?>"></span>
                                        <span class="subject">
                                        <span><?php echo $rs_head_pd_2['name']; ?></span>
                                        </span>
                                    </a>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <!-- inbox dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="<?php echo $baseUrl; ?>/back/user/logout">Logout</a></li>
            	</ul>
            </div>
      </header>
      <!--header end-->

      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">

              	  <p class="centered"><a href="<?php echo $baseUrl; ?>/back/user/update/<?php echo $_SESSION[_ss . 'id'] ?>"><img src="<?php echo $baseUrl; ?>/assets/img/ui-sam.jpg" class="img-circle" width="60"></a></p>
              	  <h5 class="centered"><?php echo $_SESSION[_ss . 'username'] ?></h5>

                  <!--<li class="mt">
                      <a href="<?php echo $baseUrl; ?>/back/home">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>-->
                  <li class="menu">
                      <a href="<?php echo $baseUrl; ?>/back/order">
                          <i class="fa fa-align-justify"></i>
                          <span>การสั่งซื้อ</span>
                      </a>
                  </li>
                  <li>
                      <a href="<?php echo $baseUrl; ?>/back/product">
                          <i class="fa fa-shopping-cart"></i>
                          <span>สินค้า</span>
                      </a>
                  </li>
                  
                  <!--<li>
                      <a href="<?php echo $baseUrl; ?>/back/categorie">
                          <i class="glyphicon glyphicon-list-alt"></i>
                          <span>หมวดหมู่สินค้า</span>
                      </a>
                  </li>-->            
                  <?php if($_SESSION[_ss . 'levelaccess'] == 'admin'){ ?>
                  <!--<li>
                      <a href="<?php echo $baseUrl; ?>/back/import">
                          <i class="fa fa-arrow-right"></i>
                          <span>สินค้านำเข้า</span>
                      </a>
                  </li>

                  <li>
                      <a href="<?php echo $baseUrl; ?>/back/export">
                          <i class="fa fa-arrow-left"></i>
                          <span>สินค้าส่งออก</span>
                      </a>
                  </li>-->

                  <li>
                    <a href="<?php echo $baseUrl; ?>/back/payment/create">
                        <i class="fa fa-credit-card"></i>
                        <span>แจ้งชำระเงินหลายรายการ</span>
                    </a>
                  </li>

                  <li>
                      <a href="<?php echo $baseUrl; ?>/back/user">
                          <i class="fa fa-user"></i>
                          <span>ผู้ใช้</span>
                      </a>
                  </li>
                  <li>
                      <span>Version 5.0</span>
                  </li>
                  <?php } ?>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->