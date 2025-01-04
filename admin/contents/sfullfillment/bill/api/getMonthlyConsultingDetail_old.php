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

$start_date = str_replace("년 ","-",$_POST['start_date']);
$start_date = str_replace("월 ","-",$start_date);
$start_date = str_replace("일"," 00:00:00",$start_date);

$end_date = str_replace("년 ","-",$_POST['end_date']);
$end_date = str_replace("월 ","-",$end_date);
$end_date = str_replace("일"," 23:59:59",$end_date);





//category1_idx 칼럼 추가해야함
$sel_bill_check = mysqli_query($dbcon, "select * from consulting.client_bill where consulting_idx='".$_POST['consulting_idx']."' and category1_idx='".$_POST['category1_idx']."'  and bill_month='".$yyyymm."' and bill_status != '폐기' ") or die(mysqli_error($dbcon));


$sel_bill_check_num = mysqli_num_rows($sel_bill_check);

if ($sel_bill_check_num > 0) {
   $data_bill_check = mysqli_fetch_assoc($sel_bill_check);

   $bill_idx = $data_bill_check['bill_idx'];
   
   $sql_aaa = "
   select a.io_idx, a.bill_idx, a.bill_yyyymm,a.bill_yyyymm_except,  b.oocp_idx, a.out_date,a.order_date,a.io_status, b.product_name, b.order_count,
   
   
   b.client_price,
   b.total_client_price,
   
   b.total_client_price_tax,
   b.total_client_price_sum,
   c.to_name
    
   
   from
   (select *,date(regist_datetime) as order_date from fullfillment.in_out where bill_idx='".$bill_idx."'
   
   or

   (consulting_idx='".$_POST['consulting_idx']."' and category1_idx='".$_POST['category1_idx']."' and part='출고' and io_status='출고완료'  and bill_idx is null and
    
   ((output_datetime >= '".$start_date."' and output_datetime <= '".$end_date."' )
   or (bill_yyyymm_except='".$yyyymm."'))) 
   
   ) a

   left join fullfillment.out_order_client_product b 
   on a.oocp_idx=b.oocp_idx 
   
   
   left join fullfillment.out_order c 
   on a.out_order_idx=c.out_order_idx
   ";



   
   
   $sel = mysqli_query($dbcon, $sql_aaa) or die(mysqli_error($dbcon));
   $sel_num = mysqli_num_rows($sel);
   
   $rows = array();
   if ($sel_num > 0) {
       //$data = mysqli_fetch_assoc($sel);
       while($data = mysqli_fetch_assoc($sel)) {
       
           $rows[] = $data;
       }
   }
}else{


    $sql_aaa = "
    select a.io_idx, a.bill_idx, a.bill_yyyymm,a.bill_yyyymm_except,  b.oocp_idx, a.out_date,a.order_date,a.io_status,
    
    b.product_name, b.order_count,
    
    
    b.client_price,
    b.total_client_price,
    
    b.total_client_price_tax,
    b.total_client_price_sum,
    c.to_name
    
    from
    (select *,date(regist_datetime) as order_date from fullfillment.in_out where consulting_idx='".$_POST['consulting_idx']."' and category1_idx='".$_POST['category1_idx']."' and part='출고' and io_status='출고완료' and 
    
    ((output_datetime >= '".$start_date."' and output_datetime <= '".$end_date."' )
    or (bill_yyyymm_except='".$yyyymm."'))
    
    ) a
    left join fullfillment.out_order_client_product b 
    on a.oocp_idx=b.oocp_idx 

    left join fullfillment.out_order c 
    on a.out_order_idx=c.out_order_idx
    
    ";
    
    $sel = mysqli_query($dbcon, $sql_aaa) or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    $rows = array();
    if ($sel_num > 0) {
        //$data = mysqli_fetch_assoc($sel);
        while($data = mysqli_fetch_assoc($sel)) {
        
            $rows[] = $data;
        }
    }
    
}



$sel_con = mysqli_query($dbcon, "select * from consulting.consulting where consulting_idx='".$_POST['consulting_idx']."'  ") or die(mysqli_error($dbcon));
$sel_con_num = mysqli_num_rows($sel_con);

if ($sel_con_num > 0) {
    $data_con = mysqli_fetch_assoc($sel_con);
   
}

/*
$sel1 = mysqli_query($dbcon, "select company_name from consulting.consulting where consulting_idx='".$_POST['consulting_idx']."'  ") or die(mysqli_error($dbcon));
$sel_num1 = mysqli_num_rows($sel1);

if ($sel_num1 > 0) {
   $data1 = mysqli_fetch_assoc($sel1);
   
}
*/


$filtered_num = $sel_num;

$result = array();
$result['status'] = 1;
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $filtered_num;// $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;
$result['consulting_info'] = $data_con;
$result['bill_check_num'] = $sel_bill_check_num; //거래명세서 해당월에 발행내역이 있으면 1, 없으면 0
//$result['sql'] = $sql_aaa; //거래명세서 해당월에 발행내역이 있으면 1, 없으면 0

//$result['consulting_info'] = $data1;



//$result['sql_aaa'] = $sql_aaa;

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
