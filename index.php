<?php

session_start();
/*
 * include file start
 */
require 'env.php';
require 'assets/library/core.php';
require 'assets/library/cons.php';
require 'assets/library/database.php';
require 'assets/library/security.php';
require 'assets/library/thaidate.php';
require 'assets/library/functions.php';

$baseUrl = base_url();
$basePath = base_path();

$onpage = isset($_GET['onpage']) ? $_GET['onpage'] : "back";
$url = isset($_GET['url']) ? $_GET['url'] : "order";
$a = isset($_GET['a']) ? $_GET['a'] : "index";

/*
 * logical programming
 */
if ($onpage == "back" AND $a != "login") {
    $security = new security();
    $security->check_login();

    //guest prevent route
    if($_SESSION[_ss . 'levelaccess'] != 'admin'){
        //product create
        if ($url == 'product' && $a == 'create') {
             header("location:" . base_url() . "/back/product");
        }
    	//product edit
    	if ($url == 'product' && $a == 'update') {
    		 header("location:" . base_url() . "/back/product");
        }
        
        if ($url == 'user' && $a == 'index') {
            header("location:" . base_url() . "/back/order");
        }
    }
}

if (file_exists("application/" . $onpage . "/" . $url . "/" . $a . ".php")) {
    require ("application/" . $onpage . "/" . $url . "/" . $a . ".php");
} else {
    header('location:' . $baseUrl);
}
