<?
ini_set('display_errors', '0');

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
//admin_check_ajax();



if($_POST['name'] == "" || $_POST['hp'] == "" || $_POST['id'] == "" || $_POST['pw'] == "" ){
		$out['code'] = "11"; 
		$out['msg'] = "필수항목이 누락되었습니다.";
    
		echo json_encode($out);
		exit;

}



$sel = mysqli_query($dbcon, "select * from ".$db_admin.".admin_list where admin_id='".$_POST['id']."'  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
		$out['code'] = "10"; 
		$out['msg'] = "아이디가 중복되었습니다.";
    
		echo json_encode($out);
		exit;
}


$hp = str_replace("-","",$_POST['hp']);

//require_once ('../../lib/KISA_SHA256.php');  //암호화
//$pw_sha256 = encrypt($_POST['pw']);


$UUID = guidv4(random_bytes(16)); //php7 부터 가능
$UUID = str_replace("-","",$UUID);

	
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/password_bcrypt.php'; //패스워드 암호화. php 5.5이상. 
$pw_hash = password_hash($_POST['pw'], PASSWORD_BCRYPT);

$in = mysqli_query($dbcon, "insert into ".$db_admin.".admin_list (admin_id,admin_uuid,admin_name,admin_hp,admin_email,admin_pw,storage_idx) values 
('".$_POST['id']."','".$UUID."','".$_POST['name']."','".$hp."','".$_POST['email']."','".$pw_hash."','".$_POST['storage_idx']."')


") or die(mysqli_error($dbcon));
$in_id = mysqli_insert_id($dbcon);
if($in_id){
   //쿼리성공
	
	//$admin_info['admin_uuid'] = $UUID;

	$out['code'] = "1"; 
	$out['msg'] = "성공";
   
	echo json_encode($out);
	exit;
}else{
  //쿼리실패
	$out['code'] = "10"; 
	$out['msg'] = "등록실패";
  
	echo json_encode($out);
	exit;
  
}

        
        






?>