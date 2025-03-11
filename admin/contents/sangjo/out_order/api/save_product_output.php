<?
// 에러 보고 설정
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER["DOCUMENT_ROOT"].'/debug_log.txt');
error_reporting(E_ALL);

// 예외 처리 핸들러 등록
set_exception_handler(function($exception) {
    $error_code = md5(date('H:i:s') . rand(1000, 9999));
    
    $error_message = "예외 발생: " . $exception->getMessage() . "\n";
    $error_message .= "파일: " . $exception->getFile() . " (라인: " . $exception->getLine() . ")\n";
    $error_message .= "스택 트레이스: " . $exception->getTraceAsString() . "\n";
    $error_message .= "에러 코드: " . $error_code . "\n";
    error_log($error_message);
    
    $error_detail = $exception->getMessage() . " - 파일: " . $exception->getFile() . " (라인: " . $exception->getLine() . ")";
    
    $result = array();
    $result['status'] = 0;
    $result['msg'] = $error_detail; // 에러 메시지를 직접 보여줌
    $result['error_code'] = $error_code;
    echo json_encode($result);
    exit;
});

// 에러 핸들러 등록
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $error_code = md5(date('H:i:s') . rand(1000, 9999));
    
    $error_message = "에러 발생: [$errno] $errstr\n";
    $error_message .= "파일: $errfile (라인: $errline)\n";
    $error_message .= "에러 코드: " . $error_code . "\n";
    error_log($error_message);
    
    $error_detail = "[$errno] $errstr - 파일: $errfile (라인: $errline)";
    
    if ($errno == E_USER_ERROR) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = $error_detail; // 에러 메시지를 직접 보여줌
        $result['error_code'] = $error_code;
        echo json_encode($result);
        exit;
    }
    
    return true;
});

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

// admin_info 변수 확인
if (!isset($admin_info) || empty($admin_info)) {
    error_log("admin_info 변수가 정의되지 않았거나 비어 있습니다.");
    // admin_info 변수가 없는 경우 세션에서 가져오기 시도
    if (isset($_SESSION['admin_info'])) {
        $admin_info = $_SESSION['admin_info'];
        error_log("세션에서 admin_info 변수를 가져왔습니다: " . print_r($admin_info, true));
    } else {
        error_log("세션에도 admin_info 변수가 없습니다.");
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "관리자 정보를 찾을 수 없습니다. 다시 로그인해주세요.";
        echo json_encode($result);
        exit;
    }
}

// 에러 로깅 함수 추가
function log_error($message, $query = '', $error = '') {
    $log_file = $_SERVER["DOCUMENT_ROOT"].'/debug_log.txt';
    
    $date = date('Y-m-d');
    $time = date('H:i:s');
    
    // 에러 코드 생성 (추적용)
    $error_code = md5($time . rand(1000, 9999));
    
    // 로그 메시지 구성
    $log_message = "[" . $date . " " . $time . "] [코드: " . $error_code . "] " . $message . "\n";
    if (!empty($query)) {
        $log_message .= "쿼리: " . $query . "\n";
    }
    if (!empty($error)) {
        $log_message .= "에러: " . $error . "\n";
    }
    $log_message .= "요청 데이터: " . print_r($_POST, true) . "\n";
    $log_message .= "서버 정보: " . print_r($_SERVER, true) . "\n";
    $log_message .= "----------------------------------------\n";
    
    // 로그 파일에 기록
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    // 상세 에러 메시지 구성
    $error_detail = $message;
    if (!empty($query)) {
        $error_detail .= " - 쿼리: " . $query;
    }
    if (!empty($error)) {
        $error_detail .= " - 에러: " . $error;
    }
    
    // JSON 응답 반환
    $result = array();
    $result['status'] = 0;
    $result['msg'] = $error_detail; // 에러 메시지를 직접 보여줌
    $result['error_code'] = $error_code;
    
    echo json_encode($result);
    exit;
}

// 쿼리 실행 함수 (에러 처리 포함)
function execute_query($dbcon, $query, $error_message = "쿼리 실행 오류") {
    $result = mysqli_query($dbcon, $query);
    if (!$result) {
        $mysqli_error = mysqli_error($dbcon);
        $mysqli_errno = mysqli_errno($dbcon);
        $detailed_error = "MySQL 에러 #$mysqli_errno: $mysqli_error";
        log_error($error_message, $query, $detailed_error);
    }
    return $result;
}

