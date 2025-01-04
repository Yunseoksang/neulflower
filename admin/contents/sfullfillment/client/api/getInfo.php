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


$sel4 = mysqli_query($dbcon, "select b.*,a.client_product_idx,a.client_price,a.client_price_tax,a.client_price_sum from (
    select * from ".$db_fullfillment.".client_product where consulting_idx='".$_POST['consulting_idx']."' ) a 
    left join ".$db_fullfillment.".product b 
    on a.product_idx=b.product_idx 
    order by b.t_category1_name, b.t_category1_name 
    
    ") or die(mysqli_error($dbcon));
$sel4_num = mysqli_num_rows($sel4);

$client_product_list = array();
if ($sel4_num > 0) {
    while($data4 = mysqli_fetch_assoc($sel4)) {
        array_push($client_product_list,$data4);
    }
}




$sel5 = mysqli_query($dbcon, "select * from client_place where consulting_idx='".$_POST['consulting_idx']."' order by client_place_idx") or die(mysqli_error($dbcon));
$sel5_num = mysqli_num_rows($sel5);

$place_list = array();
if ($sel5_num > 0) {
    while($data5 = mysqli_fetch_assoc($sel5)) {
        array_push($place_list,$data5);
    }
}



$sel6 = mysqli_query($dbcon, "select a.category1_idx,a.category1_name,

b.fs_idx,b.sdate,
c.sm_idx,
d.manager_idx,d.manager_name,d.manager_email,d.manager_position,d.manager_department 

from fullfillment.category1 a 
left join (select * from fullfillment.settlement_sdate 
    where consulting_idx='".$_POST['consulting_idx']."') b 
on a.category1_idx=b.category1_idx

left join fullfillment.settlement_manager c
on a.category1_idx=c.category1_idx and c.consulting_idx='".$_POST['consulting_idx']."'


left join consulting.manager d
on c.manager_idx=d.manager_idx

order by a.category1_name,sm_idx

") or die(mysqli_error($dbcon));
$sel6_num = mysqli_num_rows($sel6);

$category1_manager_list = array();
if ($sel6_num > 0) {
    while($data6 = mysqli_fetch_assoc($sel6)) {
        array_push($category1_manager_list,$data6);
    }
}






$result = array();
$result['status'] = 1;
$result['data']['company_info']=$company_info;
$result['data']['manager_list']=$manager_list;

$result['data']['memo_list']=$memo_list;
$result['data']['attachment_list']=$attachment_list;

$result['data']['client_product_list']=$client_product_list;
$result['data']['place_list']=$place_list;
$result['data']['category1_manager_list']=$category1_manager_list;


echo json_encode($result);


?>