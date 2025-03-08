<?
// 에러 로깅 설정
ini_set('display_errors', 0); // 브라우저에 에러 표시 안함
ini_set('log_errors', 1); // 에러 로깅 활성화
error_log("getOrderInfo.php 호출 시작: " . date('Y-m-d H:i:s'), 0);

// 요청 파라미터 로깅
error_log("POST 데이터: " . print_r($_POST, true), 0);

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

// 결과 배열 초기화
$result = array(
    'status' => 0,
    'data' => array(
        'order_info' => array(),
        'attachment_list' => array()
    ),
    'error' => '',
    'error_details' => ''
);

try {
    // POST 데이터 검증
    if (!isset($_POST['oocp_idx']) || empty($_POST['oocp_idx'])) {
        throw new Exception("oocp_idx 파라미터가 제공되지 않았습니다.");
    }
    
    // SQL 인젝션 방지를 위한 입력값 처리
    $oocp_idx = mysqli_real_escape_string($dbcon, $_POST['oocp_idx']);
    
    error_log("쿼리 실행 시작: oocp_idx=" . $oocp_idx);
    
    $query = "
    select a.*,b.t_company_name,b.to_place_name,b.to_address,b.to_name,b.to_hp,delivery_memo,b.total_client_price,
    b.total_client_price_tax,b.total_client_price_sum,d.base_storage_idx,d.t_base_storage_name,e.manager_name,b.admin_name 
    
    from (
    select * from sangjo_new.out_order_client_product where oocp_idx='".$oocp_idx."') a


    left join sangjo_new.out_order b 
    on a.out_order_idx=b.out_order_idx
    
    left join sangjo_new.client_product c 
    on a.client_product_idx=c.client_product_idx 
    
    left join sangjo_new.product d 
    on c.product_idx=d.product_idx
    
    
    left join consulting.manager e
    on b.manager_idx=e.manager_idx
    ";
    
    error_log("실행 쿼리: " . $query);
    
    $sel = mysqli_query($dbcon, $query);
    
    if (!$sel) {
        throw new Exception("첫 번째 쿼리 실행 오류: " . mysqli_error($dbcon));
    }
    
    $sel_num = mysqli_num_rows($sel);
    error_log("첫 번째 쿼리 결과 수: " . $sel_num);
    
    if ($sel_num <= 0) {
        throw new Exception("주문 정보를 찾을 수 없습니다. (oocp_idx: " . $oocp_idx . ")");
    }
    
    $order_info = array(); // 빈 배열로 초기화
    $data = array(); // 데이터 변수 초기화
    
    $data = mysqli_fetch_assoc($sel);
    error_log("데이터 조회 성공: " . json_encode($data, JSON_UNESCAPED_UNICODE));
    
    // null 값을 빈 문자열로 변환하여 undefined 방지
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            // 특수 문자나 이진 데이터 처리
            if (is_string($value)) {
                // UTF-8 검증 및 정리
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                $order_info[$key] = ($value === null) ? "" : $value;
            } else {
                $order_info[$key] = ($value === null) ? "" : $value;
            }
        }
    } else {
        throw new Exception("데이터가 배열 형식이 아닙니다: " . gettype($data));
    }
    
    $attachment_list = array();
    
    if (isset($data['out_order_idx']) && !empty($data['out_order_idx'])) {
        $out_order_idx = mysqli_real_escape_string($dbcon, $data['out_order_idx']);
        
        error_log("첨부 파일 조회 시작: out_order_idx=" . $out_order_idx);
        
        $query2 = "select attachment_idx,filename from sangjo_new.attachment where out_order_idx='".$out_order_idx."' order by attachment_idx";
        error_log("실행 쿼리2: " . $query2);
        
        $sel2 = mysqli_query($dbcon, $query2);
        
        if (!$sel2) {
            throw new Exception("두 번째 쿼리 실행 오류: " . mysqli_error($dbcon));
        }
        
        $sel2_num = mysqli_num_rows($sel2);
        error_log("첨부 파일 수: " . $sel2_num);
        
        if($sel2_num > 0) {
            while($data2 = mysqli_fetch_assoc($sel2)){
                $clean_data = array();
                // null 값을 빈 문자열로 변환
                foreach ($data2 as $key => $value) {
                    if (is_string($value)) {
                        // UTF-8 검증 및 정리
                        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                        $clean_data[$key] = ($value === null) ? "" : $value;
                    } else {
                        $clean_data[$key] = ($value === null) ? "" : $value;
                    }
                }
                array_push($attachment_list, $clean_data);
            }
        }
    } else {
        error_log("첨부 파일 조회 건너뜀: out_order_idx 없음");
    }
    
    // 성공 응답 설정
    $result['status'] = 1;
    $result['data']['order_info'] = $order_info;
    $result['data']['attachment_list'] = $attachment_list;
    
} catch (Exception $e) {
    // 에러 발생 시 로그 기록
    error_log("AJAX 에러 발생: " . $e->getMessage(), 0);
    error_log("에러 트레이스: " . $e->getTraceAsString(), 0);
    
    // 클라이언트에 에러 응답
    $result['status'] = 0;
    $result['error'] = "처리 중 오류가 발생했습니다.";
    $result['error_details'] = $e->getMessage();
}

// 디버깅 로그 추가
error_log("응답 데이터 구조: " . print_r(array_keys($result), true));
error_log("data 키 구조: " . (isset($result['data']) ? print_r(array_keys($result['data']), true) : "data 키 없음"));

// 응답 데이터 로깅
error_log("최종 응답 데이터: " . json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR));

// JSON 인코딩 전 데이터 검증
$json_result = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
if ($json_result === false) {
    error_log("JSON 인코딩 실패: " . json_last_error_msg());
    // JSON 인코딩 실패 시 간단한 응답으로 대체
    $result = array(
        'status' => 0,
        'error' => 'JSON 인코딩 오류',
        'error_details' => json_last_error_msg()
    );
    $json_result = json_encode($result);
}

// 브라우저 캐싱 방지
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// JSON 응답 전송
header('Content-Type: application/json; charset=utf-8');
echo $json_result;
?>