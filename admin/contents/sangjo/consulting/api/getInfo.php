<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
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





$result = array();
$result['status'] = 1;
$result['data']['company_info']=$company_info;
$result['data']['manager_list']=$manager_list;

$result['data']['memo_list']=$memo_list;
$result['data']['attachment_list']=$attachment_list;


echo json_encode($result);


?>