<?php

// 거래명세서 보기

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

/*
$sql_aaa_old = "
select b.oocp_idx, a.out_date, b.client_product_idx, b.product_name, sum(b.order_count) as sum_order_count,  
b.client_price as price ,
sum(b.total_client_price) as price_sum,
sum(b.total_client_price_tax) as price_tax

from
(select * from sangjo_new.in_out where  consulting_idx='".$_POST['consulting_idx']."' and  part='출고' and io_status='출고완료' and  date_format(output_datetime,'%Y%m')='".$yyyymm."' ) a
left join sangjo_new.out_order_client_product b 
on a.out_order_idx=b.out_order_idx 

left join consulting.consulting c 
on a.consulting_idx=c.consulting_idx


group by a.out_date,b.client_product_idx order by a.out_date
";

*/


/*
$sel_con1 = mysqli_query($dbcon, "

select a.*,b.category1_name from (

select bill_idx,date(regist_datetime) as rdate,bill_month,unique_number,category1_idx from consulting.client_bill 
where consulting_idx='".$_POST['consulting_idx']."' and bill_month='".$yyyymm."' 
and bill_part='종합물류' and category1_idx='".$_POST['category1_idx']."' and bill_status != '폐기'   ) a 

left join sangjo_new.category1 b 
on a.category1_idx=b.category1_idx

") or die(mysqli_error($dbcon));
*/


$sel = mysqli_query($dbcon, "

select a.*,b.category1_name from (

select bill_idx,date(regist_datetime) as rdate,bill_month,unique_number,category1_idx,consulting_idx from consulting.client_bill 
where bill_idx='".$_POST['bill_idx']."' ) a 

left join sangjo_new.category1 b 
on a.category1_idx=b.category1_idx

") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $bill_info = mysqli_fetch_assoc($sel);
    $bill_idx = $bill_info['bill_idx'];

}else{
    error_json(0,"해당 거래명세서가 존재하지 않습니다.");
    exit;
}





$sql_aaa = "
select  a.oocp_idx, a.order_date, a.client_product_idx, a.product_name, a.order_count as sum_order_count,  
a.client_price as price ,
a.total_client_price as price_sum,
a.total_client_price_tax as price_tax,
a.bigo,
b.io_status,
c.to_name

from
(select * from sangjo_new.out_order_client_product where bill_idx='".$bill_idx."') a


left join sangjo_new.in_out b 
on a.oocp_idx=b.oocp_idx 


left join sangjo_new.out_order c 
on a.out_order_idx=c.out_order_idx


order by a.order_date
";
//group by a.order_date,a.client_product_idx order by a.order_date


$sel_aaa = mysqli_query($dbcon, $sql_aaa) or die(mysqli_error($dbcon));
$sel_aaa_num = mysqli_num_rows($sel_aaa);

$rows = array();
if ($sel_aaa_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel_aaa)) {
    
        $rows[] = $data;
    }
}


$sel_con = mysqli_query($dbcon, "select * from consulting.consulting where consulting_idx='".$bill_info['consulting_idx']."'  ") or die(mysqli_error($dbcon));
$sel_con_num = mysqli_num_rows($sel_con);

if ($sel_con_num > 0) {
   $data_con = mysqli_fetch_assoc($sel_con);
   
}



$sel_con2 = mysqli_query($dbcon, "

select category1_name from sangjo_new.category1 where category1_idx='".$bill_info['category1_idx']."'


") or die(mysqli_error($dbcon));
$sel_con2_num = mysqli_num_rows($sel_con2);

if ($sel_con2_num > 0) {
   $category2_info = mysqli_fetch_assoc($sel_con2);
   $category1_name = $category2_info['category1_name'];
}






$filtered_num = $sel_num;

$result = array();
$result['status'] = 1;
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $filtered_num;// $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;
$result['bill_info'] = $bill_info;

$result['consulting_info'] = $data_con;

$result['yyyymm'] = $yyyymm;
$result['category1_name'] = $category1_name;



//$result['sql_aaa'] = $sql_aaa;

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
