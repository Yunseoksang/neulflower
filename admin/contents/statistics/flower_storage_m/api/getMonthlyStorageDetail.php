<?php
ini_set('display_errors', '0');

$rData= $_REQUEST;

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



//날짜 검색 적용시
// if($rData['date_apply'] == "on"){ 
//     if($rData['date_part'] != ""){
//       $sql_where_date .= " AND ".$rData['date_part']." >='".$rData['start_date']."' AND ".$rData['date_part']." <= '".$rData['end_date']."' ";
//     }
// }else{
//     $ymd = date("Y-m-d",time());
//     $sql_where_date .= " AND r_date >= ".$ymd."  and r_date <= ".$ymd;
// }



if($_POST['yyyymm'] == ""){
    $_POST['yyyymm'] = date("Ym",time());
}



$yyyymm = str_replace("년 ","",$_POST['yyyymm']);
$yyyymm = str_replace("월","",$yyyymm);


$sql_aaa = "
select b.oocp_idx, a.r_date, b.product_name, b.order_count, a.sender_name, 

b.client_price,
(b.client_price*b.order_count) as price_sum,

(b.client_price_tax*b.order_count) as tax_sum,
b.total_client_price_sum


from
(select * from flower.out_order where storage_idx='".$_POST['flower_storage_idx']."' and out_order_part='화훼'  and out_order_status='배송완료' and date_format(r_date,'%Y%m')='".$yyyymm."' ) a 
left join flower.out_order_client_product b 
on a.out_order_idx=b.out_order_idx
";

$sel = mysqli_query($dbcon, $sql_aaa) or die(mysqli_error($dbcon));


// $sel = mysqli_query($dbcon, "

// select b.oocp_idx, a.r_date, b.product_name, b.order_count, a.sender_name, b.price_calcu from
// (select * from flower.out_order where  out_order_part='화훼' ) a 
// left join flower.out_order_client_product b 
// on a.out_order_idx=b.out_order_idx


// ") or die(mysqli_error($dbcon));

$sel_num = mysqli_num_rows($sel);

$rows = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
    
        $rows[] = $data;
    }
}


$sel1 = mysqli_query($dbcon, "select storage_name from flower.storage where storage_idx='".$_POST['flower_storage_idx']."'  ") or die(mysqli_error($dbcon));
$sel_num1 = mysqli_num_rows($sel1);

if ($sel_num1 > 0) {
   $data1 = mysqli_fetch_assoc($sel1);
   
}



$filtered_num = $sel_num;

$result = array();
$result['status'] = 1;
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $filtered_num;// $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;
$result['storage_info'] = $data1;

$result['sql_aaa'] = $sql_aaa;

echo json_encode($result);

?>
