<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();




$sel = mysqli_query($dbcon, "select brand_idx,brand_name from brand order by brand_display_order,brand_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$brandList = array();  //coupon_upload_group 쿠폰 목록 및 각 쿠폰의 정보
if ($sel_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data = mysqli_fetch_assoc($sel)) {

        if($_POST['mode'] == ""){ //mode=brand 를 넣으면 brand 목록만 리턴해줌.
            $sel1 = mysqli_query($dbcon, "select menu_master_idx,menu_name from menu where brand_idx=".$data['brand_idx']." order by menu_display_order,menu_name ") or die(mysqli_error($dbcon));
            $sel1_num = mysqli_num_rows($sel1);
            $menuList = array();  //coupon_upload_group 쿠폰 목록 및 각 쿠폰의 정보

            if ($sel1_num > 0) {
                //$data = mysqli_fetch_assoc($sel);
                while($data1 = mysqli_fetch_assoc($sel1)) {
                    $menuList[] = $data1;
                }
            }
            $data['menuList'] = $menuList;
        }

       $data['brand_name'] = iconv_substr($data['brand_name'], 0, 14, 'utf-8'); //앞에서 14자만 표기
       $brandList[] = $data;
   }
}



$result = array();
$result['status'] = 1;
$result['data']['brandList']=$brandList;

echo json_encode($result);



?>


