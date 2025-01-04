<?

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$sel = mysqli_query($dbcon, "select * from coupon_upload_pre_memo where part='return' order by cu_memo_order") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$return_memo_list = array();
if ($sel_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data = mysqli_fetch_assoc($sel)) {
      $return_memo_list[] = $data;
   }
}




$sel = mysqli_query($dbcon, "select * from coupon_upload_pre_memo where part='reject' order by cu_memo_order") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$reject_memo_list = array();
if ($sel_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data = mysqli_fetch_assoc($sel)) {
      $reject_memo_list[] = $data;
   }
}






$result = array();
$result['status'] = 1;
$result['data']['return_memo_list'] = $return_memo_list;
$result['data']['reject_memo_list'] = $reject_memo_list;
echo json_encode($result);




