<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


/** 필수 요소 누락 체크 시작 */
if($_POST['storage_idx'] == ""){
    error_json(0,"storage_idx 정보가 없습니다");
    exit;
}


$arr['storage_idx'] = $_POST['storage_idx'];

require('./common.php');



if($_POST['mode'] == "plus"){
    $sel = mysqli_query($dbcon, $base_input_product_query) or die(mysqli_error($dbcon));

}else{
    $sel = mysqli_query($dbcon, $base_output_product_query) or die(mysqli_error($dbcon));

}

$sel_num = mysqli_num_rows($sel);


$product_list = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_list,$data);
    
    }
}




$result = array();
$result['status'] = 1;
$result['data']['product_list']=$product_list;


echo json_encode($result);


?>