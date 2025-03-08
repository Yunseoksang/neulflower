<?
include($_SERVER['DOCUMENT_ROOT'].'/lib/url_setting.php');
//error_reporting(E_ALL^E_NOTICE);
//ini_set('display_errors', 1);
ini_set('display_errors', '0');





if(isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

session_set_cookie_params(0, "/"); // 세션쿠키가 적용되는 위치 (특별한 경우가 없다면 일반적으로 홈디렉토리 루트경로인 / 를 설정합니다.)
ini_set("session.cookie_domain", "neulflower.kr");
//session_start();


if (isset($_COOKIE["admin_info"])) {
    $admin_info = json_decode($_COOKIE['admin_info'], true);
}

if (isset($_COOKIE["manager_info"])) {
    $manager_info = json_decode($_COOKIE['manager_info'], true);
}


/*
if($admin_info['admin_id'] != ""){


    $sel_check = mysqli_query($dbcon,"select a.*,pm_super, pm_fullfillment, pm_sangjo, pm_flower, pm_consulting, pm_statistics, storage_super, t_storage_super, storage_fullfillment, t_storage_fullfillment, storage_sangjo, t_storage_sangjo, storage_flower, t_storage_flower, storage_consulting, t_storage_consulting, storage_statistics, t_storage_statistics from ".$db_admin.".admin_list a

    left join ".$db_admin.".admin_permission b 
    on a.admin_idx=b.admin_idx

    where admin_id='".$admin_info['admin_id']."' ") or die(mysqli_error($dbcon));
    $sel_check_num = mysqli_num_rows($sel_check);

    if($sel_check_num > 0){
       $data_check=mysqli_fetch_assoc($sel_check);
       setcookie('admin_info', json_encode($data_check), time()+360*24); //admin 정보 전체  => 사용할때는 $data = json_decode($_COOKIE['admin_info'], true);
    }



}
*/



$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";

if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){
	//echo "Mobile";
    $this_agent = "mobile";
}else{
	//echo "PC";
    $this_agent = "pc";
}




$writeday = date("Y-m-d H:i:s",time());
$writedate = date("Y-m-d H:i:s",time());
$todate = date("Y-m-d",time());


function get_header($field) {
    $headers = headers_list();
    foreach ($headers as $header) {
        list($key, $value) = preg_split('/:\s*/', $header);
        if ($key == $field)
            return $value;
    }
}



function error_exit($msg){
    $out = array();
    $out['status'] = 0;
    $out['msg'] = $msg;
    $result = json_encode($out);
    echo $result;
 }


function error_json($code,$msg){
   $out = array();
   $out['status'] = 0;
   $out['error']['code'] = $code;
   $out['msg'] = $msg;
   $result = json_encode($out);
   echo $result;
}

function success_exit($msg){
	$out = array();
	$out['status'] = 1;
	$out['msg'] = $msg;
	$result = json_encode($out);
	echo $result;
 }
 function success_json(){
	$out = array();
	$out['status'] = 1;
	$out['data']['result'] = true;
	$result = json_encode($out);
	echo $result;
 }
 




function admin_check_ajax(){
   global $admin_info;
   if(!$admin_info['admin_uuid']){
		$msg = array('code' => '-1', 'msg' => "다시 로그인 해주세요");
		echo json_encode($msg); 
		exit;
   }
}
function admin_check(){
    global $admin_info;

   if(!$admin_info['admin_uuid']){
		exit;
   }
}

function manager_check_ajax(){
    global $manager_info;
    if(!$manager_info['manager_id']){
         $msg = array('code' => '-1', 'msg' => "다시 로그인 해주세요");
         echo json_encode($msg); 
         exit;
    }
 }

function manager_check(){
    global $manager_info;

   if(!$manager_info['manager_id']){
		exit;
   }
}


function guidv4($data)
{
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}



//json_encode 하면 한글디코딩해야 정상으로 보이기 때문에, 이 함수를 이용하면 한글로 바로 보인다.
//php 5.4 버전에서는 json_array($array,JSON_UNESCAPED_UNICODE) 로 해결 할 수 있다
function my_json_encode($arr)
{
        //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
        array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });
        return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
 
}


