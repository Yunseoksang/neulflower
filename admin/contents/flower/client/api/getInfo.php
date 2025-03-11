<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$sel = mysqli_query($dbcon, "select * from consulting where consulting_idx='".$_POST['consulting_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$company_info;
if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $company_info = $data;

}


$sel1 = mysqli_query($dbcon, "select *,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime from memo where consulting_idx='".$_POST['consulting_idx']."' order by memo_idx desc") or die(mysqli_error($dbcon));
$sel1_num = mysqli_num_rows($sel1);

$memo_list = array();
if ($sel1_num > 0) {
    while($data1 = mysqli_fetch_assoc($sel1)) {
        array_push($memo_list,$data1);
    }
}



$sel2 = mysqli_query($dbcon, "select * from attachment where consulting_idx='".$_POST['consulting_idx']."' order by attachment_idx") or die(mysqli_error($dbcon));
$sel2_num = mysqli_num_rows($sel2);

$attachment_list = array();
if ($sel2_num > 0) {
    while($data2 = mysqli_fetch_assoc($sel2)) {
        array_push($attachment_list,$data2);
    }
}




$sel3 = mysqli_query($dbcon, "select * from manager where consulting_idx='".$_POST['consulting_idx']."' order by manager_idx") or die(mysqli_error($dbcon));
$sel3_num = mysqli_num_rows($sel3);

$manager_list = array();
if ($sel3_num > 0) {
    while($data3 = mysqli_fetch_assoc($sel3)) {
        array_push($manager_list,$data3);
    }
}


// $sel4 = mysqli_query($dbcon, "select b.*,a.client_product_idx,a.client_price,a.client_price_tax,a.client_price_sum from (
//     select * from ".$db_flower.".client_product where consulting_idx='".$_POST['consulting_idx']."' ) a 
//     left join ".$db_flower.".product b 
//     on a.product_idx=b.product_idx 
//     order by b.t_category1_name, b.t_category1_name 
    
//     ") or die(mysqli_error($dbcon));


$sel4 = mysqli_query($dbcon, "select a.*,b.client_product_idx,COALESCE(b.client_price,a.product_price) as client_price,ifnull(b.client_price_tax,0) as client_price_tax,COALESCE(b.client_price_sum,a.product_price) as client_price_sum from 
    ".$db_flower.".product a
    left join (select * from ".$db_flower.".client_product where consulting_idx='".$_POST['consulting_idx']."' ) b
    on a.product_idx=b.product_idx 

    order by a.t_category1_name, a.t_category1_name 
    
    ") or die(mysqli_error($dbcon));


$sel4_num = mysqli_num_rows($sel4);

$client_product_list = array();
if ($sel4_num > 0) {
    while($data4 = mysqli_fetch_assoc($sel4)) {
        array_push($client_product_list,$data4);
    }
}


// 상조거래품목 정보 가져오기
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //상조 DB 접속

// 디버깅 로그 추가
error_log("상조거래품목 정보 가져오기 시작: consulting_idx = " . $_POST['consulting_idx']);

$sel_sangjo = mysqli_query($dbcon, "select a.*,b.product_name from 
    (select * from ".$db_sangjo_new.".client_product where consulting_idx='".$_POST['consulting_idx']."') a 
    left join ".$db_sangjo_new.".product b 
    on a.product_idx=b.product_idx 
    order by a.client_product_idx desc
") or die(mysqli_error($dbcon));

$sel_sangjo_num = mysqli_num_rows($sel_sangjo);
error_log("상조거래품목 조회 결과 개수: " . $sel_sangjo_num);

$sangjo_client_product_list = array();
if ($sel_sangjo_num > 0) {
    while($data_sangjo = mysqli_fetch_assoc($sel_sangjo)) {
        array_push($sangjo_client_product_list, $data_sangjo);
    }
    error_log("상조거래품목 데이터 처리 완료");
} else {
    error_log("상조거래품목 데이터가 없습니다");
}


$sel5 = mysqli_query($dbcon, "select * from flower.client_flower_sender where consulting_idx='".$_POST['consulting_idx']."' order by client_flower_sender_idx") or die(mysqli_error($dbcon));
$sel5_num = mysqli_num_rows($sel5);

$sender_list = array();
if ($sel5_num > 0) {
    while($data5 = mysqli_fetch_assoc($sel5)) {
        array_push($sender_list,$data5);
    }
}



$result = array();
$result['status'] = 1;
$result['data']['company_info']=$company_info;
$result['data']['manager_list']=$manager_list;

$result['data']['memo_list']=$memo_list;
$result['data']['attachment_list']=$attachment_list;

$result['data']['client_product_list']=$client_product_list;
$result['data']['sangjo_client_product_list']=$sangjo_client_product_list; // 상조거래품목 정보 추가
$result['data']['sender_list']=$sender_list;


echo json_encode($result);


?>