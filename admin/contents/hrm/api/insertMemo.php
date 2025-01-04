<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();





if($_POST['mode'] == "add"){


    /** 필수 요소 누락 체크 시작 */
    if($_POST['memo_title'] == ""){
        error_json(0,"제목은 필수입니다.");
        exit;
    }




    $in = mysqli_query($dbcon, "insert into ".$_POST['part']." set 
    hrm_idx='".$_POST['hrm_idx']."',
    mdate='".$_POST['mdate']."',
    memo_status='".$_POST['memo_status']."',
    memo_title='".mysqli_real_escape_string($dbcon, $_POST['memo_title'])."',
    memo_text='".mysqli_real_escape_string($dbcon, urldecode($_POST['memo_text']))."',
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'
    
    ") or die(mysqli_error($dbcon));
    $in_id = mysqli_insert_id($dbcon);
    if($in_id){//쿼리성공



        $up = mysqli_query($dbcon, "update hrm set update_datetime=now() where hrm_idx='".$_POST['hrm_idx']."' ") or die(mysqli_error($dbcon));
        
        $sel = mysqli_query($dbcon, "select *,date_format(mdate,'%Y%m%d') as md,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime  from  ".$_POST['part']."  where memo_idx='".$in_id."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
           $data = mysqli_fetch_assoc($sel);

        }


        $result = array();
        $result['status'] = 1;
        $result['data']['memo']=$data;
        $result['msg'] = "저장되었습니다";



        echo json_encode($result);
        exit;

    }else{//쿼리실패
        $result = array();
        $result['status'] = 0;
        //$result['data'][$key_column_name]=$in_id;
        //$result['data']['query_insert']=$query_insert;
        $result['msg'] = "저장실패";


        echo json_encode($result);
        exit;
    }
    





}else if($_POST['mode'] == "edit"){
    $up = mysqli_query($dbcon, "update  ".$_POST['part']."  set 
    
    mdate='".$_POST['mdate']."',

    memo_status='".$_POST['memo_status']."',
    memo_title='".mysqli_real_escape_string($dbcon, $_POST['memo_title'])."',
    memo_text='".mysqli_real_escape_string($dbcon, $_POST['memo_text'])."'

    where memo_idx='".$_POST['memo_idx']."' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.

        $up = mysqli_query($dbcon, "update hrm set update_datetime=now() where hrm_idx='".$_POST['hrm_idx']."' ") or die(mysqli_error($dbcon));
        

        $sel = mysqli_query($dbcon, "select *,date_format(mdate,'%Y%m%d') as md,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime  from  ".$_POST['part']."  where memo_idx='".$_POST['memo_idx']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
           $data = mysqli_fetch_assoc($sel);
        }


        $result = array();
        $result['status'] = 1;
        $result['data']['memo']=$data;
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
   $del = mysqli_query($dbcon, "delete from  ".$_POST['part']." where memo_idx='".$_POST['memo_idx']."' ") or die(mysqli_error($dbcon));
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
