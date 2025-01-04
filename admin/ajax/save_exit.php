<?
require_once '../../lib/DB_Connect.php'; //DB 접속

//session_start();
require_once('../../lib/lib_api.php');
admin_check();




    $del = mysqli_query($dbcon, "delete from partner where partner_idx=".$_POST['partner_idx']." ") or die(mysqli_error($dbcon));
    $del_num = mysqli_affected_rows($dbcon);
    if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
    		$out['code'] = "1"; 
			$out['msg'] = "성공";
		   
			echo json_encode($out);
			exit;
    }else{ //쿼리실패
      		$out['code'] = "10"; 
			$out['msg'] = "등록실패";
		  
			echo json_encode($out);
			exit;
    }
    

    








?>