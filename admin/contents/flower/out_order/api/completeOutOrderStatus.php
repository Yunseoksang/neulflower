<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


// if($_POST['data'] == ""){
//     $result = array();
//     $result['status'] = 0;
//     $result['msg'] = "입력데이터가 없습니다.";

//     echo json_encode($result);
//     exit;
// }


$arr = json_decode($_POST['data'],true);

//$multi_file = $_FILES["multi_file"];

if(isset($_FILES['files']['name'])){


    $countfiles = count($_FILES['files']['name']);
    $ymd = date("Y/m/d",time());

    $folder_idx = "/upload/client_flower/".$ymd."/".$arr['out_order_idx'];
    $absolute_folder_idx = $_SERVER["DOCUMENT_ROOT"]. $folder_idx;

    if (!file_exists($absolute_folder_idx)) {
        mkdir($absolute_folder_idx, 0777, true);
    }




    $local_name = $folder_idx."/".$name;
    $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;

    //중복파일 존재하면
    if(file_exists($absolute_path)){ 
        list($txt, $ext) = explode(".", $name);
        $new_name = $txt."_".time().".".$ext;
        $local_name = $folder_idx."/".$new_name;
        $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
    }




    // Upload Location
    $upload_location = $absolute_folder_idx."/";

    // To store uploaded files path
    $files_arr = array();

    // Loop all files
    for($index = 0;$index < $countfiles;$index++){

    if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){
        // File name
        $filename = $_FILES['files']['name'][$index];

        // Get extension
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Valid image extension
        $valid_ext = array("png","jpeg","jpg");

        // Check extension
        if(in_array($ext, $valid_ext)){

            // File path
            $absolute_path = $upload_location.$filename;

            //중복파일 존재하면
            if(file_exists($absolute_path)){ 
                list($txt, $ext) = explode(".", $filename);
                $new_name = $txt."_".time().".".$ext;
                $local_name = $folder_idx."/".$new_name;
                $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
            }



            // Upload file
            if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$absolute_path)){
                $files_arr[] = str_replace($_SERVER["DOCUMENT_ROOT"],"",$absolute_path); //저장된 파일주소 배열로 담기
            }
        }
    }
    }


    for($i=0;$i<count($files_arr);$i++){

        $in = mysqli_query($dbcon, "insert into attachment
        set
        out_order_idx='".$arr['out_order_idx']."',
        filename='".$files_arr[$i]."',
        attachType='photo',
        admin_idx='".$admin_info['admin_idx']."',
        admin_name='".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));
        
    }

}

// 정보 업데이트 모드 추가
if($arr['mode'] == 'update_info') {
    $update_fields = array();
    
    if($arr['receiver_name'] != '') {
        $update_fields[] = "receiver_name='".mysqli_real_escape_string($dbcon, $arr['receiver_name'])."'";
    }
    
    if($arr['received_time'] != '') {
        $update_fields[] = "received_time='".mysqli_real_escape_string($dbcon, $arr['received_time'])."'";
    }
    
    if($arr['agency_order_price'] != '') {
        // 금액에서 콤마와 원 제거하고 숫자만 추출
        $agency_order_price = preg_replace("/[^0-9]/", "", $arr['agency_order_price']);
        if(is_numeric($agency_order_price)) {
            $update_fields[] = "agency_order_price='".$agency_order_price."'";
        }
    }
    
    if(!empty($update_fields)) {
        $update_query = "UPDATE ".$db_flower.".out_order SET ".implode(", ", $update_fields)." WHERE out_order_idx='".$arr['out_order_idx']."'";
        
        error_log("Update Query: " . $update_query); // 쿼리 로깅 추가
        
        $up = mysqli_query($dbcon, $update_query);
        if(!$up) {
            $result = array();
            $result['status'] = 0;
            $result['msg'] = "업데이트 중 오류가 발생했습니다: " . mysqli_error($dbcon);
            $result['query'] = $update_query; // 디버깅용 쿼리 추가
            echo json_encode($result);
            exit;
        }
        
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "정보가 업데이트되었습니다";
        echo json_encode($result);
        exit;
    } else {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "업데이트할 내용이 없습니다";
        echo json_encode($result);
        exit;
    }
}



 if($arr['onlyPhotoOption'] == 0){
     $update_fields = array(
         "out_order_status='배송완료'"
     );

    if(isset($arr['receiver_name']) && $arr['receiver_name'] != '') {
        $update_fields[] = "receiver_name='" . mysqli_real_escape_string($dbcon, $arr['receiver_name']) . "'";
    }
    
    if(isset($arr['received_time']) && $arr['received_time'] != '') {
        $update_fields[] = "received_time='" . mysqli_real_escape_string($dbcon, $arr['received_time']) . "'";
    }
    
    if(isset($arr['agency_order_price']) && $arr['agency_order_price'] != '') {
        // 금액에서 콤마와 원 제거하고 숫자만 추출
        $agency_order_price = preg_replace("/[^0-9]/", "", $arr['agency_order_price']);
        if(is_numeric($agency_order_price)) {
            $update_fields[] = "agency_order_price='" . $agency_order_price . "'";
        }
    }

    $update_query = "UPDATE ".$db_flower.".out_order SET " . implode(", ", $update_fields) . " WHERE out_order_idx='" . $arr['out_order_idx'] . "'";
    
    error_log("Complete Order Update Query: " . $update_query); // 쿼리 로깅 추가
    
    $up = mysqli_query($dbcon, $update_query);
    if(!$up) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "업데이트 중 오류가 발생했습니다: " . mysqli_error($dbcon);
        $result['query'] = $update_query;
        echo json_encode($result);
        exit;
    }
}

$result = array();
$result['status'] = 1;
$result['data']['receiver_name'] = isset($arr['receiver_name']) ? $arr['receiver_name'] : '';
$result['data']['received_time'] = isset($arr['received_time']) ? $arr['received_time'] : '';
$result['attachment'] = isset($files_arr) ? $files_arr : array();
$result['msg'] = "저장되었습니다";
echo json_encode($result);
exit;


