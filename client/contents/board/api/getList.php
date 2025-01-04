<?php

ini_set('display_errors', '0');


// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER["DOCUMENT_ROOT"].'/client/contents/common/api/getList_common.php'; //DB 접속


//당일배송건
$sel = mysqli_query($dbcon, "select count(*) as cnt from flower.out_order where r_date='".$todate."' and out_order_status!='주문취소'  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $today_count = $data['cnt'];
}



//예약건
$sel = mysqli_query($dbcon, "select count(*) as cnt from flower.out_order where r_date > '".$todate."' and out_order_status!='주문취소' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $reserved_count = $data['cnt'];
}




//미배송건
$sel = mysqli_query($dbcon, "select count(*) as cnt from flower.out_order  where r_date >= '".$todate."' and  (out_order_status!='배송완료' and out_order_status!='주문취소' ) ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $ready_count = $data['cnt'];
}




//금일수주
$sel = mysqli_query($dbcon, "select count(*) as cnt from flower.out_order where date(regist_datetime)='".$todate."' and out_order_status!='주문취소'  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $today_order_count = $data['cnt'];
}



$this_month = date("Y-m",time());

//금월수주
$sel = mysqli_query($dbcon, "select count(*) as cnt from flower.out_order where date_format(regist_datetime,'%Y-%m')='".$this_month."' and out_order_status!='주문취소'  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $this_month_order_count = $data['cnt'];
}






/*

//날짜 검색 적용시
if($rData['date_apply'] == "on"){ 
    if($rData['date_part'] != ""){
      $sql_period = "  ".$rData['date_part']." >='".$rData['start_date']."' AND ".$rData['date_part']." <= '".$rData['end_date']."' ";
    }
}else{
    if($rData['date_part'] != "" && $rData['start_date'] != "" && $rData['end_date'] != ""){
        $sql_period = "  ".$rData['date_part']." >='".$rData['start_date']."' AND ".$rData['date_part']." <= '".$rData['end_date']."' ";
    }else{
        $sql_period = " r_date >='".$todate."' AND r_date <= '".$rData['end_date']."' ";
    }
}


//총수주건
$sel = mysqli_query($dbcon, "select count(*) as cnt from flower.out_order where ".$sql_period." and  (out_order_status!='배송완료' and out_order_status!='주문취소' ) ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $total_order_count = $data['cnt'];
}


//총수주금액
$sel = mysqli_query($dbcon, "select sum(total_client_price_sum) as total_order_price from flower.out_order where ".$sql_period." and  (out_order_status!='배송완료' and out_order_status!='주문취소' ) ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $total_order_price = $data['total_order_price'];
}


*/

//총수주금액
$total_sql = "select sum(X.total_client_price_sum) as total_order_price from (".$sql_join.") X";

$query_result_x=mysqli_query($dbcon, $total_sql) or die(mysqli_error($dbcon));
$data_x = mysqli_fetch_assoc($query_result_x);
$total_order_price = $data_x['total_order_price'];
 


$result['today_count'] = $today_count;
$result['reserved_count'] = $reserved_count;
$result['ready_count'] = $ready_count;

$result['today_order_count'] = $today_order_count;
$result['this_month_order_count'] = $this_month_order_count;

$result['total_order_price'] = $total_order_price;




echo json_encode($result);

//js/daw_dt.js.php 통해서 api 결과 받은 이후 통계영역 반영