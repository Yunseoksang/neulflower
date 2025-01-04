<?php
ini_set('display_errors', '0');

$rData= $_REQUEST;

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();



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

//
$sql_aaa = "
select date(output_datetime) as r_date, product_idx, t_product_name as product_name, sum(out_count) as sum_order_count
from sangjo.in_out
where  consulting_idx='".$manager_info['consulting_idx']."' and  part='출고' and io_status='출고완료' and  date_format(output_datetime,'%Y%m')='".$yyyymm."' 
group by r_date, product_idx order by r_date
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
$result['yyyymm'] = $yyyymm;



//$result['sql_aaa'] = $sql_aaa;

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
