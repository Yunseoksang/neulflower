<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();


// if($_POST['data'] == ""){
//     $result = array();
//     $result['status'] = 0;
//     $result['msg'] = "입력데이터가 없습니다.";

//     echo json_encode($result);
//     exit;
// }




//$multi_file = $_FILES["multi_file"];

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


$arr = json_decode($_POST['data'],true);

if($arr['onlyPhotoOption'] == 0){
    $up = mysqli_query($dbcon, "update out_order set receiver_name='".$arr['receiver_name']."',out_order_status='배송완료' where out_order_idx='".$arr['out_order_idx']."' ") or die(mysqli_error($dbcon));
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



$result = array();
$result['status'] = 1;
$result['data']['receiver_name']=$arr['receiver_name'] ;
$result['attachment'] = $files_arr;
$result['msg'] = "저장되었습니다";
echo json_encode($result);
exit;


