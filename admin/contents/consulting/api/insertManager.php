<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();





if($_POST['mode'] == "add"){


    /** 필수 요소 누락 체크 시작 */
    if($_POST['manager_name'] == "" && $_POST['item_in_charge'] == ""){
        error_json(0,"담당업무와 담당자 이름 중 하나는 입력해주세요.");
        exit;
    }


    if(strlen($_POST['manager_id']) > 2){
        $st_sql = "manager_status='승인',";
    }

    $in = mysqli_query($dbcon, "insert into manager set
    consulting_idx                 = '".$_POST['consulting_idx']."',
    manager_id                     = '".$_POST['manager_id']."',
    ".$st_sql."

    item_in_charge                 = '".$_POST['item_in_charge']."',
    manager_name                   = '".$_POST['manager_name']."',
    manager_department             = '".$_POST['manager_department']."',
    manager_position               = '".$_POST['manager_position']."',
    manager_email                  = '".$_POST['manager_email']."',
    manager_tel                    = '".$_POST['manager_tel']."',
    manager_hp                     = '".$_POST['manager_hp']."',
    manager_settlement_date        = '".$_POST['manager_settlement_date']."',

    admin_idx         = '".$admin_info['admin_idx']."',
    admin_name        = '".$admin_info['admin_name']."'
    
    ") or die(mysqli_error($dbcon));

    $manager_idx = mysqli_insert_id($dbcon);
    if($manager_idx){//쿼리성공



        $result = array();
        $result['status'] = 1;
        $result['data']['manager_idx'] = $manager_idx;

        $result['msg'] = "저장되었습니다";



        echo json_encode($result);
        exit;

    }else{//쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "저장실패";

        echo json_encode($result);
        exit;
    }
    





}else if($_POST['mode'] == "edit"){
    
    $up = mysqli_query($dbcon, "update manager set
    manager_id                     = '".$_POST['manager_id']."',
    item_in_charge                 = '".$_POST['item_in_charge']."',
    manager_name                   = '".$_POST['manager_name']."',
    manager_department             = '".$_POST['manager_department']."',
    manager_position               = '".$_POST['manager_position']."',
    manager_email                  = '".$_POST['manager_email']."',
    manager_tel                    = '".$_POST['manager_tel']."',
    manager_hp                     = '".$_POST['manager_hp']."',
    manager_settlement_date        = '".$_POST['manager_settlement_date']."',

    admin_idx         = '".$admin_info['admin_idx']."',
    admin_name        = '".$admin_info['admin_name']."'

    where manager_idx='".$_POST['manager_idx']."'
    
    ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.


        $result = array();
        $result['status'] = 1;
        $result['msg'] = "저장되었습니다";


        echo json_encode($result);
        exit;

        
    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "수정실패";
        echo json_encode($result);
        exit;
    }
}else if($_POST['mode'] == "del"){
   $del = mysqli_query($dbcon, "delete from  manager where manager_idx='".$_POST['manager_idx']."' ") or die(mysqli_error($dbcon));
   $del_num = mysqli_affected_rows($dbcon);
   if($del_num > 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "삭제되었습니다";
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
