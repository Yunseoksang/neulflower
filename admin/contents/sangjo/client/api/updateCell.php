<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

// mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));



// mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));

if($_POST['mode'] == "client_status"){

    $up = mysqli_query($dbcon, "update client set client_status='".$_POST['client_status']."'  where consulting_idx='".$_POST['consulting_idx']."' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
       
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "저장되었습니다.";

        echo json_encode($result);
        exit;

    }else{ //쿼리실패
       
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "변경내역이 없습니다";

        echo json_encode($result);
        exit;

    }
}else if($_POST['mode'] == "memo_status"){


    $up = mysqli_query($dbcon, "update memo set memo_status='".$_POST['memo_status']."'  where memo_idx='".$_POST['memo_idx']."' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
       
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "저장되었습니다.";

        echo json_encode($result);
        exit;

    }else{ //쿼리실패
       
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "변경내역이 없습니다";

        echo json_encode($result);
        exit;

    }


}else if($_POST['mode'] == "del"){
    $del = mysqli_query($dbcon, "delete from client where consulting_idx='".$_POST['consulting_idx']."' ") or die(mysqli_error($dbcon));
    $del_num = mysqli_affected_rows($dbcon);
    if($del_num > 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "삭제되었습니다.";

        echo json_encode($result);
        exit;

    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "삭제 실패하였습니다.";

        echo json_encode($result);
        exit;

    }

}else if($_POST['mode'] == "manager_del"){
    $del = mysqli_query($dbcon, "delete from manager where manager_idx='".$_POST['manager_idx']."' ") or die(mysqli_error($dbcon));
    $del_num = mysqli_affected_rows($dbcon);
    if($del_num > 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "삭제되었습니다.";

        echo json_encode($result);
        exit;

    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "삭제 실패하였습니다.";

        echo json_encode($result);
        exit;

    }

}


?>



