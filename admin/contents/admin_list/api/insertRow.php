<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

// try-catch로 전체 로직 감싸기
try {
    // 요청 파라미터 로깅
    error_log("Request parameters: " . json_encode($_REQUEST));
    
    require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
    require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
    //session_start();
    admin_check_ajax();

    // table_name에서 DB명 추출
    $table_name = isset($_REQUEST['table_name']) ? $_REQUEST['table_name'] : '';
    error_log("[1] Initial table_name from REQUEST: " . $table_name);
    
    if (strpos($table_name, '.') !== false) {
        error_log("[2] Found DB prefix in table_name: " . $table_name);
        // DB명이 포함된 경우 (예: "admin.admin_list")
        error_log("[3] Using full table name with DB: " . $table_name);
    } else {
        error_log("[2] No DB prefix found in table_name: " . $table_name);
        // DB명이 없는 경우 기본 DB명 추가
        $table_name = "sangjo." . $table_name;
        error_log("[3] Added default DB prefix: " . $table_name);
    }
    error_log("[5] Final table_name before query: " . $table_name);

    // table_name과 key_column_name 먼저 초기화
    $key_column_name = isset($_REQUEST['key_column_name']) ? $_REQUEST['key_column_name'] : '';
    
    if(empty($table_name)) {
        throw new Exception("테이블명이 지정되지 않았습니다.");
    }

    // DB 연결 확인
    if (!isset($dbcon) || !$dbcon) {
        error_log("Database connection error: dbcon is not available");
        throw new Exception("데이터베이스 연결이 설정되지 않았습니다.");
    }

    /** 필수 요소 누락 체크 시작 */
    if($_POST['admin_name'] == ""){
        error_json(0,"관리자명은 필수입니다.");
        exit;
    }
    if($_POST['admin_id'] == ""){
        error_json(0,"관리자 아이디는 필수입니다.");
        exit;
    }

    // 아이디 중복 체크 추가
    $admin_id = mysqli_real_escape_string($dbcon, $_POST['admin_id']);
    $check_query = "SELECT * FROM ".$table_name." WHERE admin_id='".$admin_id."'";
    error_log("Check query: " . $check_query);
    
    $sel = mysqli_query($dbcon, $check_query);
    if (!$sel) {
        error_log("Query error: " . mysqli_error($dbcon));
        throw new Exception("쿼리 실행 중 오류가 발생했습니다: " . mysqli_error($dbcon));
    }
    if (mysqli_num_rows($sel) > 0) {
        error_json(0, "이미 사용중인 아이디입니다.");
        exit;
    }

    /** 필수 요소 누락 체크 끝 */



    /** 키데이터 중복여부 체크 */
    //$sel = mysqli_query($dbcon, "select * from ".$db_admin.".admin_list where admin_hp='".$_POST['admin_hp']."' ") or die(mysqli_error($dbcon));
    //$sel_num = mysqli_num_rows($sel);

    //if ($sel_num > 0) {
    //    error_json(0,"이미 등록되어있는 휴대폰 입니다.");
    //    exit;
    //}
    /** 키데이터 중복여부 체크 시작 끝 */






    //폼 요소 중에서 key_table에 insert 되는 칼럼이 아닌경우를 제외하고 쿼리문 완성하기.
    $set_sql = "";
    foreach($_REQUEST as $key => $val) {

        //칼럼명이 아닌것은 제외
        if($key == "table_name"){ //이미 처리된 테이블명은 건너뛰기
            continue;
        }
        if($key == "key_column_name"){  //이미 처리된 키컬럼명은 건너뛰기
            continue;
        }

        /******* 이부분만 추가,제외 시작*/
        // if($key == "brand_ex"){  // 다른 테이블용이라서 제외
        //     $brand_ex = $val;
        //     continue;
        // }
        /******* 이부분만 추가,제외 끝*/


        //칼럼명과 value 를 inset 쿼리에 세팅
        if($val != "" && $val != "undefined" && $val != null){
            if($set_sql == ""){
                $set_sql = " ".$key."='".mysqli_real_escape_string($dbcon,$val)."'";
            }else{
                $set_sql .= ", ".$key."='".mysqli_real_escape_string($dbcon,$val)."'";
            }
        }
    }


    $UUID = guidv4(random_bytes(16)); //php7 부터 가능
    $UUID = str_replace("-","",$UUID);
    //admin_idx 와 cf_admin_name 칼럼이 있는 경우
    $set_sql .= " , admin_uuid='".$UUID."'";

    // 트랜잭션 시작 전 쿼리 검증
    if (empty($set_sql)) {
        error_json(0, "입력할 데이터가 없습니다.");
        exit;
    }

    //insert 문이 2개 이상 일때 트랜젝션 삽입 권장.
    mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));


    $query_insert = "insert into ".$table_name." set ".$set_sql;
    $in = mysqli_query($dbcon, $query_insert);
    if (!$in) {
        mysqli_query($dbcon, "ROLLBACK");
        error_json(0, "데이터 입력 실패: " . mysqli_error($dbcon));
        exit;
    }

    $in_id = mysqli_insert_id($dbcon);
    if(!$in_id){
        mysqli_query($dbcon, "ROLLBACK");
        error_json(0,"데이터 입력 실패: ID를 가져올 수 없습니다.");
        exit;
    }



    /* key_table 이외의 다른 테이블에 분산해서 insert  해야 하는 경우 수행 */
    // $in = mysqli_query($dbcon, "insert into brand_explain 

    // set brand_idx=".$brand_idx.",
    // brand_ex='".mysqli_real_escape_string($dbcon,$brand_ex)."'

    // ") or die(mysqli_error($dbcon));
    // $in_id = mysqli_insert_id($dbcon);
    // if($in_id){//쿼리성공
       
    // }else{//쿼리실패
    //     mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

    //     error_json(0,"오류입니다.");
    //     exit;
    // }
    /* key_table 이외의 다른 테이블에 분산해서 insert  해야 하는 경우 수행 */


    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




    $result = array();
    $result['status'] = 1;
    $result['data'][$key_column_name]=$in_id;
    $result['data']['query_insert']=$query_insert;


    // 응답 전송 전 로깅
    error_log("응답 데이터: " . json_encode($result));
    
    header('Content-Type: application/json');
    echo json_encode($result);
    
} catch (Exception $e) {
    error_log("insertRow.php 오류 발생: " . $e->getMessage());
    echo json_encode([
        'status' => 0,
        'message' => $e->getMessage()
    ]);
}


