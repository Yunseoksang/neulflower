<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

// // 오류 로깅 활성화
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// 요청 데이터 로깅
$request_data = file_get_contents('php://input');
if(empty($request_data)) {
    $request_data = isset($_REQUEST['jsonDataPHP']) ? $_REQUEST['jsonDataPHP'] : '';
}

// 디버깅을 위한 로그 파일 생성
$log_dir = $_SERVER["DOCUMENT_ROOT"].'/logs';
if(!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}
$log_file = $log_dir.'/update_row_'.date('Y-m-d').'.log';
file_put_contents($log_file, date('Y-m-d H:i:s')." - 요청 데이터: ".$request_data."\n", FILE_APPEND);

$main_table_name = isset($_POST['table_name']) ? $_POST['table_name'] : '';

//삭제
if($_POST['mode'] == "delete"){
   $del = mysqli_query($dbcon, "delete from ".$main_table_name." where ".$_POST['key_name']."='".$_POST['key_value']."' ") or die(mysqli_error($dbcon));
   $del_num = mysqli_affected_rows($dbcon);
   if($del_num > 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
      //
   }else{ //쿼리실패
      error_json(0,"삭제내역이 없습니다.");
      exit;
   }

   $result = array();
   $result['status'] = 1;
   $result['msg'] = "삭제되었습니다";

   echo json_encode($result);
   exit;

}





//수정저장하기 
$jsonData = null;
try {
    $jsonData = json_decode($_REQUEST['jsonDataPHP']);
    if(json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON 디코딩 오류: " . json_last_error_msg());
    }
    file_put_contents($log_file, date('Y-m-d H:i:s')." - JSON 디코딩 성공\n", FILE_APPEND);
} catch(Exception $e) {
    file_put_contents($log_file, date('Y-m-d H:i:s')." - 오류: ".$e->getMessage()."\n", FILE_APPEND);
    error_json(0, "JSON 데이터 처리 오류: ".$e->getMessage());
    exit;
}

