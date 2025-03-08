<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
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
        //$current_count = 0;

        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="출고 수량 부족";

        echo json_encode($result);
        exit;


    }

    if($arr['mode'] == "output"){
        $next_count = $current_count;
        $io_status = "미출고";

    }else if($arr['mode'] == "out_complete"){
        $next_count = $current_count - $product_list[$i]['cnt'];
        $io_status = "출고완료";

    }


    $in = mysqli_query($dbcon, "insert into in_out 
    set
    storage_idx='".$arr['storage_idx']."',
    product_idx='".$product_list[$i]['product_idx']."',
    current_count='".$next_count."',
    out_count='".$product_list[$i]['cnt']."',
    part='출고',
    io_status='".$io_status."',
    memo = '".mysqli_real_escape_string($dbcon, $arr['memo'])."',
    move_check = '".$arr['move_check']."',
    write_admin_idx='".$admin_info['admin_idx']."',
    t_write_admin_name='".$admin_info['admin_name']."',
    update_admin_idx='".$admin_info['admin_idx']."',
    t_update_admin_name='".$admin_info['admin_name']."'
    
    ") or die(mysqli_error($dbcon));

    $in_id = mysqli_insert_id($dbcon);
    if($in_id){//쿼리성공


        $in = mysqli_query($dbcon, "insert into out_order
        set io_idx=".$in_id.",
        storage_idx='".$arr['storage_idx']."',
        sender_name='".$arr['sender_name']."',
        to_address='".$arr['address']."',
        to_name='".$arr['to_name']."',
        to_hp='".$arr['hp']."'
        
        
        ") or die(mysqli_error($dbcon));
        $in_id = mysqli_insert_id($dbcon);
        if($in_id){//쿼리성공


            //안전재고 테이블 합계 저장 업데이트
            $in = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,current_count) values ('".$arr['storage_idx']."','".$product_list[$i]['product_idx']."','".$next_count."')
            ON DUPLICATE KEY UPDATE current_count='".$next_count."'
            
            ") or die(mysqli_error($dbcon));



         
        }else{//쿼리실패
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="저장 실패 E21";
    
            echo json_encode($result);
            exit;
        }
        
       
    }else{//쿼리실패
        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="저장 실패 E22";

        echo json_encode($result);
        exit;
    }



    if($arr['move_check'] == 1){ //이동지시서 작성
        //이동지시서 자동 작성을 위해서는 제품 최초 등록시 지정창고가 있어야 함.


        $sel = mysqli_query($dbcon, "select * from product where product_idx='".$product_list[$i]['product_idx']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $data = mysqli_fetch_assoc($sel);
            $base_storage_idx= $data['base_storage_idx'];
         
        }else{
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="자동이동지시 작성 중: 상품등록 정보 읽기 실패";
    
            echo json_encode($result);
            exit;
        }

        //기본출고 창고의 재고량 파악
        $sel = mysqli_query($dbcon, "select * from in_out where storage_idx='".$base_storage_idx."' and product_idx='".$product_list[$i]['product_idx']."' order by io_idx desc limit 1 ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $data = mysqli_fetch_assoc($sel);
            $base_current_count = $data['current_count'];
            
        }else{
            //$current_count = 0;

            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="자동이동지시 작성 중: 기본출고지 상품정보 없음";

            echo json_encode($result);
            exit;


        }

        $base_next_count = $base_current_count - $product_list[$i]['cnt'];

        $in = mysqli_query($dbcon, "insert into in_out 
        set
        storage_idx='".$base_storage_idx."',
        product_idx='".$product_list[$i]['product_idx']."',
        part='이동출고',
        io_status='미출고',
        out_count='".$product_list[$i]['cnt']."',
        to_storage_idx='".$arr['storage_idx']."',

        current_count='".$base_current_count."',


        memo = '".mysqli_real_escape_string($dbcon, $arr['memo'])."',
        move_check = '".$arr['move_check']."',
        write_admin_idx='".$admin_info['admin_idx']."',
        t_write_admin_name='".$admin_info['admin_name']."',
        update_admin_idx='".$admin_info['admin_idx']."',
        t_update_admin_name='".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));


    }
    

    
}












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