<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



$sel = mysqli_query($dbcon, "select * from out_order where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    
}else{
    error_exit("일치하는 주문정보가 없습니다.");
    exit;
}



$sel1 = mysqli_query($dbcon, "select  `template_idx`, `template_title`, `template_subject`, `template_content`, `default_selected` from sms_template where display=1 ") or die(mysqli_error($dbcon));
$sel_num1 = mysqli_num_rows($sel1);

$template_list = array();
if ($sel_num1 > 0) {
    while($data1 = mysqli_fetch_assoc($sel1)) {
       array_push($template_list,$data1);
    }
}


$result = array();
$result['status'] = 1;

$result['data']['order_info'] = $data;
$result['data']['template_list'] = $template_list;


echo json_encode($result);
exit;