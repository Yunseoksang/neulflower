<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


/** 필수 요소 누락 체크 시작 */
if($_POST['storage_idx'] == ""){
    error_json(0,"storage_idx 정보가 없습니다");
    exit;
}


$arr['storage_idx'] = $_POST['storage_idx'];



$sel = mysqli_query($dbcon, "

select a.*,b.current_count,b.safe_count from product a
left join storage_safe b
on a.product_idx=b.product_idx and b.storage_idx='".$_POST['storage_idx']."'

order by b.safe_count desc,b.current_count desc,a.product_name

") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$list = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
    
        array_push($list,$data);
    
    }
}




$result = array();
$result['status'] = 1;
$result['data']['storage_safe_list']=$list;


echo json_encode($result);


?>