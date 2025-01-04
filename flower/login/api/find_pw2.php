<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');



if(strlen($_POST['pw']) < 6){
    	$msg = array('code' => '10', 'msg' => "비밀번호는 6자리 이상이어야 합니다.");
		echo json_encode($msg); 
		exit;
}


if($_POST['uuid'] == "" || $_POST['vcode'] == ""){
        $msg = array('code' => '11', 'msg' => "접속정보가 유효하지 않습니다.");
		echo json_encode($msg); 
		exit;
}



$sel = mysqli_query($dbcon, "select * from admin_pw where admin_uuid='".$_POST['uuid']."' and verify_code='".$_POST['vcode']."' and status='1confirm' and reg_datetime > NOW() - INTERVAL 15 MINUTE  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num == 0) {
	$msg = array('code' => '12', 'msg' => "기간이 만료되었거나 유효하지 않은 접속정보입니다.");
	echo json_encode($msg); 
	exit;
}else{
   //$data = mysqli_fetch_assoc($sel);
}



	
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/password_bcrypt.php'; //패스워드 암호화. php 5.5이상. [ 15.08.17 윤석상 추가]
$pw_hash = password_hash($_POST['pw'], PASSWORD_BCRYPT);




$in = mysqli_query($dbcon, "update ".$db_admin.".admin_list set admin_pw='".$pw_hash."'  where admin_uuid='".$_POST['uuid']."' ") or die(mysqli_error($dbcon));
$up_num = mysqli_affected_rows($dbcon);
if($up_num >= 0){ //업데이트 성공

    $del = mysqli_query($dbcon, "delete from admin_pw where admin_uuid='".$_POST['uuid']."' ") or die(mysqli_error($dbcon));

	$out['code'] = "1"; 
	$out['msg'] = "비밀번호가 변경되었습니다.";
   
	echo json_encode($out);
	exit;
}else{ //쿼리실패
	$out['code'] = "13"; 
	$out['msg'] = "업데이트 실패";
  
	echo json_encode($out);
	exit;
}







?>