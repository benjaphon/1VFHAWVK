<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    date_default_timezone_set('Asia/Bangkok');
    $db = new database();

    if($_SESSION[_ss . 'levelaccess'] == 'admin'){

        switch ($_POST['status']) {
            case 'A':
                $value_rq = array(
                    "status" => trim($_POST['status'])
                );
                break;
            case 'P':
                $value_rq = array(
                    "status" => trim($_POST['status']),
                    "result" => trim($_POST['result'])
                );
                break;
            case 'F':
                $value_rq = array(
                    "status" => trim($_POST['status'])
                );
                break;
            case 'C':
                $value_rq = array(
                    "status" => trim($_POST['status'])
                );
                break;
            default:
                $value_rq = array(
                    "problem" => trim($_POST['problem']),
                    "user_id" => $_SESSION[_ss . 'id']
                );
                break;
        }

    } else {


        switch ($_POST['status']) {
            case 'A':
                $value_rq = array(
                    "status" => trim($_POST['status'])
                );
                break;
            case 'F':
                $value_rq = array(
                    "status" => trim($_POST['status'])
                );
                break;
            case 'C':
                $value_rq = array(
                    "status" => trim($_POST['status'])
                );
                break;
            default:
                $value_rq = array(
                    "problem" => trim($_POST['problem']),
                    "user_id" => $_SESSION[_ss . 'id']
                );
                break;
        }
        

    }

    $query_rq = $db->update("request", $value_rq, "order_id='{$_POST['order_id']}'");
    if ($query_rq == TRUE) {
        header("location:" . base_url() . "/back/order");
    }
    mysql_close();
}

?>