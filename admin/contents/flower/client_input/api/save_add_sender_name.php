<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


if($_POST['mode'] == ""  || $_POST['consulting_idx'] == "" ){

    $result = array();
    $result['status'] = 0;
    $result['msg']="필수값이 없습니다.";

    echo json_encode($result);
    exit;
}



if($_POST['mode'] == "add"){


    if( $_POST['sender_name'] == "" ){

        $result = array();
        $result['status'] = 0;
        $result['msg']="필수값이 없습니다.";
    
        echo json_encode($result);
        exit;
    }
    

   $in = mysqli_query($dbcon, "insert ignore into ".$db_flower.".client_flower_sender
   set
   consulting_idx='".$_POST['consulting_idx']."',
   sender_name='".$_POST['sender_name']."'
    ") or die(mysqli_error($dbcon));
   $in_id = mysqli_insert_id($dbcon);

   if($in_id){//쿼리성공
        
        $sel = mysqli_query($dbcon, "select * from ".$db_flower.".client_flower_sender where client_flower_sender_idx='".$in_id."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
           $data = mysqli_fetch_assoc($sel);

        }

        $result = array();
        $result['status'] = 1;
        $result['data'] = $data;

        $result['msg']="저장되었습니다.";

        echo json_encode($result);
        exit;
   }else{//쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg']="저장 실패";

        echo json_encode($result);
        exit;
   }
   
}else if($_POST['mode'] == "del"){

    if( $_POST['client_flower_sender_idx'] == "" ){

        $result = array();
        $result['status'] = 0;
        $result['msg']="필수값이 없습니다.";
    
        echo json_encode($result);
        exit;
    }
    

    $del = mysqli_query($dbcon, "delete from ".$db_flower.".client_flower_sender where client_flower_sender_idx='".$_POST['client_flower_sender_idx']."' ") or die(mysqli_error($dbcon));
    $del_num = mysqli_affected_rows($dbcon);
    if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        $result = array();
        $result['status'] = 1;
        $result['msg']="삭제되었습니다.";

        echo json_encode($result);
        exit;
    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg']="삭제실패";
    
        echo json_encode($result);
        exit;
    }


}




