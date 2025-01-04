<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$key = 'neulflower_key';  // 중요: 이 키는 외부에서 알려지면 안 되며, 보안을 위해 저장 또는 관리가 필요합니다.
$method = 'AES-256-CBC';  // 암호화 방식

// 별도의 salt로 사용할 initialization vector를 생성합니다.
$ivLength = openssl_cipher_iv_length($method);
$iv = openssl_random_pseudo_bytes($ivLength);
// IV를 16진수 문자열로 변환합니다.
$hexIv = bin2hex($iv); //url로 보낼때는 16진수로 변환

//echo "-------".$iv."---------";



function encrypt($data, $key, $method, $iv) {
    return base64_encode(openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv));
}

function decrypt($data, $key, $method, $iv) {
    return openssl_decrypt(base64_decode($data), $method, $key, OPENSSL_RAW_DATA, $iv);
}

//예제
// $originalData = "eDU0bpbnZN7CCJYjHS/3CLqz/eugn2jexu595teNBHUenNBL1EBS4tGU99vejCCJ";
// $encryptedData = encrypt($originalData, $key, $method, $iv);
// $decryptedData = decrypt($encryptedData, $key, $method, $iv);

// echo "Original Data: " . $originalData . "\n";
// echo "Encrypted Data: " . $encryptedData . "\n";
// echo "Decrypted Data: " . $decryptedData . "\n";




// exit;


if($_POST['mode'] == "resend"){
    $sel = mysqli_query($dbcon, "select * from consulting.client_bill where bill_idx='".$_POST['bill_idx']."' ") or die(mysqli_error($dbcon));


}else{
    $sel = mysqli_query($dbcon, "select * from consulting.client_bill where bill_idx='".$_POST['bill_idx']."' and bill_status='저장/미발송' ") or die(mysqli_error($dbcon));


}




$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $uuid = $data['uuid'];
    //$uuid = $data['unique_number'];
    $encryptedData = encrypt($uuid, $key, $method, $iv);

}else{
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "오류: 발송대상 거래명세 정보가 없습니다";

    echo json_encode($result,JSON_UNESCAPED_UNICODE);

    exit;
}









// PHPMailer 클래스 파일들 포함
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;






$ex_email = explode(",",$_POST['emails']);

