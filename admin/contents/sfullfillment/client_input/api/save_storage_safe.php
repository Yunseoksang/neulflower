<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_fullfillment.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



$post_data = file_get_contents("php://input");

$arr = json_decode($post_data , true); //배열






/* Start transaction */
mysqli_begin_transaction($dbcon);

$product_list = $arr['product_list'];
for ($i=0;$i<count($product_list);$i++ )
{

    $sel = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,safe_count) values ('".$arr['storage_idx']."','".$product_list[$i]['product_idx']."','".$product_list[$i]['cnt']."') 
    ON duplicate key update  safe_count='".$product_list[$i]['cnt']."' ") or die(mysqli_error($dbcon));

}

mysqli_commit($dbcon);



$result = array();
$result['status'] = 1;


echo json_encode($result);


?>