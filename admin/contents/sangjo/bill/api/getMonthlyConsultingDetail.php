<?php
ini_set('display_errors', '0');

$rData= $_REQUEST;

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
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






$sql_aaa = "
select 

a.oocp_idx, 
a.order_date, 
a.bill_idx, 
a.bill_yyyymm, 


a.product_name, 
a.order_count,
a.client_price,
a.total_client_price,
a.total_client_price_tax,
a.total_client_price_sum,
a.bigo,

b.io_idx,  
b.io_status,
b.out_date,

c.to_name,
d.bill_status,
d.unique_number


from
(
    select * from sangjo_new.out_order_client_product 
    where consulting_idx='".$_POST['consulting_idx']."' and category1_idx='".$_POST['category1_idx']."' 
    and ((order_date >= '".$start_date."' and order_date <= '".$end_date."'  ) or (bill_yyyymm = '".$yyyymm."')  )

) a  


left join sangjo_new.in_out b 
on a.out_order_idx=b.out_order_idx 

left join sangjo_new.out_order c 
on a.out_order_idx=c.out_order_idx

left join consulting.client_bill d 
on a.bill_idx=d.bill_idx and d.bill_part='상조물류' and d.bill_status != '폐기'

where (b.io_status <> '출고취소')

ORDER BY 
    a.bill_idx IS NULL, 
    a.bill_idx;         


";


/* 
ORDER BY 
    a.bill_idx IS NULL, -- a.bill_idx가 NULL이면 뒤쪽에 배치
    a.bill_idx;         -- a.bill_idx가 있을 때 오름차순 정렬

거래명에서가 폐기되는 경우 거래내역 자체는 폐기가 안되므로 
d.bill_status != '폐기' 
이 조건은 where 절이 아니고 join on 조건에 넣어서 
거래내역은 유지하고 조회되게하되 매칭되는 거래명세서 내역 idx 를 관리해줌.

*/

$sel = mysqli_query($dbcon, $sql_aaa) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

$rows = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
    
        $rows[] = $data;
    }
}











$sel_con = mysqli_query($dbcon, "select * from consulting.consulting where consulting_idx='".$_POST['consulting_idx']."'  ") or die(mysqli_error($dbcon));
$sel_con_num = mysqli_num_rows($sel_con);

if ($sel_con_num > 0) {
    $data_con = mysqli_fetch_assoc($sel_con);
   
}




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



$result['sql_aaa'] = $sql_aaa;

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
