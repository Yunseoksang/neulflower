<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$sel = mysqli_query($dbcon, "select * from category2 where category1_idx='".$_POST['category1_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

$category2_list = array();
if ($sel_num > 0) {
 //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
    
       array_push($category2_list,$data);
    }
}




$result = array();
$result['status'] = 1;
$result['data']['category2_list']=$category2_list;

echo json_encode($result);
exit;


?>