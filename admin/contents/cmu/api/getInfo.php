<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$sel = mysqli_query($dbcon, "select * from cmu where cmu_idx='".$_POST['cmu_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$cmu_info;
if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $cmu_info = $data;

}


$sel1 = mysqli_query($dbcon, "select *,IFNULL(date_format(mdate,'%Y%m%d'),'') as md,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime from memo where cmu_idx='".$_POST['cmu_idx']."' order by mdate desc, memo_idx desc") or die(mysqli_error($dbcon));
$sel1_num = mysqli_num_rows($sel1);

$memo_list = array();
if ($sel1_num > 0) {
    while($data1 = mysqli_fetch_assoc($sel1)) {
        array_push($memo_list,$data1);
    }
}


/*
$sel1 = mysqli_query($dbcon, "select *,IFNULL(date_format(mdate,'%Y%m%d'),'') as md,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime from memo_product where cmu_idx='".$_POST['cmu_idx']."' order by mdate desc,memo_idx desc") or die(mysqli_error($dbcon));
$sel1_num = mysqli_num_rows($sel1);

$memo_product_list = array();
if ($sel1_num > 0) {
    while($data1 = mysqli_fetch_assoc($sel1)) {
        array_push($memo_product_list,$data1);
    }
}
*/



$sel2 = mysqli_query($dbcon, "select * from attachment where cmu_idx='".$_POST['cmu_idx']."' order by attachment_idx") or die(mysqli_error($dbcon));
$sel2_num = mysqli_num_rows($sel2);

$attachment_list = array();
if ($sel2_num > 0) {
    while($data2 = mysqli_fetch_assoc($sel2)) {
        array_push($attachment_list,$data2);
    }
}







$result = array();
$result['status'] = 1;
$result['data']['cmu_info']=$cmu_info;

$result['data']['memo_list']=$memo_list;
//$result['data']['memo_product_list']=$memo_product_list;

$result['data']['attachment_list']=$attachment_list;


echo json_encode($result,JSON_UNESCAPED_UNICODE);


?>