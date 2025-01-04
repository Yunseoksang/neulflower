<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$post_data = file_get_contents("php://input");

$arr = json_decode($post_data , true); //배열



require('./common.php');

/* Start transaction */
mysqli_begin_transaction($dbcon);


$product_list = $arr['product_list'];
for ($i=0;$i<count($product_list);$i++ )
{
    $sel = mysqli_query($dbcon, "select * from storage_safe where storage_idx='".$arr['storage_idx']."' and product_idx='".$product_list[$i]['product_idx']."'  ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        $current_count = $data['current_count'];
        
    }else{
        $current_count = 0;
    }

    $next_count = $current_count + $product_list[$i]['cnt'];

    if($next_count < 0){
        //$current_count = 0;

        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="조정된 재고가 0개보가 작은품목이 포함되었습니다.[".$data['t_product_name']."]";

        echo json_encode($result);
        exit;

    }


    if($product_list[$i]['cnt'] >= 0){
       $inout_sql = "in_count='".$product_list[$i]['cnt']."',    

       in_manager_idx='".$admin_info['admin_idx']."',
       t_in_manager_name='".$admin_info['admin_name']."',";
    }else{
        $inout_sql = "out_count='".abs($product_list[$i]['cnt'])."',    

        out_manager_idx='".$admin_info['admin_idx']."',
        t_out_manager_name='".$admin_info['admin_name']."',";
    }


    $in = mysqli_query($dbcon, "insert into in_out 
    set
    storage_idx='".$arr['storage_idx']."',
    product_idx='".$product_list[$i]['product_idx']."',

    part='수량조정',
    io_status='조정완료',

    current_count='".$next_count."',
    memo = '".mysqli_real_escape_string($dbcon, $arr['memo'])."',

    ".$inout_sql."
    
    write_admin_idx='".$admin_info['admin_idx']."',
    t_write_admin_name='".$admin_info['admin_name']."',
    update_admin_idx='".$admin_info['admin_idx']."',
    t_update_admin_name='".$admin_info['admin_name']."'
    
    ") or die(mysqli_error($dbcon));


    //안전재고 테이블 합계 저장 업데이트
    $in = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,current_count) values ('".$arr['storage_idx']."','".$product_list[$i]['product_idx']."','".$next_count."')
    ON DUPLICATE KEY UPDATE current_count='".$next_count."'
    
    ") or die(mysqli_error($dbcon));

    
}


$sel = mysqli_query($dbcon, $base_input_product_query) or die(mysqli_error($dbcon));
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



?>