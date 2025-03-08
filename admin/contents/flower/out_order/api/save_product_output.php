<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

// 디버깅 함수 정의
function debug_to_file($data, $label = '') {
    $debug_file = $_SERVER["DOCUMENT_ROOT"].'/debug_log.txt';
    $output = date('Y-m-d H:i:s') . " - " . $label . ": ";
    if (is_array($data) || is_object($data)) {
        $output .= print_r($data, true);
    } else {
        $output .= $data;
    }
    file_put_contents($debug_file, $output . "\n", FILE_APPEND);
}

// 전역 예외 처리기 설정
set_exception_handler(function($e) {
    debug_to_file($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(), 'EXCEPTION');
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "오류가 발생했습니다: " . $e->getMessage();
    echo json_encode($result);
    exit;
});

debug_to_file($_POST, 'SAVE_PRODUCT_OUTPUT_REQUEST');

if($_POST['mode'] == "배송요청"){
    debug_to_file("배송요청 모드 시작", 'DELIVERY_REQUEST');

    /* Start transaction */
    mysqli_begin_transaction($dbcon);
    debug_to_file("트랜잭션 시작", 'TRANSACTION');

    $sel_oo = mysqli_query($dbcon, "select * from ".$db_flower.".out_order where out_order_idx='".$_POST['out_order_idx']."'
    
    ") or die(mysqli_error($dbcon));
    $sel_oo_num = mysqli_num_rows($sel_oo);
    debug_to_file("주문 조회 결과: $sel_oo_num 건", 'DELIVERY_REQUEST');
    
    if ($sel_oo_num > 0) {
        $data_oo = mysqli_fetch_assoc($sel_oo);
        debug_to_file($data_oo, 'ORDER_DATA');
        
        if($data_oo['out_order_status'] != '접수대기'){
                mysqli_rollback($dbcon);
                debug_to_file("접수대기 상태가 아님: {$data_oo['out_order_status']}", 'DELIVERY_REQUEST_ERROR');

                $result = array();
                $result['status'] = 0;
                $result['msg']="처리실패:접수대기 상태 일때만 배송요청 가능합니다.";
        
                echo json_encode($result);
                exit;
        }
    }else{
        debug_to_file("주문 정보 없음: {$_POST['out_order_idx']}", 'DELIVERY_REQUEST_ERROR');
        
        $result = array();
        $result['status'] = 0;
        $result['msg']="오류입니다 : ES001";

        echo json_encode($result);
        exit;
    }




    if($_POST['out_order_part'] == "상조"){
        debug_to_file("상조 주문 처리 시작", 'SANGJO_PROCESSING');

        $sel_output = mysqli_query($dbcon, "
            select client_product_idx,order_count as cnt,oocp_idx from ".$db_sangjo_new.".out_order_client_product where flower_out_order_idx='".$_POST['out_order_idx']."'


        ") or die(mysqli_error($dbcon));
        $sel_output_num = mysqli_num_rows($sel_output);


        debug_to_file("상조 상품 조회 결과: $sel_output_num 건", 'SANGJO_PROCESSING');



         //지점211
         $product_list = array();
         if ($sel_output_num > 0) {
             while($data_output = mysqli_fetch_assoc($sel_output)){
                 $product_list[] = array(
                     'client_product_idx' => $data_output['client_product_idx'],
                     'cnt' => $data_output['cnt'],
                     'oocp_idx' => $data_output['oocp_idx']
                 );
             }
             debug_to_file($product_list, 'PRODUCT_LIST_FOR_COMMON');
             
             // 쿼리 결과를 다시 사용하기 위해 포인터 리셋
             mysqli_data_seek($sel_output, 0);
         }
         
         $arr['consulting_idx'] = $_POST['consulting_idx'];

         $arr['storage_idx'] = $_POST['storage_idx'];
         $arr['mode'] = "output";
         $arr['address'] = $data_oo['address1']." ".$data_oo['address2'];
         $arr['to_name'] = $data_oo['r_name'];
         $arr['hp'] = $data_oo['r_tel'];
         $arr['admin_memo'] = $_POST['admin_memo'];
         $arr['move_check'] = $_POST['move_check'];

         $arr['admin_notice'] = $_POST['admin_notice'];
         $arr['head_officer'] = $_POST['head_officer'];
         $arr['agency_order_price'] = !empty($_POST['agency_order_price']) ? $_POST['agency_order_price'] : 0;

         debug_to_file($arr, 'SANGJO_COMMON_PARAMS');

         include($_SERVER["DOCUMENT_ROOT"].'/admin/contents/sangjo/storage_input/api/save_product_output_common.php');
         debug_to_file("상조 공통 처리 완료", 'SANGJO_PROCESSING');

         $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set out_order_status='배송요청' where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
         debug_to_file("주문 상태 업데이트: 배송요청", 'SANGJO_PROCESSING');
    
    
    }else if($_POST['out_order_part'] == "화훼"){
        debug_to_file("화훼 주문 처리 시작", 'FLOWER_PROCESSING');

        $sql_update= "update ".$db_flower.".out_order set out_order_status='배송요청',
        ".addConditionalField('storage_idx', $_POST['storage_idx'])."
        ".addConditionalField('admin_memo', $_POST['admin_memo'])."
        ".addConditionalField('move_check', $_POST['move_check'])."
        ".addConditionalField('admin_notice', $_POST['admin_notice'])."
        ".addConditionalField('head_officer', $_POST['head_officer'])."
        agency_order_price='".(!empty($_POST['agency_order_price']) ? $_POST['agency_order_price'] : 0)."'
        where out_order_idx='".$_POST['out_order_idx']."'
        ";
        
        debug_to_file($sql_update, 'FLOWER_UPDATE_SQL');

        $up = mysqli_query($dbcon, $sql_update) or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        
        debug_to_file("화훼 주문 업데이트 결과: $up_num 행", 'FLOWER_PROCESSING');
        
        if($up_num <= 0){
            debug_to_file("화훼 주문 업데이트 실패", 'FLOWER_PROCESSING_ERROR');
            mysqli_rollback($dbcon);
            
            $result = array();
            $result['status'] = 0;
            $result['msg']="오류입니다 : ES003";
        
            echo json_encode($result);
            exit;
        }
    }


    mysqli_commit($dbcon);
    debug_to_file("트랜잭션 커밋 완료", 'TRANSACTION');

    $result = array();
    $result['status'] = 1;
    $result['msg']="저장되었습니다";
    
    debug_to_file($result, '최종 결과');

    echo json_encode($result);
    exit;


}else if($_POST['mode'] == "주문취소"){
    debug_to_file("주문취소 모드 시작", 'ORDER_CANCEL');
    debug_to_file($_POST, 'ORDER_CANCEL_REQUEST');

    $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set out_order_status='주문취소' where out_order_idx='".$_POST['out_order_idx']."' and out_order_status='접수대기' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    debug_to_file("주문취소 업데이트 결과: $up_num 행", 'ORDER_CANCEL');
    
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        debug_to_file("주문취소 성공", 'ORDER_CANCEL');

        $result = array();
        $result['status'] = 1;
        $result['msg']="저장되었습니다";
        
        debug_to_file($result, '최종 결과');

        echo json_encode($result);
        exit;


    }else{ //쿼리실패
        debug_to_file("주문취소 실패: 접수대기 상태가 아님", 'ORDER_CANCEL_ERROR');
        
        $result = array();
        $result['status'] = 0;
        $result['msg']="처리실패:접수대기 상태일때만 주문취소 가능합니다.";
        
        debug_to_file($result, '최종 결과');

        echo json_encode($result);
        exit;

    }


}else if($_POST['mode'] == "발주정보저장"){
    debug_to_file("발주정보저장 모드 시작", 'ORDER_INFO_SAVE');
    debug_to_file($_POST, 'ORDER_INFO_SAVE_REQUEST');
    
    /* Start transaction */
    mysqli_begin_transaction($dbcon);
    debug_to_file("트랜잭션 시작", 'TRANSACTION');

    $update_sql = "update ".$db_flower.".out_order set
    branch_storage_idx='".$_POST['branch_storage_idx']."',
    branch_price='".$_POST['branch_price']."'
    where out_order_idx='".$_POST['out_order_idx']."' ";
    
    debug_to_file($update_sql, 'ORDER_INFO_UPDATE_SQL');

    $up = mysqli_query($dbcon, $update_sql) or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    debug_to_file("발주정보 업데이트 결과: $up_num 행", 'ORDER_INFO_SAVE');
    
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        debug_to_file("발주정보 업데이트 성공", 'ORDER_INFO_SAVE');

        mysqli_commit($dbcon);
        debug_to_file("트랜잭션 커밋 완료", 'TRANSACTION');

        $result = array();
        $result['status'] = 1;
        $result['msg']="협력사 발주정보가 저장되었습니다";
        
        debug_to_file($result, '최종 결과');

        echo json_encode($result);
        exit;

    }else{ //쿼리실패
        mysqli_rollback($dbcon);
        debug_to_file("발주정보 업데이트 실패", 'ORDER_INFO_SAVE_ERROR');
        
        $result = array();
        $result['status'] = 0;
        $result['msg']="처리실패";
        
        debug_to_file($result, '최종 결과');

        echo json_encode($result);
        exit;
    }


}else if($_POST['mode'] == "배송완료"){
    debug_to_file("배송완료 모드 시작", 'DELIVERY_COMPLETE');
    debug_to_file($_POST, 'DELIVERY_COMPLETE_REQUEST');

    /* Start transaction */
    mysqli_begin_transaction($dbcon);
    debug_to_file("트랜잭션 시작", 'TRANSACTION');

    $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set out_order_status='배송완료' where out_order_idx='".$_POST['out_order_idx']."' and out_order_status='배송요청' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    debug_to_file("배송완료 업데이트 결과: $up_num 행", 'DELIVERY_COMPLETE');
    
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        debug_to_file("배송완료 업데이트 성공", 'DELIVERY_COMPLETE');
        
        mysqli_commit($dbcon);
        debug_to_file("트랜잭션 커밋 완료", 'TRANSACTION');

        $result = array();
        $result['status'] = 1;
        $result['msg']="저장되었습니다";
        
        debug_to_file($result, '최종 결과');

        echo json_encode($result);
        exit;


    }else{ //쿼리실패
        mysqli_rollback($dbcon);
        debug_to_file("배송완료 업데이트 실패: 배송요청 상태가 아님", 'DELIVERY_COMPLETE_ERROR');
        
        $result = array();
        $result['status'] = 0;
        $result['msg']="처리실패:배송요청 상태일때만 배송완료 가능합니다.";
        
        debug_to_file($result, '최종 결과');

        echo json_encode($result);
        exit;

    }


}

?>