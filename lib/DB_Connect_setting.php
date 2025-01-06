<?php


$db_admin = "admin";
$db_client = "client";
$db_consulting = "consulting";
$db_hrm = "hrm";

$db_flower = "flower";
$db_fullfillment = "fullfillment";
$db_sangjo = "sangjo";
$db_statistics = "statistics";
$db_framework = "framework";




require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Class.php'; //DB 클래스 정의







//권한 점검
//sfullfillment getList.php  에서 호출시 적합한 DB를 선택하기 위해
if(isset($rData['folder_name'])  && $rData['folder_name']!= ""){
	$url = $rData['folder_name'];
}else{
	$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
	$url = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}


//echo "original url:".$url."\n";
	//common 에 있는
if(strpos($url ,"contents/common/api") !== false ){
    if(isset($http_referer)){
        $url = $http_referer;

        //echo $url;
    }else{
        $url = $_SERVER['HTTP_REFERER'];
    }
}



//관리자 폴더 접근권한 체크
if((strpos($url,"admin/") !== false || strpos($url,"admin2/") !== false) && (strpos($url,"login") === false && strpos($url,"logout") === false)  ){

    if(!isset($admin_info)){

        if(strpos($url,"api/") !== false){

            $result = array();
            $result['status'] = 0;
            $result['msg'] = "다시 로그인해주세요";
            echo json_encode($result);
            exit;
            exit;
        }else{

            echo '<META http-equiv="refresh" CONTENT="0; URL=./login/">';
            exit;
        }

    }
}


//종합물류 접근권한
if(strpos($url,"sfullfillment/") !== false || strpos($url,"dashboard_sffm") !== false ){
	
    if($admin_info['pm_super'] != "종합관리자" && $admin_info['pm_fullfillment'] != "종합물류관리자" && $admin_info['pm_fullfillment'] != "종합물류창고관리자"){
        echo "Permission Error: 403";
        exit;
    }

    if(strpos($url,"sfullfillment/") !== false){
        if($admin_info['pm_super'] != "종합관리자" && $admin_info['pm_fullfillment'] != "종합물류관리자" ){
            echo "Permission Error: 403";
            exit;
        }
    }

    if(strpos($url,"sfullfillment_local/") !== false){
        if($admin_info['pm_fullfillment'] != "종합물류창고관리자"){
            echo "Permission Error: 403";
            exit;
        }
    }

//상조물류접근권한
}else if(strpos($url,"sj/") !== false || strpos($url,"dashboard_sj") !== false){
	
    if($admin_info['pm_super'] != "종합관리자" && $admin_info['pm_sangjo'] != "상조물류관리자"  && $admin_info['pm_sangjo'] != "상조물류창고관리자" ){
        echo "Permission Error: 403";
        exit;
    }

    if(strpos($url,"sj/") !== false){
        if($admin_info['pm_super'] != "종합관리자" && $admin_info['pm_sangjo'] != "상조물류관리자" ){
            echo "Permission Error: 403";
            exit;
        }
    }

    if(strpos($url,"sj_local/") !== false){
        if($admin_info['pm_sangjo'] != "상조물류창고관리자"){
            echo "Permission Error: 403";
            exit;
        }
    }
//화훼관리 접근권한
}else if(strpos($url,"flower/") !== false || strpos($url,"dashboard_flower") !== false){
    if($admin_info['pm_super'] != "종합관리자" && $admin_info['pm_flower'] != "화훼관리자" && $admin_info['pm_flower'] != "화훼지점관리자" ){
        echo "Permission Error: 403-1";
        exit;
    }

    if($admin_info['pm_flower'] == "화훼지점관리자"  && $admin_info['t_storage_flower'] == ""){
        echo "Permission Error: 403-2 : 관리자에게 화훼관리지점 입력(협력사 입력)을 요청해주세요.";
        exit;
    }

    if($admin_info['pm_flower'] == "화훼지점관리자"){ //접근가능 폴더 제한
        $urlx = explode("/",$url);
        //echo $url."<br>";
        //'statistics/flower_storage_m/list'
        //echo $urlx[4]."<br>";
        if($urlx[4] != "dashboard_flower.php" && strpos($url,"flower/out_order") === false && strpos($url,"statistics/flower_storage_m") === false ){
            echo "Permission Error: 403";
            exit;
        }
    }




//계약업체관리 접근권한
}else if(strpos($url,"consulting/") !== false || strpos($url,"dashboard_cnst") !== false){
	
    if($admin_info['pm_super'] != "종합관리자" && $admin_info['pm_consulting'] != "계약업체관리자" ){
        echo "Permission Error: 403";
        exit;
    }

//인사관리자
}else if(strpos($url,"hrm/") !== false || strpos($url,"cmu/") !== false || strpos($url,"dashboard_hrm") !== false){
	
    if($admin_info['pm_super'] != "종합관리자" && $admin_info['pm_hrm'] != "인사관리자" && $admin_info['pm_hrm'] != "총무관리자"  && $admin_info['pm_hrm'] != "인사총무관리자"){
        echo "Permission Error: 403";
        exit;
    }

}





//관리자목록 접근권한
if(strpos($url,"admin_list/") !== false  ){

    if($admin_info['pm_super'] != "종합관리자" ){
        echo "Permission Error: 1";
        exit;
    }
}


//통계 접근권한
if(strpos($url,"statistics") !== false){


}





?>