<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


/** 필수 요소 누락 체크 시작 */
if($_POST['main_db'] == ""){
    error_json(0,"main_db를 선택해주세요");
    exit;
}

/** 필수 요소 누락 체크 시작 */
if($_POST['main_table'] == ""){
    error_json(0,"main_table를 선택해주세요");
    exit;
}





//폼 요소 중에서 key_table에 insert 되는 칼럼이 아닌경우를 제외하고 쿼리문 완성하기.
$set_sql = "";
foreach($_REQUEST as $key => $val) {

    //칼럼명이 아닌것은 제외
    if($key == "table_name"){ //칼럼목록이 아닌 테이블명 지명용도 라서 제외
        $table_name = $val;
        continue;
    }
    if($key == "key_column_name"){  //칼럼목록이 아닌 키칼럼 지명용도 라서 제외
        $key_column_name = $val;
        continue;
    }



    //칼럼명과 value 를 inset 쿼리에 세팅
    if($val != "" && $val != "undefined" && $val != null){
        if($set_sql == ""){
            $set_sql = " ".$key."='".mysqli_real_escape_string($dbcon,$val)."'";
        }else{
            $set_sql .= ", ".$key."='".mysqli_real_escape_string($dbcon,$val)."'";
        }
    }
}





//insert 문이 2개 이상 일때 트랜젝션 삽입 권장.
mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));

$in = mysqli_query($dbcon, "insert into ".$table_name."
set
".$set_sql
) or die(mysqli_error($dbcon));
$idx = mysqli_insert_id($dbcon);
if($idx){//쿼리성공
   //
}else{//쿼리실패
    mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

    error_json(0,"오류입니다.");
    exit;
}



mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




$result = array();
$result['status'] = 1;
$result['data'][$key_column_name]=$idx;

echo json_encode($result);


