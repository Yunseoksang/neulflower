<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

//$dbcon = $db->flower_connect();


$sel = mysqli_query($dbcon, "select * from category2 where category1_idx='".$_POST['category1_idx']."' and category2_name='".$_POST['category1_name']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "중복된 중분류명입니다.";
    echo json_encode($result);
    exit;
}


$in = mysqli_query($dbcon, "insert into category2
set
category1_idx='".$_POST['category1_idx']."',
category2_name='".$_POST['category2_name']."',
base_price='".$_POST['base_price']."'

") or die(mysqli_error($dbcon));
$in_id = mysqli_insert_id($dbcon);
if($in_id){//쿼리성공


   $sel = mysqli_query($dbcon, "select * from category2 where category2_idx='".$in_id."' ") or die(mysqli_error($dbcon));
   $sel_num = mysqli_num_rows($sel);
   
   if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    
   }

    $result = array();
    $result['status'] = 1; 
    $result['data']=$data;
    $result['data']['key_column_name']=$key_column_name;


    $result['msg'] = "저장되었습니다.";
    echo json_encode($result);
    exit;
}else{//쿼리실패
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "추가실패";
    echo json_encode($result);
    exit;
}



?>