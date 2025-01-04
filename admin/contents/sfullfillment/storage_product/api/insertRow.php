<?

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_fullfillment.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



/** 필수 요소 누락 체크 시작 */


if($_POST['category1_idx'] == ""){
    error_json(0,"대분류를 선택해주세요");
    exit;
}

if($_POST['category2_idx'] == ""){
    error_json(0,"중분류를 선택해주세요");
    exit;
}

if($_POST['product_name'] == ""){
    error_json(0,"제품명은 필수입니다.");
    exit;
}



if($_POST['product_price'] == ""){
    error_json(0,"납품가격을 입력해주세요");
    exit;
}




/** 필수 요소 누락 체크 끝 */


/** 키데이터 중복여부 체크 */
$sel = mysqli_query($dbcon, "select * from product where category1_idx='".$_POST['category1_idx']."' and category2_idx='".$_POST['category2_idx']."' and product_name='".$_POST['product_name']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    error_json(0,"같은 분류에 이미 등록되어있는 제품명 입니다.");
    exit;
}
/** 키데이터 중복여부 체크 시작 끝 */



$in = mysqli_query($dbcon, "insert into product
set
category1_idx='".$_POST['category1_idx']."',
category2_idx='".$_POST['category2_idx']."',
base_storage_idx='".$_POST['base_storage_idx']."',
product_name='".$_POST['product_name']."',
product_price='".$_POST['product_price']."',
display_order='".$_POST['display_order']."',
display_group='".$_POST['display_group']."',

unit='".$_POST['unit']."',
cost='".$_POST['cost']."',
specifications='".$_POST['specifications']."'

") or die(mysqli_error($dbcon));
$product_idx = mysqli_insert_id($dbcon);
if($product_idx){//쿼리성공



   $sel = mysqli_query($dbcon, "select * from product where product_idx='".$product_idx."' ") or die(mysqli_error($dbcon));
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