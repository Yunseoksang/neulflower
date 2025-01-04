<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


if($_POST['mode'] == "save"){

   
   $in = mysqli_query($dbcon, "insert into sms_template
   set
   template_title = '".mysqli_real_escape_string($dbcon, $_POST['template_title'])."',
   template_subject = '".mysqli_real_escape_string($dbcon, $_POST['template_subject'])."',
   template_content = '".mysqli_real_escape_string($dbcon, str_replace("$$$$$","&",$_POST['template_content']))."',
   admin_idx = '".$admin_info['admin_idx']."',
   admin_name = '".$admin_info['admin_name']."'
   ") or die(mysqli_error($dbcon));
   $in_id = mysqli_insert_id($dbcon);
   if($in_id){//쿼리성공

      $result = array();
      $result['status'] = 1;
      $result['data']['template_idx'] = $in_id;
      $result['data']['template_title'] =$_POST['template_title'];
      echo json_encode($result);
      exit;

   }else{//쿼리실패
    error_exit("저장실패");
    exit;
   }
   

}else if($_POST['mode'] == "edit"){
   $up = mysqli_query($dbcon, "update sms_template set 
   
   template_subject = '".mysqli_real_escape_string($dbcon, $_POST['template_subject'])."',
   template_content = '".mysqli_real_escape_string($dbcon, str_replace("$$$$$","&",$_POST['template_content']))."',
   up_admin_idx = '".$admin_info['admin_idx']."',
   up_admin_name = '".$admin_info['admin_name']."'

   where template_idx='".$_POST['template_idx']."' ") or die(mysqli_error($dbcon));
   $up_num = mysqli_affected_rows($dbcon);
   if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
    success_exit("저장되었습니다");
    exit;
   }else{//쿼리실패
    error_exit("실패");
    exit;
   }

}else if($_POST['mode'] == "del"){
   $up = mysqli_query($dbcon, "update sms_template set 
   
   display=0,
   del_admin_idx = '".$admin_info['admin_idx']."',
   del_admin_name = '".$admin_info['admin_name']."'

   where template_idx='".$_POST['template_idx']."' ") or die(mysqli_error($dbcon));
   $up_num = mysqli_affected_rows($dbcon);
   if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
    success_exit("삭제되었습니다");
    exit;
   }else{//쿼리실패
    error_exit("실패");
    exit;
   }
}else if($_POST['mode'] == "default"){

   $up = mysqli_query($dbcon, "update sms_template set default_selected=0
    ") or die(mysqli_error($dbcon));

   $up = mysqli_query($dbcon, "update sms_template set 
   
   default_selected=1,   
   up_admin_idx = '".$admin_info['admin_idx']."',
   up_admin_name = '".$admin_info['admin_name']."'

   where template_idx='".$_POST['template_idx']."' ") or die(mysqli_error($dbcon));
   $up_num = mysqli_affected_rows($dbcon);
   if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
    success_exit("기본 템플릿이 재설정되었습니다.");
    exit;
   }else{//쿼리실패
    error_exit("실패");
    exit;
   }
}
