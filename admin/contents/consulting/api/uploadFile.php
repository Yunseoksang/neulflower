<?
// 에러 로깅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/php_errors.log');

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

// POST 데이터 로깅
error_log("Received POST data: " . print_r($_POST, true));
error_log("Received FILES data: " . print_r($_FILES, true));

function sendError($msg, $data = []) {
    $result = array(
        'status' => 0,
        'msg' => $msg,
        'debug' => $data
    );
    echo json_encode($result);
    exit;
}

if($_POST['consulting_idx'] == ""){
    sendError("ERROR: 고유번호누락");
}

// part 값 체크
if(!isset($_POST['part']) || $_POST['part'] == "" || $_POST['part'] == "전체"){
    sendError("카테고리를 선택해주세요");
}

// if($_FILES['attachFile']['name'] != ''){
//     $test = explode('.', $_FILES['attachFile']['name']);
//     $extension = end($test);    
//     $name = rand(100,999).'.'.$extension;

//     $location = $_SERVER["DOCUMENT_ROOT"].'/upload/consulting/'.$name;
//     move_uploaded_file($_FILES['attachFile']['tmp_name'], $location);

//     echo '<img src="'.$location.'" height="100" width="100" />';
// }

//  echo "### POST ###";
//  echo "<pre>";
//  print_r($_POST);
//  echo "</pre>";

//  echo "### FILE ###";
//  echo "<pre>";
//  print_r($_FILES);
//  echo "</pre>";

// exit;

//$path = $_SERVER["DOCUMENT_ROOT"]."/upload/consulting/";
 
//$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");



if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{
    error_log("Processing file upload...");
    $name = $_FILES['attachFile']['name'];
    $size = $_FILES['attachFile']['size'];
     
    if(strlen($name)) {
        error_log("File name exists: " . $name);

        $folder_idx = "/upload/consulting/".$_POST['consulting_idx'];
        $absolute_folder_idx = $_SERVER["DOCUMENT_ROOT"]. $folder_idx;

        error_log("Creating directory: " . $absolute_folder_idx);
        if (!file_exists($absolute_folder_idx)) {
            if(!mkdir($absolute_folder_idx, 0777, true)) {
                sendError("디렉토리 생성 실패", [
                    'path' => $absolute_folder_idx,
                    'error' => error_get_last()
                ]);
            }
        }


        $local_name = $folder_idx."/".$name;
        $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;

        error_log("Checking file path: " . $absolute_path);

        //중복파일 존재하면
        if(file_exists($absolute_path)){ 
            error_log("File already exists, creating new name");
            list($txt, $ext) = explode(".", $name);
            $new_name = $txt."_".time().".".$ext;
            $local_name = "/upload/consulting/".$_POST['consulting_idx']."/".$new_name;
            $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
        }

        $tmp = $_FILES['attachFile']['tmp_name'];
        error_log("Moving file from: " . $tmp . " to: " . $absolute_path);

        //파일복사
        if(move_uploaded_file($tmp, $absolute_path)) {
            error_log("File moved successfully");

            $query = "insert into consulting.attachment set consulting_idx='".mysqli_real_escape_string($dbcon, $_POST['consulting_idx'])."', part='".mysqli_real_escape_string($dbcon, $_POST['part'])."', filename='".mysqli_real_escape_string($dbcon, $local_name)."', admin_idx='".mysqli_real_escape_string($dbcon, $admin_info['admin_idx'])."', admin_name='".mysqli_real_escape_string($dbcon, $admin_info['admin_name'])."'";


            // $result = array();
            // $result['status'] = 1;
            // $result['query'] = $query;
            // echo json_encode($result);
            // exit;


            error_log("Executing query: " . $query);
            $in = mysqli_query($dbcon, $query);

            if(!$in) {
                error_log("Query failed: " . mysqli_error($dbcon));
                sendError("DB입력실패", [
                    'query' => $query,
                    'error' => mysqli_error($dbcon)
                ]);
            }

            $in_id = mysqli_insert_id($dbcon);
            if($in_id){//쿼리성공

                $sel = mysqli_query($dbcon, "select * from consulting.attachment where attachment_idx='".$in_id."' ") or die(mysqli_error($dbcon));
                $sel_num = mysqli_num_rows($sel);
                
                if ($sel_num > 0) {
                    $data = mysqli_fetch_assoc($sel);
                    //2023.5.25. 배은송님 요청으로 첨부파일 등록시 업데이트 날짜적용
                    $up = mysqli_query($dbcon, "update consulting.consulting set update_datetime=now() where consulting_idx='".$_POST['consulting_idx']."' ") or die(mysqli_error($dbcon));

                }

                $result = array();
                $result['status'] = 1;
                $result['data']=$data ;
                $result['msg'] = "저장되었습니다";
                echo json_encode($result);
                exit;
            }else{//쿼리실패
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "DB입력실패";
        
                echo json_encode($result);
                exit;
            }
            
        } else {
            error_log("File move failed");
            sendError("파일 업로드 실패", [
                'tmp' => $tmp,
                'destination' => $absolute_path,
                'error' => error_get_last()
            ]);
        }
    } else {
        error_log("No file name provided");
        sendError("첨부파일을 선택해주세요");
    }
} else {
    error_log("Invalid request method or no POST data");
    sendError("잘못된 요청입니다");
}
 
?>