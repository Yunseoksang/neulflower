<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');



if($_POST['admin_id'] == "" || $_POST['hp'] == ""){
    	$msg = array('code' => '10', 'msg' => "아이디 정보가 누락되었습니다.");
		echo json_encode($msg); 
		exit;
}


$hp = str_replace("-","",$_POST['hp']);


$sel = mysqli_query($dbcon, "select * from ".$db_admin.".admin_list where admin_id='".$_POST['admin_id']."' and admin_hp='".$hp."'   ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
   $data = mysqli_fetch_assoc($sel);

   if($data['admin_status'] != "승인"){
        $msg = array('code' => '11', 'msg' => "승인된 관리자만 비빌번호 변경이 가능합니다");
        echo json_encode($msg); 
        exit;

   }

   if($data['admin_pw'] != null){
        $msg = array('code' => '12', 'msg' => "담당자에게 비빌번호 초기화를 먼저 요청해주세요.");
        echo json_encode($msg); 
        exit;

    }


	
    require_once $_SERVER["DOCUMENT_ROOT"].'/lib/password_bcrypt.php'; //패스워드 암호화. php 5.5이상. 
    $pw_hash = password_hash($_POST['pw'], PASSWORD_BCRYPT);

    $up = mysqli_query($dbcon, "update ".$db_admin.".admin_list set admin_pw='".$pw_hash."'  where admin_idx='".$data['admin_idx']."' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        $msg = array('code' => '1', 'msg' => "성공");
        echo json_encode($msg); 
        exit;

    }else{
        //쿼리실패
        $msg = array('code' => '14', 'msg' => "쿼리 실행 중 중 오류입니다. Error 14");
        echo json_encode($msg); 
        exit;
    }
    
    
    


   
   
   

}else{

       	$msg = array('code' => '11', 'msg' => "일치하는 회원정보가 없습니다.");
		echo json_encode($msg); 
		exit;
}







?>