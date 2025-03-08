<?
// 에러 로깅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/php_errors.log');

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');

// DB 연결 체크
if (!$dbcon) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("DB connection failed");
}

admin_check_ajax();

// 원본 POST 데이터 로깅
error_log("Raw POST data: " . file_get_contents("php://input"));

$post_data = file_get_contents("php://input");
$arr = json_decode($post_data, true);

// 디코딩된 데이터 로깅
error_log("Decoded data: " . print_r($arr, true));

// JSON 디코딩 에러 체크
if (json_last_error() !== JSON_ERROR_NONE) {
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "JSON 파싱 오류: " . json_last_error_msg();
    error_log("JSON Parse Error: " . json_last_error_msg());
    echo json_encode($result);
    exit;
}

// admin_info 체크
if (!isset($admin_info) || empty($admin_info)) {
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "관리자 정보가 없습니다.";
    error_log("Admin info missing");
    echo json_encode($result);
    exit;
}







// 전역 함수로 선언
function e($value, $allowNull = false) {
    global $dbcon;
    // 숫자형 필드의 빈 값을 NULL로 처리
    if ($allowNull && ($value === null || $value === '' || $value === 'null')) {
        return 'NULL';
    }
    // 숫자형 필드에 숫자가 들어온 경우 따옴표 없이 반환
    if (is_numeric($value)) {
        return mysqli_real_escape_string($dbcon, $value);
    }
    return isset($value) ? mysqli_real_escape_string($dbcon, $value) : '';
}

// 빈 값이 아닌 데이터만 필터링하는 함수
function filterEmptyValues($data) {
    return array_filter($data, function($value) {
        return $value !== '' && $value !== 'NULL' && $value !== null;
    });
}












