<?
require_once '../lib/DB_Connect_client.php'; //DB 접속
require_once '../lib/password_bcrypt.php'; //패스워드 암호화. php 5.5이상. [ 15.08.17 윤석상 추가]

//$dbcon    = $db->connect();
//require_once('../lib/lib_api.php');



$sel = mysqli_query($dbcon,"select * from consulting.manager where manager_id='".$_POST['manager_id']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if($sel_num > 0){
	$data=mysqli_fetch_assoc($sel);
	if($data['manager_pw'] != null && password_verify(trim($_POST['pw']), $data['manager_pw'])) {
	//if($_POST['pw'] == "1111") {

		if($data['manager_status'] != "승인"){
			$msg = array('code' => '13', 'msg' => "담당자에게 접근권한 승인을 요청해주세요.");
			echo json_encode($msg); 
			exit;
		}


		require_once('./login/api/set_login_cookie.php'); //DB 접속

		$result = array();
		$result['code'] = 1;
		$result['msg'] = "로그인 성공";
		//$result['manager_info_type'] = gettype($_COOKIE['manager_info']);

		//$result['manager_info'] = $_COOKIE['manager_info'];

		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		exit;



	}else{
		$msg = array('code' => '12', 'msg' => "패스워드가 일치하지 않습니다.");
		echo json_encode($msg); 
		exit;
	}

	

}else{

	$msg = array('code' => '11', 'msg' => "일치하는 아이디 정보가 없습니다.");
	echo json_encode($msg); 
	exit;
	
}






?>