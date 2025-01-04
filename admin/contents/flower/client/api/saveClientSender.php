<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



// mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));



// mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));

if($_POST['mode'] == "insert"){

    if($_POST['sender_name'] == ""){
        error_json(0,"보내는분은 필수입니다.");
        exit;
    }
    

    $in = mysqli_query($dbcon, "insert into flower.client_flower_sender
    set
    consulting_idx='".$_POST['consulting_idx']."',
    sender_name='".$_POST['sender_name']."',
    
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'

    ") or die(mysqli_error($dbcon));
    $client_flower_sender_idx = mysqli_insert_id($dbcon);
    if($client_flower_sender_idx){//쿼리성공

        $sel = mysqli_query($dbcon, "select * from flower.client_flower_sender where client_flower_sender_idx='".$client_flower_sender_idx."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $sender_info = mysqli_fetch_assoc($sel);
        }

        $result = array();
        $result['status'] = 1;
        $result['data']['sender_info'] = $sender_info;

        $result['msg'] = "저장되었습니다.";

        echo json_encode($result);
        exit;
    }else{//쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "실패하였습니다.";

        echo json_encode($result);
        exit;
    }
    


}else if($_POST['mode'] == "edit"){
    
    if($_POST['client_flower_sender_idx'] == ""){
        error_json(0,"idx 값은 필수입니다.");
        exit;
    }
    
    $up = mysqli_query($dbcon, "update flower.client_flower_sender set 
    
    sender_name='".$_POST['sender_name']."',
    
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'
    
    where client_flower_sender_idx='".$_POST['client_flower_sender_idx']."'  ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     
        $sel = mysqli_query($dbcon, "select * from flower.client_flower_sender where client_flower_sender_idx='".$_POST['client_flower_sender_idx']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $sender_info = mysqli_fetch_assoc($sel);
        }

        $result = array();
        $result['status'] = 1;
        $result['data']['sender_info'] = $sender_info;

        $result['msg'] = "저장되었습니다.";

        echo json_encode($result);
        exit;
    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "실패하였습니다.";

        echo json_encode($result);
        exit;
    }


}else if($_POST['mode'] == "del"){

    if($_POST['client_flower_sender_idx'] == ""){
        error_json(0,"idx 값은 필수입니다.");
        exit;
    }
    

     $del = mysqli_query($dbcon, "delete from flower.client_flower_sender where client_flower_sender_idx='".$_POST['client_flower_sender_idx']."'  ") or die(mysqli_error($dbcon));
     $del_num = mysqli_affected_rows($dbcon);
     if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "삭제하였습니다.";

        echo json_encode($result);
        exit;
     }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "삭제실패";

        echo json_encode($result);
        exit;
     }


}
?>