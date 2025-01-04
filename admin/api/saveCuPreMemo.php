<?

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


if($_POST['mode'] == "return"){
    if($_POST['new_return_reason'] == ""){
        error_json(0,"메모 입력값이 필요합니다.");
        exit;
    }

    $in = mysqli_query($dbcon, "insert into coupon_upload_pre_memo
    
    set 
    part='return',
    cu_memo_order=select (max(cu_memo_order)+1) from coupon_upload_pre_memo where part='return',
    memo='".$_POST['new_return_reason']."',
    admin_idx=".$admin_info['admin_idx']"

     ") or die(mysqli_error($dbcon));
    $in_id = mysqli_insert_id($dbcon);
    if($in_id){//쿼리성공
       //
    }else{//쿼리실패
        error_json(0,"쿼리 오류 입니다.");
        exit;
    }
    

}







if($_POST['new_return_reject'] == ""){
    error_json(0,"메모 입력값이 필요합니다.");
    exit;
}

$in = mysqli_query($dbcon, "insert into coupon_upload_pre_memo

set 
part='reject',
cu_memo_order=select (max(cu_memo_order)+1) from coupon_upload_pre_memo where part='reject',
memo='".$_POST['new_reject_reason']."',
admin_idx=".$admin_info['admin_idx']"

 ") or die(mysqli_error($dbcon));
$in_id = mysqli_insert_id($dbcon);
if($in_id){//쿼리성공
   //
}else{//쿼리실패
    error_json(0,"쿼리 오류 입니다.");
    exit;
}


$result = array();
$result['status'] = 1;
$result['data']['cu_memo_idx'] = $in_idx;
echo json_encode($result);



