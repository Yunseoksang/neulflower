<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_fullfillment.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();


$post_data = file_get_contents("php://input");
$arr = json_decode($post_data , true); //배열




/* Start transaction */
mysqli_begin_transaction($dbcon);

$in = mysqli_query($dbcon, "insert into out_order
set 
consulting_idx='".$arr['consulting_idx']."',
to_place_name='".$arr['to_place_name']."',
to_address='".$arr['address']."',
to_name='".$arr['to_name']."',
to_hp='".$arr['hp']."',
delivery_memo='".$arr['memo']."',
admin_idx='".$admin_info['admin_idx']."',
admin_name='".$admin_info['admin_name']."'

") or die(mysqli_error($dbcon));
$out_order_idx = mysqli_insert_id($dbcon);
if($out_order_idx){//쿼리성공
    $client_product_list = $arr['client_product_list'];
    for ($i=0;$i<count($client_product_list);$i++ )
    {

        $in = mysqli_query($dbcon, "insert into out_order_client_product 
            set
            out_order_idx='".$out_order_idx."',
            consulting_idx='".$arr['consulting_idx']."',
            client_product_idx='".$client_product_list[$i]['client_product_idx']."',
            product_name='".$client_product_list[$i]['product_name']."',
            order_count='".$client_product_list[$i]['cnt']."',
            admin_idx='".$admin_info['admin_idx']."',
            admin_name='".$admin_info['admin_name']."'
            
        ") or die(mysqli_error($dbcon));
        $in_id = mysqli_insert_id($dbcon);
        if($in_id){//쿼리성공
            //
        }else{//쿼리실패
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="쿼리실행 오류";
        
            echo json_encode($result);
            exit;
        }
        
        
    }


    
    mysqli_commit($dbcon);

    $result = array();
    $result['status'] = 1;
    $result['msg']="저장되었습니다";

    echo json_encode($result);
    exit;


    
}else{//쿼리실패

    mysqli_rollback($dbcon);

    $result = array();
    $result['status'] = 0;
    $result['msg']="저장 실패 E21";

    echo json_encode($result);
    exit;
}






?>