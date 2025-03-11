<?php
ini_set('display_errors', '0');

$rData= $_REQUEST;

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();



if($_POST['mode'] == "bill_month"){ //미발행된 내역의 발행월 조정, 발행제외처리, 발행된 경우에는 수정요청모드 일때인지 체크
    admin_check_ajax();


    $sel = mysqli_query($dbcon, "select * from sangjo_new.out_order_client_product where oocp_idx='".$_POST['oocp_idx']."' ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        if($data['bill_idx'] > 0){ //발행내역있을때 : 수정요청 모드일때만 허용

            $sel_bill = mysqli_query($dbcon, "select * from consulting.client_bill  where bill_idx='".$data['bill_idx']."' ") or die(mysqli_error($dbcon));
            $sel_bill_num = mysqli_num_rows($sel_bill);
            
            if ($sel_bill_num > 0) {
                $data_bill = mysqli_fetch_assoc($sel_bill);
                if($data_bill['bill_status'] != "수정요청"){ //수정요청모드가 아니면 수정불가
                    error_json(0,"이미 거래명세서가 발행된 내역이라서 수정할수 없습니다. 수정을 위해서는 해당월의 거래명세서를 먼저 폐기해주세요.");
                    exit;
                }
             
            }
            

            
        }
    }




    //연도형식이 맞는지 체크
    if (preg_match('/^(\d{4})(0[1-9]|1[0-2])$/', $_POST['bill_month'])) {
        $bill_month = $_POST['bill_month'];
        $sql = "update sangjo_new.out_order_client_product set bill_yyyymm='".$bill_month."'  where oocp_idx='".$_POST['oocp_idx']."' ";

    }else if($_POST['bill_month'] == "발행제외"){
        //    
        $sql = "update sangjo_new.out_order_client_product set bill_yyyymm='-1'  where oocp_idx='".$_POST['oocp_idx']."' ";

    }else{

        error_json(0,"거래명세서발행 년월 형식(YYYYMM)이 올바르지 않습니다.");
        exit;
    }

    $up = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
       

        $sel = mysqli_query($dbcon, "select * from sangjo_new.out_order_client_product where oocp_idx='".$_POST['oocp_idx']."'  ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        $data = mysqli_fetch_assoc($sel);
           

        $result = array();
        $result['status'] = 1;
        $result['data'] =  $data;

        echo json_encode($result);
        exit;

            
    }else{ //쿼리실패
        error_json(0,"오류입니다1.");
        exit;
    }

}else if($_POST['mode'] == "destroy"){ //폐기
    admin_check_ajax();



    $up = mysqli_query($dbcon, "update consulting.client_bill set bill_status='폐기' where bill_idx='".$_POST['bill_idx']."'
     and consulting_idx='".$_POST['consulting_idx']."' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.

        $up = mysqli_query($dbcon, "update sangjo_new.out_order_client_product set bill_idx=null  where  bill_idx='".$_POST['bill_idx']."'
        and consulting_idx='".$_POST['consulting_idx']."' ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
           
            $result = array();
            $result['status'] = 1;
            $result['data']['bill_idx'] =  $_POST['bill_idx'];
            $result['msg'] =  "폐기되었습니다.";

            echo json_encode($result);
            exit;
        }else{ //쿼리실패
            error_json(0,"오류입니다2.");
            exit;
        }

    }else{ //쿼리실패
        error_json(0,"오류입니다3.");
        exit;
    }



}else if($_POST['mode'] == "bigo"){ //비고 저장

    if($_POST['bigo'] == ""){
        $up = mysqli_query($dbcon, "update sangjo_new.out_order_client_product set bigo=null
        where oocp_idx='".$_POST['oocp_idx']."'
        ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            $result = array();
            $result['status'] = 1;
            $result['msg'] =  "저장되었습니다.";

            echo json_encode($result);
            exit;
        }else{ //쿼리실패
            error_json(0,"저장하기 실패하였습니다. 새로고침후 다시 시도해주세요");
            exit;
        }

    }else{
        $up = mysqli_query($dbcon, "update sangjo_new.out_order_client_product set bigo='".$_POST['bigo']."'
        where oocp_idx='".$_POST['oocp_idx']."'
        ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            $result = array();
            $result['status'] = 1;
            $result['msg'] =  "저장되었습니다.";

            echo json_encode($result);
            exit;
        }else{ //쿼리실패
            error_json(0,"저장하기 실패하였습니다. 새로고침후 다시 시도해주세요");
            exit;
        }
    }





}else if($_POST['mode'] == "approve" || $_POST['mode'] == "modify" ){


/*
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


*/



        if($_POST['mode'] == "approve"){
            $up = mysqli_query($dbcon, "update consulting.client_bill  set bill_status='승인완료' 
            where bill_idx='".$_POST['bill_idx']."' and bill_status='발송완료/수락대기' ") or die(mysqli_error($dbcon));

        }else if($_POST['mode'] == "modify"){
            $up = mysqli_query($dbcon, "update consulting.client_bill  set bill_status='수정요청' 
            where bill_idx='".$_POST['bill_idx']."' and bill_status='발송완료/수락대기' ") or die(mysqli_error($dbcon));
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

    //}




}