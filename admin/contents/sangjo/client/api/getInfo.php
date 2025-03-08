<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 디버깅을 위한 로그 파일
$log_file = $_SERVER["DOCUMENT_ROOT"].'/debug_log.txt';
file_put_contents($log_file, date('Y-m-d H:i:s') . " - API 호출 시작\n", FILE_APPEND);

try {
    require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
    require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
    //session_start();
    admin_check_ajax();

    // POST 데이터 로깅
    $post_data = print_r($_POST, true);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - POST 데이터: " . $post_data . "\n", FILE_APPEND);

    // 디버깅을 위한 코드
    if(!isset($_POST['consulting_idx']) || empty($_POST['consulting_idx'])) {
        $error = array('status' => 0, 'message' => 'consulting_idx가 없습니다.');
        echo json_encode($error);
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - 오류: consulting_idx가 없습니다.\n", FILE_APPEND);
        exit;
    }

    // 전역 변수 확인
    if(!isset($db_sangjo_new)) {
        global $db_sangjo_new;
        if(!isset($db_sangjo_new)) {
            $db_sangjo_new = "sangjo_new"; // 기본값 설정
            file_put_contents($log_file, date('Y-m-d H:i:s') . " - db_sangjo_new 변수 설정: " . $db_sangjo_new . "\n", FILE_APPEND);
        }
    }

    if(!isset($db_consulting)) {
        global $db_consulting;
        if(!isset($db_consulting)) {
            $db_consulting = "consulting"; // 기본값 설정
            file_put_contents($log_file, date('Y-m-d H:i:s') . " - db_consulting 변수 설정: " . $db_consulting . "\n", FILE_APPEND);
        }
    }

    // DB 연결 확인
    if(!isset($dbcon) || !$dbcon) {
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - 오류: DB 연결이 없습니다.\n", FILE_APPEND);
        throw new Exception("DB 연결이 없습니다.");
    }

    $consulting_idx = mysqli_real_escape_string($dbcon, $_POST['consulting_idx']);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - consulting_idx: " . $consulting_idx . "\n", FILE_APPEND);

    // 쿼리 실행 전 로깅
    $query = "select * from ".$db_consulting.".consulting where consulting_idx='".$consulting_idx."'";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 실행: " . $query . "\n", FILE_APPEND);
    
    $sel = mysqli_query($dbcon, $query) or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 결과 수: " . $sel_num . "\n", FILE_APPEND);

    $company_info = array();
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        $company_info = $data;
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - company_info 데이터 가져옴\n", FILE_APPEND);
    }

    // 메모 데이터 가져오기
    $query1 = "select *,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime from ".$db_consulting.".memo where consulting_idx='".$consulting_idx."' order by memo_idx desc";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 실행: " . $query1 . "\n", FILE_APPEND);
    
    $sel1 = mysqli_query($dbcon, $query1) or die(mysqli_error($dbcon));
    $sel1_num = mysqli_num_rows($sel1);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 결과 수: " . $sel1_num . "\n", FILE_APPEND);

    $memo_list = array();
    if ($sel1_num > 0) {
        while($data1 = mysqli_fetch_assoc($sel1)) {
            array_push($memo_list,$data1);
        }
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - memo_list 데이터 가져옴\n", FILE_APPEND);
    }

    // 첨부 파일 데이터 가져오기
    $query2 = "select * from ".$db_consulting.".attachment where consulting_idx='".$consulting_idx."' order by attachment_idx";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 실행: " . $query2 . "\n", FILE_APPEND);
    
    $sel2 = mysqli_query($dbcon, $query2) or die(mysqli_error($dbcon));
    $sel2_num = mysqli_num_rows($sel2);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 결과 수: " . $sel2_num . "\n", FILE_APPEND);

    $attachment_list = array();
    if ($sel2_num > 0) {
        while($data2 = mysqli_fetch_assoc($sel2)) {
            array_push($attachment_list,$data2);
        }
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - attachment_list 데이터 가져옴\n", FILE_APPEND);
    }

    // 매니저 데이터 가져오기
    $query3 = "select * from ".$db_consulting.".manager where consulting_idx='".$consulting_idx."' order by manager_idx";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 실행: " . $query3 . "\n", FILE_APPEND);
    
    $sel3 = mysqli_query($dbcon, $query3) or die(mysqli_error($dbcon));
    $sel3_num = mysqli_num_rows($sel3);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 결과 수: " . $sel3_num . "\n", FILE_APPEND);

    $manager_list = array();
    if ($sel3_num > 0) {
        while($data3 = mysqli_fetch_assoc($sel3)) {
            array_push($manager_list,$data3);
        }
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - manager_list 데이터 가져옴\n", FILE_APPEND);
    }

    // 제품 데이터 가져오기
    $query4 = "select b.*,a.client_product_idx,a.client_price,a.client_price_tax,a.client_price_sum from (
        select * from ".$db_sangjo_new.".client_product where consulting_idx='".$consulting_idx."' ) a 
        left join ".$db_sangjo_new.".product b 
        on a.product_idx=b.product_idx 
        order by b.t_category1_name, b.t_category1_name";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 실행: " . $query4 . "\n", FILE_APPEND);
    
    $sel4 = mysqli_query($dbcon, $query4) or die(mysqli_error($dbcon));
    $sel4_num = mysqli_num_rows($sel4);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 결과 수: " . $sel4_num . "\n", FILE_APPEND);

    $client_product_list = array();
    if ($sel4_num > 0) {
        while($data4 = mysqli_fetch_assoc($sel4)) {
            array_push($client_product_list,$data4);
        }
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - client_product_list 데이터 가져옴\n", FILE_APPEND);
    }

    // 장소 데이터 가져오기
    $query5 = "select * from ".$db_consulting.".client_place where consulting_idx='".$consulting_idx."' order by client_place_idx";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 실행: " . $query5 . "\n", FILE_APPEND);
    
    $sel5 = mysqli_query($dbcon, $query5) or die(mysqli_error($dbcon));
    $sel5_num = mysqli_num_rows($sel5);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 결과 수: " . $sel5_num . "\n", FILE_APPEND);

    $place_list = array();
    if ($sel5_num > 0) {
        while($data5 = mysqli_fetch_assoc($sel5)) {
            array_push($place_list,$data5);
        }
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - place_list 데이터 가져옴\n", FILE_APPEND);
    }

    // 카테고리 매니저 데이터 가져오기
    $query6 = "select a.category1_idx,a.category1_name,
    b.fs_idx,b.sdate,
    c.sm_idx,
    d.manager_idx,d.manager_name,d.manager_email,d.manager_position,d.manager_department 
    from ".$db_sangjo_new.".category1 a 
    left join (select * from ".$db_sangjo_new.".settlement_sdate 
        where consulting_idx='".$consulting_idx."') b 
    on a.category1_idx=b.category1_idx
    left join ".$db_sangjo_new.".settlement_manager c
    on a.category1_idx=c.category1_idx and c.consulting_idx='".$consulting_idx."'
    left join ".$db_consulting.".manager d
    on c.manager_idx=d.manager_idx
    order by a.category1_name,sm_idx";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 실행: " . $query6 . "\n", FILE_APPEND);
    
    $sel6 = mysqli_query($dbcon, $query6) or die(mysqli_error($dbcon));
    $sel6_num = mysqli_num_rows($sel6);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 쿼리 결과 수: " . $sel6_num . "\n", FILE_APPEND);

    $category1_manager_list = array();
    if ($sel6_num > 0) {
        while($data6 = mysqli_fetch_assoc($sel6)) {
            array_push($category1_manager_list,$data6);
        }
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - category1_manager_list 데이터 가져옴\n", FILE_APPEND);
    }

    // 결과 배열 생성
    $result = array();
    $result['status'] = 1;
    $result['data']['company_info'] = $company_info;
    $result['data']['manager_list'] = $manager_list;
    $result['data']['memo_list'] = $memo_list;
    $result['data']['attachment_list'] = $attachment_list;
    $result['data']['client_product_list'] = $client_product_list;
    $result['data']['place_list'] = $place_list;
    $result['data']['category1_manager_list'] = $category1_manager_list;

    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 결과 데이터 생성 완료\n", FILE_APPEND);
    
    // JSON 인코딩 및 출력
    $json_result = json_encode($result);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - JSON 인코딩 완료: " . substr($json_result, 0, 100) . "...\n", FILE_APPEND);
    
    echo $json_result;

} catch (Exception $e) {
    $error = array('status' => 0, 'message' => $e->getMessage());
    echo json_encode($error);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - 오류: " . $e->getMessage() . "\n", FILE_APPEND);
}

?>