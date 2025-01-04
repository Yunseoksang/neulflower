<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



// mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));



// mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));

if($_POST['mode'] == "insert"){

    if($_POST['place_name'] == ""){
        error_json(0,"배송지명은 필수입니다.");
        exit;
    }
    

    $in = mysqli_query($dbcon, "insert into client_place
    set
    consulting_idx='".$_POST['consulting_idx']."',
    place_name='".$_POST['place_name']."',
    receiver_name='".$_POST['receiver_name']."',
    receiver_hp='".$_POST['receiver_hp']."',
    addr='".$_POST['addr']."',
    memo='".$_POST['memo']."',
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'

    ") or die(mysqli_error($dbcon));
    $client_place_idx = mysqli_insert_id($dbcon);
    if($client_place_idx){//쿼리성공

        $sel = mysqli_query($dbcon, "select * from client_place where client_place_idx='".$client_place_idx."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $place_info = mysqli_fetch_assoc($sel);
        }

        $result = array();
        $result['status'] = 1;
        $result['data']['place_info'] = $place_info;

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
    
    if($_POST['client_place_idx'] == ""){
        error_json(0,"idx 값은 필수입니다.");
        exit;
    }
    
    $up = mysqli_query($dbcon, "update client_place set 
    
    place_name='".$_POST['place_name']."',
    receiver_name='".$_POST['receiver_name']."',
    receiver_hp='".$_POST['receiver_hp']."',
    addr='".$_POST['addr']."',
    memo='".$_POST['memo']."',
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'
    
    where client_place_idx='".$_POST['client_place_idx']."'  ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     
        $sel = mysqli_query($dbcon, "select * from client_place where client_place_idx='".$_POST['client_place_idx']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $place_info = mysqli_fetch_assoc($sel);
        }

        $result = array();
        $result['status'] = 1;
        $result['data']['place_info'] = $place_info;

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

    if($_POST['client_place_idx'] == ""){
        error_json(0,"idx 값은 필수입니다.");
        exit;
    }
    

     $del = mysqli_query($dbcon, "delete from client_place where client_place_idx='".$_POST['client_place_idx']."'  ") or die(mysqli_error($dbcon));
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