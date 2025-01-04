<?php
ini_set('display_errors', '0');

$rData= $_REQUEST;

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();

if($_POST['mode'] == "approve" || $_POST['mode'] == "modify" ){



    function decrypt($data, $key, $method, $iv) {
        return openssl_decrypt(base64_decode($data), $method, $key, OPENSSL_RAW_DATA, $iv);
    }

    $key = 'neulflower_key';  // 중요: 이 키는 외부에서 알려지면 안 되며, 보안을 위해 저장 또는 관리가 필요합니다.
    $method = 'AES-256-CBC';  // 암호화 방식

    $hexIv = $_REQUEST['salt']; // URL 파라미터에서 IV 값을 받습니다.
    // 16진수 문자열을 이진 데이터로 변환합니다.
    $iv = hex2bin($hexIv);

    $decryptedData = decrypt($_REQUEST['uid'], $key, $method, $iv);

    $sql = "select *,date(regist_datetime) as rdate from consulting.client_bill where uuid='".$decryptedData."'  ";
    $sel = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);

    if ($sel_num == 0) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "유효한 데이터가 없습니다";


        echo json_encode($result,JSON_UNESCAPED_UNICODE);
        exit;

    }else{

        $bill_data = mysqli_fetch_assoc($sel);

        if($_POST['mode'] == "approve"){
            $up = mysqli_query($dbcon, "update consulting.client_bill  set bill_status='승인완료' 
            where bill_idx='".$bill_data['bill_idx']."' and bill_status='발송완료/수락대기' ") or die(mysqli_error($dbcon));

        }else if($_POST['mode'] == "modify"){
            $up = mysqli_query($dbcon, "update consulting.client_bill  set bill_status='수정요청' 
            where bill_idx='".$bill_data['bill_idx']."' and bill_status='발송완료/수락대기' ") or die(mysqli_error($dbcon));
        }

        $up_num = mysqli_affected_rows($dbcon);
        if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            $result = array();
            $result['status'] = 1;
            $result['msg'] =  "수정되었습니다.";

            echo json_encode($result);
            exit;
        }else{ //쿼리실패
            $result = array();
            $result['status'] = 0;
            $result['msg'] =  "변경내역이 없습니다.";

            echo json_encode($result);
            exit;
        }

    }




}