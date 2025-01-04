<?

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$query1 = "select getTodayInPrice(".$_POST['menu_master_idx'].",'".$_POST['expire_date']."') as price";


$sel = mysqli_query($dbcon, $query1) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
   $data = mysqli_fetch_assoc($sel);
}





$result = array();
$result['status'] = 1;


$result['data']['today_buy_in_price']=$data['price'];
echo json_encode($result);





