<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


//qrcode: 2211280100102

/** 필수 요소 누락 체크 시작 */
if(strlen($_REQUEST['qrcode']) != 13){
    error_json(0,"유효한 상품 정보가 없습니다");
    exit;
}


$part_code = substr($_REQUEST['qrcode'],6,2); // 01=sangjo, 02=sangjo  , 03=flower

if($part_code != "01"){
    error_json(0,"유효한 바코드 정보가 아닙니다");
    exit;
}



$product_idx = (int) substr($_REQUEST['qrcode'],8,5); // 01=sangjo, 02=sangjo  , 03=flower

if($product_idx < 1){
    error_json(0,"유효한 상품코드 정보가 아닙니다");
    exit;
}




$sel = mysqli_query($dbcon, "select product_idx,product_name from product where product_idx='".$product_idx."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

$product_info = array();
if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
}




$result = array();
$result['status'] = 1;
$result['product_idx'] = $product_idx;

$result['data']['product_info']=$data;


echo json_encode($result, JSON_UNESCAPED_UNICODE);


?>