if($jsonData && $jsonData->mode == "update"){

   $table_name = $jsonData->table_name;
   // 테이블 이름에서 데이터베이스 이름 분리
   if(strpos($table_name, '.') !== false) {
      $table_parts = explode('.', $table_name);
      $table_name = $table_parts[1]; // 테이블 이름만 사용
   }
   file_put_contents($log_file, date('Y-m-d H:i:s')." - 테이블 이름: ".$table_name."\n", FILE_APPEND);

   $key_name = $jsonData->key_name;
   $key_value = $jsonData->key_value;
   $inputArray = $jsonData->inputArray;

   $set_sql = "";

   $set_sql2 = "";

   for ($i=0;$i<count($inputArray);$i++ )
   {
      $column_name = $inputArray[$i]->name;
      $column_value = $inputArray[$i]->value;

      file_put_contents($log_file, date('Y-m-d H:i:s')." - 처리 중인 필드: ".$column_name.", 값: ".$column_value."\n", FILE_APPEND);

      /** 다른 테이블 칼럼이 섞여 있는 경우 제외시킨다.시작 */
      if($column_name == "to_place_name" || $column_name == "to_address"  || $column_name == "to_name"  || $column_name == "to_hp"  || $column_name == "to_phone"  || $column_name == "receiver_name"  ){

         if( $set_sql2 != ""){
            $comma2 = ",";
         }else{
            $comma2 = " ";
         }
         if ($column_value == ""){
            $set_sql2 .= $comma2.$column_name."=null ";
          }else{
            $set_sql2 .= $comma2.$column_name."='".mysqli_real_escape_string($dbcon,$column_value)."' ";
          }
         file_put_contents($log_file, date('Y-m-d H:i:s')." - set_sql2에 추가: ".$column_name."\n", FILE_APPEND);
         continue;

      }else{
         if( $set_sql != ""){
            $comma = ",";
         }else{
            $comma = " ";
         }
         if ($column_value == ""){
            $set_sql .= $comma.$column_name."=null ";
          }else{
            $set_sql .= $comma.$column_name."='".mysqli_real_escape_string($dbcon,$column_value)."' ";
          }
         file_put_contents($log_file, date('Y-m-d H:i:s')." - set_sql에 추가: ".$column_name."\n", FILE_APPEND);
         continue;
      }
   }

   file_put_contents($log_file, date('Y-m-d H:i:s')." - 최종 set_sql: ".$set_sql."\n", FILE_APPEND);
   file_put_contents($log_file, date('Y-m-d H:i:s')." - 최종 set_sql2: ".$set_sql2."\n", FILE_APPEND);

   if($set_sql != ""){
      try {
         $query = "update ".$table_name." set ".$set_sql." where ".$key_name."='".$key_value."'";
         file_put_contents($log_file, date('Y-m-d H:i:s')." - 실행 쿼리: ".$query."\n", FILE_APPEND);
         $up = mysqli_query($dbcon, $query);
         if(!$up) {
            throw new Exception(mysqli_error($dbcon));
         }
         file_put_contents($log_file, date('Y-m-d H:i:s')." - 메인 테이블 업데이트 성공\n", FILE_APPEND);
      } catch(Exception $e) {
         file_put_contents($log_file, date('Y-m-d H:i:s')." - 메인 테이블 업데이트 오류: ".$e->getMessage()."\n", FILE_APPEND);
         error_json(0, "메인 테이블 업데이트 오류: ".$e->getMessage());
         exit;
      }
   }

   /**  다른 테이블 동시 insert 하는 경우 시작 */
   if($set_sql2 != ""){
      try {
         $query = "select * from ".$table_name." where ".$key_name."='".$key_value."'";
         file_put_contents($log_file, date('Y-m-d H:i:s')." - 실행 쿼리: ".$query."\n", FILE_APPEND);
         $sel = mysqli_query($dbcon, $query);
         if(!$sel) {
            throw new Exception(mysqli_error($dbcon));
         }
         $sel_num = mysqli_num_rows($sel);
         file_put_contents($log_file, date('Y-m-d H:i:s')." - 조회된 레코드 수: ".$sel_num."\n", FILE_APPEND);
         
         if ($sel_num > 0) {
            $data = mysqli_fetch_assoc($sel);
            file_put_contents($log_file, date('Y-m-d H:i:s')." - out_order_idx: ".(isset($data['out_order_idx']) ? $data['out_order_idx'] : '없음')."\n", FILE_APPEND);
            
            if(isset($data['out_order_idx']) && $data['out_order_idx'] != '') {
               $query = "update out_order set ".$set_sql2." where out_order_idx='".$data['out_order_idx']."'";
               file_put_contents($log_file, date('Y-m-d H:i:s')." - 실행 쿼리: ".$query."\n", FILE_APPEND);
               $up = mysqli_query($dbcon, $query);
               if(!$up) {
                  throw new Exception(mysqli_error($dbcon));
               }
               file_put_contents($log_file, date('Y-m-d H:i:s')." - out_order 테이블 업데이트 성공\n", FILE_APPEND);
            } else {
               file_put_contents($log_file, date('Y-m-d H:i:s')." - out_order_idx가 없어서 업데이트 건너뜀\n", FILE_APPEND);
            }
         } else {
            file_put_contents($log_file, date('Y-m-d H:i:s')." - 레코드를 찾을 수 없어 업데이트 건너뜀\n", FILE_APPEND);
         }
      } catch(Exception $e) {
         file_put_contents($log_file, date('Y-m-d H:i:s')." - out_order 테이블 업데이트 오류: ".$e->getMessage()."\n", FILE_APPEND);
         error_json(0, "out_order 테이블 업데이트 오류: ".$e->getMessage());
         exit;
      }
   }
   /**  다른 테이블 동시 insert 하는 경우 끝 */
}

try {
   $up_num = mysqli_affected_rows($dbcon);
   file_put_contents($log_file, date('Y-m-d H:i:s')." - 영향 받은 행 수: ".$up_num."\n", FILE_APPEND);
   
   if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
      //
   } else { //쿼리실패
      throw new Exception("영향 받은 행이 없습니다.");
   }
} catch(Exception $e) {
   file_put_contents($log_file, date('Y-m-d H:i:s')." - 최종 오류: ".$e->getMessage()."\n", FILE_APPEND);
   error_json(0, "오류입니다: ".$e->getMessage());
   exit;
}

$result = array();
$result['status'] = 1;
$result['data'] = 1;

file_put_contents($log_file, date('Y-m-d H:i:s')." - 처리 완료, 응답: ".json_encode($result)."\n", FILE_APPEND);
echo json_encode($result);