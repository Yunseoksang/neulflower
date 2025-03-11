<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
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


    //$sel = mysqli_query($dbcon, "select * from in_out where storage_idx='".$arr['from_storage_idx']."' and product_idx='".$product_list[$i]['product_idx']."' order by io_idx desc limit 1") or die(mysqli_error($dbcon));
    $sel = mysqli_query($dbcon, "select * from storage_safe where storage_idx='".$arr['from_storage_idx']."' and product_idx='".$product_list[$i]['product_idx']."' ") or die(mysqli_error($dbcon));

    
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        $current_count = $data['current_count'];
        
    }else{
        $current_count = 0;

        // mysqli_rollback($dbcon);

        // $result = array();
        // $result['status'] = 0;
        // $result['msg']="출고지 창고에 최초입고내역이 없습니다";

        // echo json_encode($result);
        // exit;

        //안전재고 테이블 합계 저장 업데이트 
        $in = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,current_count) values ('".$arr['from_storage_idx']."','".$product_list[$i]['product_idx']."','".$current_count."')
        ON DUPLICATE KEY UPDATE current_count='".$current_count."'
        
        ") or die(mysqli_error($dbcon));


    }

    //$next_count = $current_count - $product_list[$i]['cnt'];
    ///$next_count = $current_count - $product_list[$i]['cnt'];



    $in = mysqli_query($dbcon, "insert into in_out 
    set
    storage_idx='".$arr['from_storage_idx']."',
    product_idx='".$product_list[$i]['product_idx']."',
    part='이동출고',
    io_status='미출고',

    out_count='".$product_list[$i]['cnt']."',
    to_storage_idx='".$arr['to_storage_idx']."',
    current_count='".$current_count."',
    admin_memo = '".mysqli_real_escape_string($dbcon, $arr['memo'])."',

    write_admin_idx='".$admin_info['admin_idx']."',
    t_write_admin_name='".$admin_info['admin_name']."',
    update_admin_idx='".$admin_info['admin_idx']."',
    t_update_admin_name='".$admin_info['admin_name']."',

    moveout_manager_idx='".$admin_info['admin_idx']."',
    t_moveout_manager_name='".$admin_info['admin_name']."'
    
    ") or die(mysqli_error($dbcon));

    $in_id = mysqli_insert_id($dbcon);
    
    
    if($in_id){//쿼리성공
        //



       
    }else{//쿼리실패
        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="저장 실패 E22";

        echo json_encode($result);
        exit;
    }



    
}











$arr['storage_idx'] = $arr['from_storage_idx'];
require('./common.php');


$sel = mysqli_query($dbcon, $base_output_product_query) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$product_list = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_list,$data);
    
    }
}




$result = array();
$result['status'] = 1;
$result['data']['product_list']=$product_list;
$result['msg']="저장되었습니다";

echo json_encode($result);



mysqli_commit($dbcon);

?>