<?php
// error_reporting(E_ALL^E_NOTICE);
// ini_set('display_errors', 1);


//DB 할당, 쿠키 점검, 권한 점검
include_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_setting.php'; //DB 클래스 정의






if(strpos($url,"sfullfillment/client") !== false ||  strpos($url,"flower/client") !== false){

	//$dbcon = $db->client_connect();
	$dbcon = $db->consulting_connect();

}else if(strpos($url,"framework") !== false  ){

	//$dbcon = $db->client_connect();
	$dbcon = $db->framework_connect();


}else if(strpos($url,"admin_list/list") !== false  ){

		//$dbcon = $db->client_connect();
		$dbcon = $db->admin_connect();

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

}else if(strpos($url,"flower") !== false){
	$dbcon = $db->flower_connect();


}else{

	$dbcon = $db->connect();
}






?>