function json_error($error_code,$error_msg){

    $error_msg = iconv("EUC-KR","UTF-8", $error_msg); //한글깨짐 및 null값 리턴 방지 처리.
    $error_msg = urlencode($error_msg);    //한글깨짐 및 null값 리턴 방지 처리.


	$result = array();
	$result['status'] = 0;
	$result['error']['error_code']=$error_code;
	$result['msg']=$error_msg;

	$json_result = json_encode($out);
    echo urldecode($json_result);    //한글깨짐 및 null값 리턴 방지 처리.

}








function get_curl($url,$param){  //$param = "'name':'gildong'";
	
	$param =str_replace("'","\"",$param);



	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "{".$param."}",
	CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json"
	),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);


	$arr = json_decode($response, true);
	return $arr;



	/* 참고: php 서버에서 curl json 파싱하기
	$object = json_decode(file_get_contents("php://input"));
	$rData = get_object_vars($object);

	// $result = array();
	// $result['status'] = 1;
	// $result['data'] = $rData;

	echo json_encode($rData);
	*/

}







  //"a/b/c/d" 형태를 => select 박스의 options 항목을 보여줄 배열형태로 반환 [["a","a"],["b","b"]]
  function make_options_array($arr,$base_index=null){ //$base_index 는 기본으로 선택되어 보여질 옵션명
	$ex = explode("/",$arr);
	
    $return_arr = [];
    for ($i=0;$i<count($ex);$i++ )
    {
        
       $ex_arr = explode(":",$ex[$i]); //"1:a/2:b/3:c" 형태인경우 value 값 별도 지정.
       if(count($ex_arr) == 1){
            $value = $ex[$i];
            $text = $ex[$i];
       }else if(count($ex_arr) == 2){
            $value = $ex_arr[0];
            $text = $ex_arr[1];
       }else if(count($ex_arr) == 3){
            $value = $ex_arr[0];
            $text = $ex_arr[1];
            $condition = $ex_arr[2];
       }

       if($base_index !== null && $base_index == $i){ //base_index 번호에 해당되면 selected 설정
          if(isset($condition) && $condition != ""){
              array_push($return_arr,[$value,$text,"SELECTED"]);
          }else{
              array_push($return_arr,[$value,$text]);
          }
       }else{
			if(isset($condition) && $condition != ""){
				array_push($return_arr,[$value,$text,$condition]);
			}else{
				array_push($return_arr,[$value,$text]);
			}
       }

	}

    
	
    return $return_arr; 
  }



  //selectDropDown 컬러메뉴 선언에 활용
  //  /미확인:미확인:btn-primary:devided:SELECTED/
  function make_options_array_color($arr,$base_index=null){ //$base_index 는 기본으로 선택되어 보여질 옵션명
	$ex = explode("/",$arr);
	
    $return_arr = [];
    for ($i=0;$i<count($ex);$i++ )
    {
        $value="";
        $text="";
        $color="";
        $divided="";
        $condition="";
       
       $ex_arr = explode(":",$ex[$i]); //"1:a/2:b/3:c" 형태인경우 value 값 별도 지정.


        if(count($ex_arr) == 3){
            $value = $ex_arr[0];
            $text = $ex_arr[1];
            $color = $ex_arr[2];
       }else if(count($ex_arr) == 4){
            $value = $ex_arr[0];
            $text = $ex_arr[1];
            $color = $ex_arr[2];
            $divider = $ex_arr[3];
        }else if(count($ex_arr) == 5){
            $value = $ex_arr[0];
            $text = $ex_arr[1];
            $color = $ex_arr[2];
            $divider = $ex_arr[3];

            $condition = $ex_arr[4];  //5번째배열에 selected 선언
        }

       if($condition != ""){
        array_push($return_arr,[$value,$text,$color,$divider,$condition]);

       }else if($divider != ""){
        array_push($return_arr,[$value,$text,$color,$divider]);

       }else{
        array_push($return_arr,[$value,$text,$color]);

       }


	}

    
	
    return $return_arr; 
  }


/**
 * SQL 쿼리에서 조건부로 필드를 추가하는 헬퍼 함수
 * @param string $fieldName 필드명
 * @param mixed $value 필드값
 * @return string 조건을 만족하면 "field='value'," 형식의 문자열, 아니면 빈 문자열
 */
function addConditionalField($fieldName, $value) {
    if (!empty($value)) {
        return "$fieldName='".mysqli_real_escape_string($GLOBALS['dbcon'], $value)."', ";
    }
    return '';
}



?>