if($_POST['mode'] == "출고지시"){

    /* Start transaction */
    mysqli_begin_transaction($dbcon);
    
    // 요청 데이터 로깅
    error_log("출고지시 요청 데이터: " . print_r($_POST, true));

    $sel_oocp_query = "select b.product_idx,a.* from (select * from sangjo_new.out_order_client_product where oocp_idx='".$_POST['oocp_idx']."') a 
    left join sangjo_new.client_product b
    on a.client_product_idx=b.client_product_idx";
    
    error_log("주문 정보 조회 쿼리: " . $sel_oocp_query);
    $sel_oocp = execute_query($dbcon, $sel_oocp_query, "출고지시 - 주문 정보 조회 실패");
    $sel_oocp_num = mysqli_num_rows($sel_oocp);
    
    if ($sel_oocp_num > 0) {
        $data_oocp = mysqli_fetch_assoc($sel_oocp);
        error_log("주문 정보 조회 결과: " . print_r($data_oocp, true));
        
        if($data_oocp['oocp_status'] != '주문접수'){
                mysqli_rollback($dbcon);
                error_log("주문 상태 오류: " . $data_oocp['oocp_status']);

                $result = array();
                $result['status'] = 0;
                $result['msg'] = "현재 주문 상태: " . $data_oocp['oocp_status'] . " (주문접수 상태여야 함)";
                $result['error_code'] = md5(date('H:i:s') . rand(1000, 9999));
        
                echo json_encode($result);
                exit;
        }
    } else {
        error_log("주문 정보 없음: oocp_idx=" . $_POST['oocp_idx']);
    }

    $sel_query = "select * from storage_safe where storage_idx='".$_POST['storage_idx']."' and product_idx='".$data_oocp['product_idx']."' limit 1";
    error_log("재고 정보 조회 쿼리: " . $sel_query);
    $sel = execute_query($dbcon, $sel_query, "출고지시 - 재고 정보 조회 실패");
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        error_log("재고 정보 조회 결과: " . print_r($data, true));
        $current_count = $data['current_count'];
        $next_count = $current_count;

        /* 23.01.14 이종철님 요청으로 주문내역->출고지시 단계에서는 재고수량 사전체크 하지 않음.
        if($data_oocp['order_count'] > $current_count ){

            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="출고 수량 부족";
    
            echo json_encode($result);
            exit;
        }

        */

        
    }else{
        error_log("재고 정보 없음: storage_idx=" . $_POST['storage_idx'] . ", product_idx=" . $data_oocp['product_idx']);
        $current_count = 0;
        $next_count = $current_count;

        /*

        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="출고 수량 부족";

        echo json_encode($result);
        exit;
        */


    }

    $insert_query = "insert into in_out 
    set
    storage_idx='".$_POST['storage_idx']."',
    out_order_idx='".$data_oocp['out_order_idx']."',
    oocp_idx='".$data_oocp['oocp_idx']."',
    client_product_idx='".$data_oocp['client_product_idx']."',
    product_idx='".$data_oocp['product_idx']."',
    client_price_sum='".$data_oocp['client_price_sum']."',
    total_client_price_sum='".$data_oocp['total_client_price_sum']."',
    current_count='".$next_count."',
    out_count='".$data_oocp['order_count']."',
    part='출고',
    io_status='미출고',
    admin_memo='".$_POST['admin_memo']."',
    write_admin_idx='".$admin_info['admin_idx']."',
    t_write_admin_name='".$admin_info['admin_name']."',
    update_admin_idx='".$admin_info['admin_idx']."',
    t_update_admin_name='".$admin_info['admin_name']."',
    regist_datetime=NOW(),
    update_datetime=NOW()";
    
    error_log("출고 정보 저장 쿼리: " . $insert_query);
    $in = execute_query($dbcon, $insert_query, "출고지시 - 출고 정보 저장 실패");

    $io_idx = mysqli_insert_id($dbcon);
    error_log("출고 정보 저장 결과 io_idx: " . $io_idx);
    if($io_idx){//쿼리성공

        $up_num = mysqli_affected_rows($dbcon);
        error_log("영향받은 행 수: " . $up_num);
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
             $update_query = "update out_order_client_product set oocp_status='출고지시', update_datetime=NOW() where oocp_idx='".$_POST['oocp_idx']."' and oocp_status='주문접수'";
             error_log("주문 상태 업데이트 쿼리: " . $update_query);
             $up = execute_query($dbcon, $update_query, "출고지시 - 주문 상태 업데이트 실패");
             $up_num = mysqli_affected_rows($dbcon);
             error_log("주문 상태 업데이트 영향받은 행 수: " . $up_num);
             if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                //
             }else{ //쿼리실패
                mysqli_rollback($dbcon);
                error_log("주문 상태 업데이트 실패: oocp_idx=" . $_POST['oocp_idx']);

                $result = array();
                $result['status'] = 0;
                $result['msg'] = "주문 상태 업데이트 실패 - oocp_idx: " . $_POST['oocp_idx'] . " - 영향받은 행 수: " . $up_num . " - 쿼리: " . $update_query;
                $result['error_code'] = md5(date('H:i:s') . rand(1000, 9999));
        
                echo json_encode($result);
                exit;
             }


        // out_order 테이블의 storage_idx 업데이트
        $update_out_order_query = "update out_order set 
            storage_idx='".$_POST['storage_idx']."',
            update_datetime=NOW() 
            where out_order_idx=(
                select out_order_idx 
                from out_order_client_product 
                where oocp_idx='".$_POST['oocp_idx']."'
            )";
        error_log("출고지 정보 업데이트 쿼리: " . $update_out_order_query);
        $up2 = execute_query($dbcon, $update_out_order_query, "출고지시 - 출고지 정보 업데이트 실패");
        $up_num2 = mysqli_affected_rows($dbcon);
        error_log("출고지 정보 업데이트 영향받은 행 수: " . $up_num2);
        if($up_num2 <= 0){ //업데이트 실패
            mysqli_rollback($dbcon);
            error_log("출고지 정보 업데이트 실패: oocp_idx=" . $_POST['oocp_idx']);

            $result = array();
            $result['status'] = 0;
            $result['msg'] = "출고지 정보 업데이트 실패 - oocp_idx: " . $_POST['oocp_idx'] . " - 영향받은 행 수: " . $up_num2 . " - 쿼리: " . $update_out_order_query;
            $result['error_code'] = md5(date('H:i:s') . rand(1000, 9999));
    
            echo json_encode($result);
            exit;
        }







        }else{ //쿼리실패
            mysqli_rollback($dbcon);
            error_log("출고 정보 저장 실패: 영향받은 행 수가 0보다 작음");

            $result = array();
            $result['status'] = 0;
            $result['msg'] = "영향받은 행 수: " . $up_num . " - 데이터베이스 오류: " . mysqli_error($dbcon);
            $result['error_code'] = md5(date('H:i:s') . rand(1000, 9999));
    
            echo json_encode($result);
            exit;
        }

        
    
    }else{//쿼리실패
        mysqli_rollback($dbcon);
        error_log("출고 정보 저장 실패: io_idx가 없음");

        $result = array();
        $result['status'] = 0;
        $result['msg'] = "출고 정보 저장 실패 - 데이터베이스 오류: " . mysqli_error($dbcon);
        $result['error_code'] = md5(date('H:i:s') . rand(1000, 9999));

        echo json_encode($result);
        exit;
    }

    mysqli_commit($dbcon);

    $result = array();
    $result['status'] = 1;
    $result['msg']="저장되었습니다";

    echo json_encode($result);
    exit;


}else if($_POST['mode'] == "주문취소"){

    $update_query = "update out_order_client_product set oocp_status='주문취소', update_datetime=NOW() where oocp_idx='".$_POST['oocp_idx']."' and oocp_status='주문접수'";
    $up = execute_query($dbcon, $update_query, "주문취소 - 주문 상태 업데이트 실패");
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     
        $result = array();
        $result['status'] = 1;
        $result['msg']="저장되었습니다";

        echo json_encode($result);
        exit;

    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "영향받은 행 수: " . $up_num . " - 쿼리: " . $update_query;
        $result['error_code'] = md5(date('H:i:s') . rand(1000, 9999));

        echo json_encode($result);
        exit;
    }
}

// 잘못된 mode 값이 전달된 경우
$mode_value = isset($_POST['mode']) ? $_POST['mode'] : '값 없음';
log_error("잘못된 mode 값", "", "mode 값: " . $mode_value);
?>