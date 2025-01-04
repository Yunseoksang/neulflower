<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



//var str = "out_order_idx="+out_order_idx+"&template_idx="+template_idx+"&sms_receiver_tel="+sms_receiver_tel+"&sms_subject="+sms_subject+"&sms_content="+sms_content;

if($_POST['out_order_idx'] == "") {error_exit("필수정보누락 오류");exit;}
if($_POST['sms_receiver_tel'] == "") {error_exit("필수정보누락 오류");exit;}
//if($_POST['sms_subject'] == "") {error_exit("필수정보누락 오류");exit;}
if($_POST['sms_content'] == "") {error_exit("필수정보누락 오류");exit;}



$sender_tel = "15880000"; //발신자 번호



$sms_content   = str_replace("$$$$$","&",$_POST['sms_content']);
$receiver_tel  = str_replace("-","",$_POST['sms_receiver_tel']);






mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));



$sql_num = 0;
$in = mysqli_query($dbcon, "insert into sms 
set
out_order_idx = '".$_POST['out_order_idx']."',
template_idx  = '".$_POST['template_idx']."',   
sms_subject   = '".mysqli_real_escape_string($dbcon, $_POST['sms_subject'])."',  
sms_content   = '".mysqli_real_escape_string($dbcon, $sms_content)."',  
sender_tel    = '".$sender_tel."', 
receiver_tel  = '".$receiver_tel."',   
admin_idx     = '".$admin_info['admin_idx']."',
admin_name    = '".$admin_info['admin_name']."'

") or die(mysqli_error($dbcon));
$in_id = mysqli_insert_id($dbcon);
if($in_id){//쿼리성공

$sql_num++;
}else{//쿼리실패

}



$up = mysqli_query($dbcon, "update out_order set sms_sent='1' where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
$up_num = mysqli_affected_rows($dbcon);
if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
    $sql_num++;

}else{//쿼리실패

}


//SMS 문자보내기
//https://message.gabia.com/api/documentation/
//



//두개의 쿼리 + 보내기 모두 성공시. commit

if($sql_num == 2){ //문자보내기 진입
    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));
    success_exit("발송되었습니다.");

}else{
    error_exit("발송실패하였습니다. Error Code: SE21");
}