for ($i=0;$i<count($ex_email);$i++ )
{

    $receiver_email = $ex_email[$i];




    $mail = new PHPMailer(true); // true parameter enables exceptions

    try {
    
    
    
    
    
        // SMTP 설정
    
        $mail->CharSet = 'UTF-8';
        //$mail->isSMTP();                                  
        $mail->Host = 'mail.neulflower.kr';  // SMTP 서버 주소
        $mail->SMTPAuth = true;                           
        $mail->Username = 'neulflower@neulflower.kr';       // SMTP username
        $mail->Password = 'triplen123';                // SMTP password
    
        // 수신자 및 발신자 정보
        $mail->setFrom('bill@neulflower.kr', '(주)늘');
        //$mail->addAddress('cheonji5@hanmail.net', '윤석일');
    
        $mail->addAddress($receiver_email, $data['t_company_name']);
        //$mail->addAddress('jefferyyun7@gmail.com', '제프리');
    
        //$mail->addAddress('triplen123@naver.com', '제프리');

        
        // 메일 내용
        $mail->isHTML(true);  
        $mail->Subject = '[(주)늘] '.$data['bill_month'].' 거래명세서 입니다';
        // $mail->Body    = '<a href="http://neulflower.kr/client/bill_view/?page=bill/print_bill&mode=approve&uid='.urlencode($encryptedData).'&salt='.urlencode($hexIv).'">'.$data['bill_month'].' 거래명세서 확인하기</a>'.'
        // <br>
        // ';
    
    
        $mail_html_content = '
        <div style="max-width:700px;min-width:280px;margin:0 auto;font-family:\'나눔고딕\',NanumGothic,\'맑은 고딕\', SDNeoGothic, SDGothicNeo, \'돋움\', \'dotum\', sans-serif;">
    
    <div style="margin:0px;">
    
    <!-- header -->
    
    <p style="margin:0;padding:20px 0 10px;font-size:0;line-height:0;border-bottom:3px solid #fbb017;">
    
    <img src="data:image/png;base64,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..."  alt="(주)늘" style="width:132px;height:auto" loading="lazy">
    
    </p>
    
    <!-- //header -->
    
    
    
    <!-- contents -->
    
    <div style="margin-top:20px;padding:40px 30px;background:#FFF3AB;text-align:center;word-break:keep-all;"> <!-- 바탕컬러 : 노랑 #FFF3AB , 파랑 #D2EBEA , 분홍 #FDE6EC , 연두 #CEF29E -->
    
    <span style="display:block;font-size:28px;color:#333;font-weight:600;line-height:44px;">이메일 거래명세서</span>
    
    <span style="display:block;margin-top:15px;color:#393208;font-size:14px;line-height:24px;">'.$data['t_company_name'].'의 '.$data['bill_month'].' 거래명세서를 보내드립니다.
    
    <br>
    <a href="http://neulflower.kr/client/bill_view/?page=bill/print_bill&mode=approve&uid='.urlencode($encryptedData).'&salt='.urlencode($hexIv).'">
    <span style="color:#662d91;font-size:16px;font-weight:600;line-height:30px;">클릭 해주세요</span>
    </span>
    </a>
    </div>
    
    
    
    <!-- //contents -->
    
    
    
    <!-- footer -->
    
    <div style="margin:50px 0 0;padding:10px 0 0;border-top:solid 2px #756f6a;">
    
    <div style="padding:30px 0 30px 26px;background:#f2f2f2;font-size:12px;line-height:20px;color:#767676;">
    
    <div style="padding:0 26px 0 0;">
    
    본 메일은 발신 전용이므로 회신하실 수 없습니다.
    
    </div>
    
    <div style="overflow:hidden;margin:17px 0 0 -4px;">
    
    <div style="float:left;width:100%;height:28px;padding:12px 0 0;margin:4px 0 0 4px;background:#fff;font-weight:600;text-align:center;color:#333;">
    
    대표번호 070-5080-3050
    
    </div>
    
    </div>
    
    </div>
    
    <table width="100%" cellpadding="0" cellspacing="0">
    
    <tbody><tr><td style="padding:27px 0 5px;font-family:\'나눔고딕\',NanumGothic,\'맑은 고딕\', SDNeoGothic, SDGothicNeo, \'돋움\', \'dotum\', sans-serif;color:#767676;font-size:12px;line-height:180%;">
    
부산광역시 북구 백양대로 1025, 229호(구포동,협진태양쇼핑프로라자) (주) 늘 대표이사 오정환<br>
    
    사업자등록번호 : <a href="#" style="font-size:12px;color:#767676;text-decoration:none" rel="noreferrer noopener" target="_blank">812-87-03546</a>   /   전화번호 : 070-5080-3050   
    
    </td></tr>
    
    <tr><td style="padding:0 0 30px;font-family:arial;color:#767676;font-size:10px;line-height:180%;">
    
    Copyright 2023 (주)늘 All Rights Reserved.
    
    </td></tr>
    
    </tbody></table>
    
    </div>
    
    
    
    <!-- //footer -->
    
    </div>
    
    </div>
        ';
    
    
        $mail->Body    = $mail_html_content;
    
        $mail->send();
    
    
        // $result = array();
        // $result['status'] = 1;
        // $result['msg'] =  "거래명세서가 발송되었습니다.";
    
        // echo json_encode($result,JSON_UNESCAPED_UNICODE);
        // exit;
    
    
        $in = mysqli_query($dbcon, "insert into consulting.client_bill_email_history
        set 
        client_bill_idx='".$_POST['bill_idx']."',
        email='".$receiver_email."',
        consulting_idx='".$data['consulting_idx']."',
        category1_idx='".$data['category1_idx']."',
        admin_name='".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));

    
    
    } catch (Exception $e) {
        //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;


    }
    
    
    

 
}


if($_POST['mode'] == "resend"){
    $result = array();
    $result['status'] = 1;
    $result['msg'] =  "거래명세서가 재발송되었습니다.";

    echo json_encode($result);
    exit;
}else{

    $up = mysqli_query($dbcon, "update consulting.client_bill  set bill_status='발송완료/수락대기',
    manager_email='".$_POST['emails']."'

    where bill_idx='".$_POST['bill_idx']."' and bill_status='저장/미발송' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        $result = array();
        $result['status'] = 1;
        $result['msg'] =  "거래명세서가 발송되었습니다.";

        echo json_encode($result);
        exit;

    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] =  "거래명세서 발송가능상태가 아닙니다.";

        echo json_encode($result);
        exit;

    }





}




?>