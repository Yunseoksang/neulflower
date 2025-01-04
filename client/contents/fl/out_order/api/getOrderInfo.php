<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_client.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();





$sel = mysqli_query($dbcon, "select a.*,b.manager,b.hp,b.address from flower.out_order a

left join flower.storage b
on a.storage_idx=b.storage_idx

where a.out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

$order_info = array();
if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $order_info = $data;

}




$order_product_list = array();
if($_POST['out_order_part'] == "화훼"){
   $sel1 = mysqli_query($dbcon, "select * from flower.out_order_client_product where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
   $sel_num1 = mysqli_num_rows($sel1);
   
   if ($sel_num1 > 0) {
        //$data1 = mysqli_fetch_assoc($sel1);
        while($data1 = mysqli_fetch_assoc($sel1)) {
            array_push($order_product_list,$data1);
        }
   }
    
}else if($_POST['out_order_part'] == "상조"){
    $sel1 = mysqli_query($dbcon, "select * from sangjo.out_order_client_product where flower_out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
    $sel_num1 = mysqli_num_rows($sel1);
    
    if ($sel_num1 > 0) {
         //$data1 = mysqli_fetch_assoc($sel1);
         while($data1 = mysqli_fetch_assoc($sel1)) {
            array_push($order_product_list,$data1);
         }
    }
}






$sel2 = mysqli_query($dbcon, "select out_order_idx,filename from flower.attachment where out_order_idx='".$data['out_order_idx']."' and attachType='attachment' order by attachment_idx") or die(mysqli_error($dbcon));
$sel2_num = mysqli_num_rows($sel2);

$attachment_list = array();
if($sel2_num > 0) {
    while($data2 = mysqli_fetch_assoc($sel2)) {
        array_push($attachment_list,$data2);
    }
}



$sel3 = mysqli_query($dbcon, "select out_order_idx,filename from flower.attachment where out_order_idx='".$data['out_order_idx']."' and attachType='photo' order by attachment_idx") or die(mysqli_error($dbcon));
$sel3_num = mysqli_num_rows($sel3);

$photo_list = array();
if($sel3_num > 0) {
    while($data3 = mysqli_fetch_assoc($sel3)) {
        array_push($photo_list,$data3);
    }
}







$result = array();
$result['status'] = 1;
$result['data']['order_info']=$order_info;
$result['data']['order_product_list']=$order_product_list;
$result['data']['attachment_list']=$attachment_list;
$result['data']['photo_list']=$photo_list;



echo json_encode($result);


?>