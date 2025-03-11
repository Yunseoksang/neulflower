<?php
// error_reporting(E_ALL^E_NOTICE);
// ini_set('display_errors', 1);


//DB 할당, 쿠키 점검, 권한 점검
include_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_setting.php'; //DB 클래스 정의

// URL에서 /admin/ 이후 부분만 추출
$full_url = $_SERVER['REQUEST_URI'];
$url = substr($full_url, strpos($full_url, '/admin/') + 7); // '/admin/'의 길이가 7이므로

// URL 확인
//echo "Current URL: " . $url . "<br>";


if(strpos($url,"sfullfillment/client") !== false ||  strpos($url,"flower/client") !== false){

	//$dbcon = $db->client_connect();
	$dbcon = $db->consulting_connect();

}else if(strpos($url,"framework") !== false  ){

	//$dbcon = $db->client_connect();
	$dbcon = $db->framework_connect();


}else if(strpos($url,"admin_list/list") !== false  ){

	// table_name에 이미 DB명이 포함되어 있는지 확인
	$table_name = isset($_REQUEST['table_name']) ? $_REQUEST['table_name'] : '';
	if (strpos($table_name, '.') === false) {
		// DB명이 포함되어 있지 않은 경우에만 admin DB 연결
		$dbcon = $db->admin_connect();
	} else {
		// DB명이 이미 포함된 경우 기본 연결 사용
		$dbcon = $db->connect();
	}

}else if(strpos($url,"statistics") !== false || strpos($url,"link/list") !== false ){
	$dbcon = $db->statistics_connect();


}else if(strpos($url,"fullfillment") !== false || strpos($url,"ffm") !== false ){
	$dbcon = $db->fullfillment_connect();

}else if(strpos($url,"consulting") !== false || strpos($url,"cnst") !== false){
	$dbcon = $db->consulting_connect();

}else if(strpos($url,"hrm") !== false){
	$dbcon = $db->hrm_connect();
}else if(strpos($url,"cmu") !== false){
	$dbcon = $db->cmu_connect();

}else if(strpos($url,"flower") !== false){ //neulflower.kr 도메인때문에 뒷자리만 비교필요

	//echo "Matched: flower condition<br>";

	$dbcon = $db->flower_connect();

}else if(strpos($url,"sj") !== false){

	//echo "Matched: sj condition<br>";

	$dbcon = $db->sj_connect();
}else if(strpos($url,"sangjo") !== false || strpos($url,"dashboard_sangjo") !== false){

	//echo "Matched: sangjo_new condition<br>";

	$dbcon = $db->sangjo_new_connect();

}else{
	//echo "Matched: default sj condition<br>";

	$dbcon = $db->connect();
}






?>
