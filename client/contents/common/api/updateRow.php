<?




require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_client.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();


$main_table_name = $_POST['table_name'];

//삭제
if($_POST['mode'] == "delete"){
  
   $query_delete = "delete from ".$main_table_name." where ".$_POST['key_name']."='".$_POST['key_value']."' ";

   $del = mysqli_query($dbcon, $query_delete) or die(mysqli_error($dbcon));
   $del_num = mysqli_affected_rows($dbcon);
   if($del_num > 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
      //
   }else{ //쿼리실패
      error_json(0,"삭제내역이 없습니다.");
      exit;
   }

   $result = array();
   $result['status'] = 1;

   $result['data']['query_delete'] = $query_delete;

   echo json_encode($result);
   exit;

}


//수정저장하기 
$jsonData = json_decode($_REQUEST['jsonDataPHP']);
if($jsonData->mode == "update"){
   $table_name = $jsonData->table_name;
   $key_name = $jsonData->key_name;
   $key_value = $jsonData->key_value;
   $inputArray = $jsonData->inputArray;

   $set_sql = "";

   for ($i=0;$i<count($inputArray);$i++ )
   {

      $join_table_name = $inputArray[$i]->join_table_name;
      // $join_table_key_name = $inputArray[$i]->table_key_name;
      // $join_table_key_value = $inputArray[$i]->table_key_value;


      if($join_table_name == "" || $join_table_name == null){ //연계테이블의 칼럼이 아니라면 메인테이블이므로 여러칼럼 한꺼번에 업데이트

         $column_name = $inputArray[$i]->name;
         $column_value = $inputArray[$i]->value;

         if(strpos($column_name, "_temp") !== false) {  //칼럼이름에 "_temp" 문자가 포함되어 있으면 저장대상이 아님.
            continue;
         }

         if($set_sql == ""){
            if ($column_value == ""){
               $set_sql =  $column_name."=null ";
            }else{
               $set_sql =  $column_name."='".mysqli_real_escape_string($dbcon,$column_value)."' ";
            }
            
         }else{
            if ($column_value == ""){
               $set_sql .=  ",".$column_name."=null ";
            }else{
               $set_sql .=  ",".$column_name."='".mysqli_real_escape_string($dbcon,$column_value)."' ";
            }
         }


      }
   }


   if($set_sql != ""){
      //main table 의 여러변경된 칼럼을 업데이트

      $up_sql = "update ".$table_name." set ".$set_sql."
      where ".$key_name."='".$key_value."' ";

      $up = mysqli_query($dbcon,$up_sql) or die(mysqli_error($dbcon));


   }


   //메인테이블이 아닌 연계된 테이블의 업데이트 정보가 왔으면 각 테이블의 칼럼별로 update 쿼리 각기 시행
   for ($i=0;$i<count($inputArray);$i++ )
   {

      $join_table_name = $inputArray[$i]->join_table_name;

      if($join_table_name != ""){ //연계 테이블 칼럼이라면 각기 업데이트 실행

         $column_name = $inputArray[$i]->name;
         $column_value = $inputArray[$i]->value;

         $join_table_key_name = $inputArray[$i]->join_table_key_name;
         $join_table_key_value = $inputArray[$i]->join_table_key_value;

         $sql100 = "update ".$join_table_name." set ".$column_name."='".mysqli_real_escape_string($dbcon,$column_value)."'
         where ".$join_table_key_name."='".$join_table_key_value."' ";

         $up = mysqli_query($dbcon, $sql100) or die(mysqli_error($dbcon));

         $up_sql .= ";".$sql100;

      }
   }



}





$up_num = mysqli_affected_rows($dbcon);
if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
   //
}else{ //쿼리실패
   //error_json(0,"오류입니다.");


   $result = array();
   $result['status'] = 0;
   $result['msg'] = "오류입니다";

   $result['data']['query_update'] = $up_sql;
   
   
   echo json_encode($result);

   exit;
}


$result = array();
$result['status'] = 1;
$result['data']['query_update'] = $up_sql;


echo json_encode($result);