if($arr['mode'] == "input"){
    error_log("Starting input mode processing");
    
    /** 필수 요소 누락 체크 시작 */
    if($arr['company_name'] == ""){
        error_log("Company name is empty");
        error_json(0,"회사명은 필수입니다.");
        exit;
    }

    // 쿼리 실행 전 변수값 확인
    error_log("Input variables: " . print_r([
        'company_name' => $arr['company_name'],
        'input_part' => $arr['input_part'],
        'admin_idx' => $admin_info['admin_idx'],
        'admin_name' => $admin_info['admin_name']
    ], true));

    # $arr['head_office'] - 트리거에서 자동 설정 




    /** 필수 요소 누락 체크 끝 */



    /** 키데이터 중복여부 체크 */
    $check_query = "select * from consulting where company_name='".$arr['company_name']."'";
    error_log("Executing query: " . $check_query);
    
    $sel = mysqli_query($dbcon, $check_query);
    if (!$sel) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "중복체크 쿼리 오류: " . mysqli_error($dbcon);
        $result['query'] = $check_query;
        error_log("Duplicate Check Error: " . mysqli_error($dbcon));
        error_log("Query: " . $check_query);
        echo json_encode($result);
        exit;
    }
    $sel_num = mysqli_num_rows($sel);

    if ($sel_num > 0) {
        error_json(0,"이미 등록되어있는 회사명 입니다.");
        exit;
    }
    /** 키데이터 중복여부 체크 시작 끝 */






    // 회사 정보 데이터
    $company_data = filterEmptyValues([
        'part'            => e($arr['input_part']),
        'company_name'    => e($arr['company_name']),
        'employees'       => e($arr['employees']),
        'employment_fee'  => e($arr['employment_fee']),
        'trading_items'   => e($arr['trading_items']),
        'contract_date'   => e($arr['contract_date'])
    ]);

    // 사업자 정보
    $business_data = filterEmptyValues([
        'biz_num'        => e($arr['biz_num']),
        'corp_num'       => e($arr['corp_num']),
        'biz_part'       => e($arr['biz_part']),
        'biz_type'       => e($arr['biz_type'])
    ]);

    // 조직 정보
    $org_data = filterEmptyValues([
        'office_type'    => e($arr['office_type']),
        'head_office_consulting_idx' => e($arr['head_office_consulting_idx'], true),
        'head_office'    => e($arr['head_office'], true)
    ]);

    // 연락처 정보
    $contact_data = filterEmptyValues([
        'ceo_name'       => e($arr['ceo_name']),
        'tel'            => e($arr['tel']),
        'fax'            => e($arr['fax']),
        'address'        => e($arr['address']),
        'homepage'       => e($arr['homepage'])
    ]);

    // 기타 정보
    $etc_data = filterEmptyValues([
        'memo'           => e($arr['memo']),
        'meeting'        => e($arr['meeting']),
        'meeting_person' => e($arr['meeting_person']),
        'payment_date'   => e($arr['payment_date']),
        'admin_idx'      => e($admin_info['admin_idx']),
        'admin_name'     => e($admin_info['admin_name'])
    ]);

    // 모든 데이터 병합
    $insert_data = array_merge(
        $company_data,
        $business_data, 
        $org_data,
        $contact_data,
        $etc_data
    );

    // INSERT 쿼리 생성
    $columns = implode(", ", array_keys($insert_data));
    $values = implode(", ", array_map(function($val) {
        return $val === 'NULL' ? $val : "'$val'";
    }, array_values($insert_data)));
    $query_insert = "INSERT INTO consulting.consulting ($columns) VALUES ($values)";



    //echo $query_insert;
    // 쿼리 실행 전 로깅
    error_log("About to execute query: " . $query_insert);



    $in = mysqli_query($dbcon, $query_insert);



    if (!$in) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "SQL 오류: " . mysqli_error($dbcon);
        $result['query'] = $query_insert;
        echo json_encode($result);
        exit;
    }





    // 쿼리 실행 결과 로깅
    if (!$in) {
        error_log("Query execution failed. Error: " . mysqli_error($dbcon));
        error_log("SQL State: " . mysqli_sqlstate($dbcon));
        error_log("Error number: " . mysqli_errno($dbcon));
    } else {
        error_log("Query executed successfully");
    }

    if (!$in) {
        mysqli_query($dbcon, "ROLLBACK");
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "회사정보 입력 오류: " . mysqli_error($dbcon);
        $result['query'] = $query_insert;
        error_log("Company Insert Error: " . mysqli_error($dbcon));
        error_log("Query: " . $query_insert);
        echo json_encode($result);
        exit;
    }
    $consulting_idx = mysqli_insert_id($dbcon);
    if($consulting_idx){//쿼리성공


        if($arr['office_type'] == "계열사"){


            $jisa_query = "select count(*) as cnt from consulting where office_type='계열사' and head_office_consulting_idx='".$arr['head_office_consulting_idx']."'";
            $sel_jisa = mysqli_query($dbcon, $jisa_query);
            if (!$sel_jisa) {
                mysqli_query($dbcon, "ROLLBACK");
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "계열사 조회 오류: " . mysqli_error($dbcon);
                $result['query'] = $jisa_query;
                error_log("Jisa Select Error: " . mysqli_error($dbcon));
                error_log("Query: " . $jisa_query);
                echo json_encode($result);
                exit;
            }
            $sel_jisa_num = mysqli_num_rows($sel_jisa);
            
            if ($sel_jisa_num > 0) {
               $data_jisa = mysqli_fetch_assoc($sel_jisa);

               $up = mysqli_query($dbcon, "update consulting set jisa_count='".$data_jisa['cnt']."'  where consulting_idx='".$arr['head_office_consulting_idx']."' ") or die(mysqli_error($dbcon));

           }

        }

    //
    }else{//쿼리실패
        mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

        $result = array();
        $result['status'] = 0;
        $result['msg'] = "쿼리 입력오류:1 입니다. - " . mysqli_error($dbcon);
        $result['query'] = $query_insert;  // 실행된 쿼리 확인용
        error_log("Insert Error: " . mysqli_error($dbcon));
        error_log("Query: " . $query_insert);
        
        echo json_encode($result);
        
        exit;
    }

    $manager_list = $arr['manager_list'];
    for ($i=0;$i<count($manager_list);$i++ )
    {
     

        if(strlen($manager_list[$i]['manager_id']) > 2){
            $st_sql = "manager_status='승인',";
        }else{
            $st_sql = "";
        }
    


        $in = mysqli_query($dbcon, "insert into manager set
        consulting_idx    = '".$consulting_idx."',
        manager_id        = '".$manager_list[$i]['manager_id']."',
        ".$st_sql."

        item_in_charge    = '".$manager_list[$i]['item_in_charge']."',
        manager_name      = '".$manager_list[$i]['manager_name']."',
        manager_department= '".$manager_list[$i]['manager_department']."',
        manager_position  = '".$manager_list[$i]['manager_position']."',
        manager_email     = '".$manager_list[$i]['manager_email']."',
        manager_tel       = '".$manager_list[$i]['manager_tel']."',
        manager_hp        = '".$manager_list[$i]['manager_hp']."',
        manager_settlement_date        = '".$manager_list[$i]['manager_settlement_date']."',

        admin_idx         = '".$admin_info['admin_idx']."',
        admin_name        = '".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));
        $manager_idx = mysqli_insert_id($dbcon);
        if($manager_idx){//쿼리성공
           //
        }else{//쿼리실패
            mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));
           
            $result = array();
            $result['status'] = 0;
            $result['msg'] = "쿼리 입력오류:2 입니다. - " . mysqli_error($dbcon);
            $result['query'] = "insert into manager..."; // 실행된 쿼리 일부 표시
            $result['manager_data'] = $manager_list[$i];  // 문제가 된 데이터 확인용
            error_log("Manager Insert Error: " . mysqli_error($dbcon));
            
            echo json_encode($result);
            
           exit;
           break;
        }
    
    }





    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




    $result = array();
    $result['status'] = 1;
    $result['data']['consulting_idx']=$consulting_idx;
    $result['msg']="저장되었습니다.";

    //$result['data']['query_insert']=$query_insert;


    echo json_encode($result);

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

}else if($arr['mode'] == "edit"){
    error_log("Starting edit mode processing");

    /** 필수 요소 누락 체크 시작 */
    if($arr['company_name'] == ""){
        error_log("Company name is empty");
        error_json(0,"회사명은 필수입니다.");
        exit;
    }
    if($arr['consulting_idx'] > 0){
        error_log("Processing consulting_idx: " . $arr['consulting_idx']);
    }else{
        error_log("Invalid consulting_idx");
        error_json(0,"IDX 정보 에러입니다.");
        exit;
    }




    /** 키데이터 중복여부 체크 */
    $check_query = "SELECT * FROM consulting.consulting WHERE company_name='".e($arr['company_name'])."' AND consulting_idx != '".e($arr['consulting_idx'])."'";
    $sel = mysqli_query($dbcon, $check_query);
    if (!$sel) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "중복체크 쿼리 오류: " . mysqli_error($dbcon);
        $result['query'] = $check_query;
        echo json_encode($result);
        exit;
    }
    $sel_num = mysqli_num_rows($sel);


    if ($sel_num > 0) {
        error_json(0,"이미 등록되어있는 회사명 입니다.");
        exit;
    }

    //트랜잭션 시작
    if (!mysqli_query($dbcon, "START TRANSACTION")) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "트랜잭션 시작 오류: " . mysqli_error($dbcon);
        echo json_encode($result);
        exit;
    }

    // 계열사 정보 처리
    $ot_sql = "";
    if($arr['office_type'] == "계열사"){
        $ot_sql = "jisa_count=0,";
    }else{
        $jisa_query = "SELECT count(*) as cnt FROM consulting WHERE office_type='계열사' AND head_office_consulting_idx='".e($arr['consulting_idx'])."'";
        $sel_jisa = mysqli_query($dbcon, $jisa_query);
        if (!$sel_jisa) {
            mysqli_query($dbcon, "ROLLBACK");
            $result = array();
            $result['status'] = 0;
            $result['msg'] = "계열사 조회 오류: " . mysqli_error($dbcon);
            $result['query'] = $jisa_query;
            echo json_encode($result);
            exit;
        }
        $sel_jisa_num = mysqli_num_rows($sel_jisa);
        
        if ($sel_jisa_num > 0) {
            $data_jisa = mysqli_fetch_assoc($sel_jisa);
            $ot_sql = "jisa_count=".$data_jisa['cnt'].", ";
        }else{
            $ot_sql = "jisa_count=0,";
        }
    }

    // UPDATE 데이터 준비 (insert와 동일한 방식으로)
    $update_data = array(
        'company_name'    => e($arr['company_name']),
        'employees'       => e($arr['employees'], true),  // 숫자형 필드
        'employment_fee'  => e($arr['employment_fee'], true),  // 숫자형 필드
        'trading_items'   => e($arr['trading_items']),
        'contract_date'   => e($arr['contract_date']),
        'biz_num'        => e($arr['biz_num']),
        'corp_num'       => e($arr['corp_num']),
        'biz_part'       => e($arr['biz_part']),
        'biz_type'       => e($arr['biz_type']),
        'office_type'       => e($arr['office_type']),
        'head_office_consulting_idx'       => e($arr['head_office_consulting_idx'], true),  // 숫자형 필드, NULL 허용
        'head_office'       => e($arr['head_office']),
        'ceo_name'       => e($arr['ceo_name']),
        'tel'            => e($arr['tel']),
        'fax'            => e($arr['fax']),
        'address'        => e($arr['address']),
        'homepage'       => e($arr['homepage']),
        'memo'           => e($arr['memo']),
        'meeting'        => e($arr['meeting']),
        'meeting_person' => e($arr['meeting_person']),
        'payment_date'   => e($arr['payment_date']),
        'update_admin_idx'      => e($admin_info['admin_idx']),
        'update_admin_name'     => e($admin_info['admin_name'])
    );

    // UPDATE 쿼리 생성
    $update_sets = array();
    foreach($update_data as $key => $value) {
        // 숫자형 필드나 NULL은 따옴표 없이, 문자열은 따옴표 추가
        $update_sets[] = "$key = " . ($value === 'NULL' || is_numeric($value) ? $value : "'$value'");
    }
    $update_query = "UPDATE consulting SET " . $ot_sql . implode(", ", $update_sets) . 
                    " WHERE consulting_idx = '" . e($arr['consulting_idx']) . "'";


    // $result = array();
    // $result['status'] = 1;
    // $result['query'] = $update_query;
    // echo json_encode($result);
    // exit;

    


    // 쿼리 실행 및 에러 처리
    if (!mysqli_query($dbcon, $update_query)) {
        mysqli_query($dbcon, "ROLLBACK");
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "SQL 오류: " . mysqli_error($dbcon);
        $result['query'] = $update_query;
        echo json_encode($result);
        exit;
    }

    $manager_list = $arr['manager_list'];
    for ($i=0;$i<count($manager_list);$i++ )
    {

        if($manager_list[$i]['manager_idx'] > 0){

            $up = mysqli_query($dbcon, "update manager set
            manager_id        = '".$manager_list[$i]['manager_id']."',
            item_in_charge    = '".$manager_list[$i]['item_in_charge']."',
            manager_name      = '".$manager_list[$i]['manager_name']."',
            manager_department= '".$manager_list[$i]['manager_department']."',
            manager_position  = '".$manager_list[$i]['manager_position']."',
            manager_email     = '".$manager_list[$i]['manager_email']."',
            manager_tel       = '".$manager_list[$i]['manager_tel']."',
            manager_hp        = '".$manager_list[$i]['manager_hp']."',
            manager_settlement_date        = '".$manager_list[$i]['manager_settlement_date']."',

            admin_idx         = '".$admin_info['admin_idx']."',
            admin_name        = '".$admin_info['admin_name']."'

            where manager_idx='".$manager_list[$i]['manager_idx']."'
            
            ") or die(mysqli_error($dbcon));


            $up_num = mysqli_affected_rows($dbcon);
            if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
             //
            }else{ //쿼리실패
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));
    
               
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "쿼리 실행오류:4 입니다.";
                //$result['data']['query_insert']=$query_insert;
                
                
                echo json_encode($result);
                
               exit;
               break;
            }

        }else{

            if(strlen($manager_list[$i]['manager_id']) > 2){
                $st_sql = "manager_status='승인',";
            }else{
                $st_sql = "";
            }
        
    

            $in = mysqli_query($dbcon, "insert into manager set
            consulting_idx    = '".$arr['consulting_idx']."',
            manager_id        = '".$manager_list[$i]['manager_id']."',
            ".$st_sql."

            item_in_charge    = '".$manager_list[$i]['item_in_charge']."',

            manager_name      = '".$manager_list[$i]['manager_name']."',
            manager_department= '".$manager_list[$i]['manager_department']."',
            manager_position  = '".$manager_list[$i]['manager_position']."',
            manager_email     = '".$manager_list[$i]['manager_email']."',
            manager_tel       = '".$manager_list[$i]['manager_tel']."',
            manager_hp        = '".$manager_list[$i]['manager_hp']."',
            manager_settlement_date        = '".$manager_list[$i]['manager_settlement_date']."',

            admin_idx         = '".$admin_info['admin_idx']."',
            admin_name        = '".$admin_info['admin_name']."'
            
            ") or die(mysqli_error($dbcon));
            $manager_idx = mysqli_insert_id($dbcon);
            if($manager_idx){//쿼리성공
               //
            }else{//쿼리실패
                //
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));
    
               
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "쿼리 실행오류:5 입니다.";
                //$result['data']['query_insert']=$query_insert;
                
                
                echo json_encode($result);
                
               exit;
               break;
            }
        }
     
    
    }





    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




    $result = array();
    $result['status'] = 1;
    $result['data']['consulting_idx']=$arr['consulting_idx'];

    $result['msg'] = "저장되었습니다.";


    echo json_encode($result);


}


