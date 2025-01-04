<?
require_once '../lib/DB_Connect.php'; //DB 접속
require_once '../lib/password_bcrypt.php'; //패스워드 암호화. php 5.5이상. [ 15.08.17 윤석상 추가]

//$dbcon    = $db->connect();
//require_once('../lib/lib_api.php');

function getClientIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		// IP is from shared Internet
		return $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		// IP is passed from proxy
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		// IP address from the remote user
		return $_SERVER['REMOTE_ADDR'];
	}
}

function smartLogApi($user_idx) {
	$clientIP = getClientIP();

	$now = new DateTime();
	// YYYY-MM-DD HH:MI:SS 형태
	$dateStr = $now->format('Y-m-d H:i:s');
	// 밀리초 추가
	$microseconds = $now->format('u'); // microseconds (note: returns 6 digits)
	$milliseconds = substr($microseconds, 0, 3); // get the first 3 digits for milliseconds
	$this_time = $dateStr.".".$milliseconds;


	$param = array(
		'crtfcKey' => '$5$API$OVpZ9bo4Z/TynHb8V0dcN7ndMUqOT/y9GvU23RDJvK3',
		'logDt' => $this_time,
		'useSe' => "접속",
		'sysUser' => $user_idx,
		'conectIp' => $clientIP,
		'dataUsgqty' => "0"
	);

	$url = "https://log.smart-factory.kr/apisvc/sendLogData.json";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);

	if ($error) {
		return $error;
	} else {
		$data = json_decode($response, true);
		return $data['result'];
	}
}



$sel = mysqli_query($dbcon,"select a.*,`pm_super`, `pm_fullfillment`, `pm_sangjo`, `pm_flower`, `pm_consulting`, `pm_statistics`, `storage_super`, `t_storage_super`, `storage_fullfillment`, `t_storage_fullfillment`, `storage_sangjo`, `t_storage_sangjo`, `storage_flower`, `t_storage_flower`, `storage_consulting`, `t_storage_consulting`, `storage_statistics`, `t_storage_statistics` from ".$db_admin.".admin_list a

left join ".$db_admin.".admin_permission b 
on a.admin_idx=b.admin_idx

where admin_id='".$_POST['admin_id']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if($sel_num > 0){
	$data=mysqli_fetch_assoc($sel);
	if($data['admin_pw'] != null && password_verify(trim($_POST['pw']), $data['admin_pw'])) {
	//if($_POST['pw'] == "1111") {

		if($data['admin_status'] != "승인"){
			$msg = array('code' => '13', 'msg' => "담당자에게 접근권한 승인을 요청해주세요.");
			echo json_encode($msg); 
			exit;
		}



        //smart factory 검수용인 경우(smart_login.php) => DB_Connect.php 에서 별도의 DB 접속처리
		if(isset($_POST['mode']) && $_POST['mode'] == "smart"){
			$data['admin_mode'] = "smart";
			$data['admin_login_page'] = "smart_login.php";
		}else{
			$data['admin_mode'] = "origin";
			$data['admin_login_page'] = "index.php";

		}




		setcookie('admin_info', json_encode($data), time()+360*24); //admin 정보 전체  => 사용할때는 $data = json_decode($_COOKIE['admin_info'], true);

        

		try{

			$up = mysqli_query($dbcon, "update admin.admin_list set admin_last_login_date=now(), admin_login_count=admin_login_count+1  where admin_uuid='".$data['admin_uuid']."' ") or die(mysqli_error($dbcon));
			$up = mysqli_query($dbcon, "update s_admin.admin_list set admin_last_login_date=now(), admin_login_count=admin_login_count+1  where admin_uuid='".$data['admin_uuid']."' ") or die(mysqli_error($dbcon));

			$in = mysqli_query($dbcon, "insert into admin.admin_login_history  (admin_idx) values ('".$data['admin_idx']."')  ") or die(mysqli_error($dbcon));
			$in = mysqli_query($dbcon, "insert ignore into s_admin.admin_login_history  (admin_idx) values ('".$data['admin_idx']."')  ") or die(mysqli_error($dbcon));
			
		}catch(Exception $e) {
			//echo 'Message: ' .$e->getMessage();
		}


        if($_POST['admin_id'] != "triplen123@naver.com"){
			$smartApiResult = smartLogApi($_POST['admin_id']);
		}else{
			$smartApiResult = "";
		}


		$result = array();
		$result['code'] = 1;
		$result['msg'] = "성공";
		$result['start_page'] = $data['start_page'];
		$result['smartApiResult'] = $smartApiResult;

		//$result['admin_info_type'] = gettype($_COOKIE['admin_info']);

		//$result['admin_info'] = $_COOKIE['admin_info'];

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