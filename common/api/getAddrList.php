<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//api_member_check();


$sel = mysqli_query($dbcon, "select distinct sigungu from flower.addr  where sido='".$_POST['juso1']."' and sigungu!='' order by sigungu") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$sigungu_list = array();
if ($sel_num > 0) {
 //$data = mysqli_fetch_assoc($sel);
 while($data = mysqli_fetch_assoc($sel)) {
   array_push($sigungu_list,$data['sigungu']);
 
 }
}



$result = array();
$result['status'] = 1;
$result['data']['sigungu_list']=$sigungu_list;


echo json_encode($result);


?>