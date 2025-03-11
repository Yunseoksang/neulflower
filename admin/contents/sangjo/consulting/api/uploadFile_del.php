<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$sel = mysqli_query($dbcon, "select * from attachment where attachment_idx='".$_POST['attachment_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    unlink($_SERVER["DOCUMENT_ROOT"].$data['filename']);
}

$del = mysqli_query($dbcon, "delete from attachment where attachment_idx='".$_POST['attachment_idx']."' ") or die(mysqli_error($dbcon));


$result = array();
$result['status'] = 1;
$result['msg'] = "삭제되었습니다.";
echo json_encode($result);
exit;

?>