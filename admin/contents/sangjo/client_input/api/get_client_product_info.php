<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


/** 필수 요소 누락 체크 시작 */
if($_POST['consulting_idx'] == ""){
    error_json(0,"consulting_idx 정보가 없습니다");
    exit;
}

// mode 파라미터 확인 (선택적)
$mode = isset($_POST['mode']) ? $_POST['mode'] : '';


$sel = mysqli_query($dbcon,"select a.*,b.product_name from
(select * from ".$db_sangjo_new.".client_product where consulting_idx='".$_POST['consulting_idx']."' and display='on') a
left join ".$db_sangjo_new.".product b 
on a.product_idx=b.product_idx 
where b.display='on'
order by product_name

") or die(mysqli_error($dbcon));


$sel_num = mysqli_num_rows($sel);


$product_list = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_list,$data);
    }
}



$place_list = array();
$sel1 = mysqli_query($dbcon, "select * from ".$db_consulting.".client_place where consulting_idx='".$_POST['consulting_idx']."' order by client_place_idx ") or die(mysqli_error($dbcon));
$sel_num1 = mysqli_num_rows($sel1);

if ($sel_num1 > 0) {
    //$data1 = mysqli_fetch_assoc($sel1);
    while($data1 = mysqli_fetch_assoc($sel1)) {
        array_push($place_list,$data1);
    }
}



$result = array();
$result['status'] = 1;
$result['data']['product_list']=$product_list;
$result['data']['place_list']=$place_list;
// mode 파라미터 응답에 추가
$result['mode'] = $mode;


echo json_encode($result);


?>