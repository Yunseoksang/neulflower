<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$main_table_name = $_POST['table_name'];

//삭제
if($_POST['mode'] == "delete"){
   $del = mysqli_query($dbcon, "delete from ".$main_table_name." where ".$_POST['key_name']."='".$_POST['key_value']."' ") or die(mysqli_error($dbcon));
   $del_num = mysqli_affected_rows($dbcon);
   if($del_num > 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
      //
   }else{ //쿼리실패
      error_json(0,"삭제내역이 없습니다.");
      exit;
   }

   $result = array();
   $result['status'] = 1;
   $result['msg'] = "삭제되었습니다";

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

   $set_sql2 = "";

   for ($i=0;$i<count($inputArray);$i++ )
   {
      $column_name = $inputArray[$i]->name;
      $column_value = $inputArray[$i]->value;


      /** 다른 테이블 칼럼이 섞여 있는 경우 제외시킨다.시작 */
      if($column_name == "to_place_name" || $column_name == "to_address"  || $column_name == "to_name"  || $column_name == "to_hp"  || $column_name == "to_phone"  || $column_name == "receiver_name"  ){

         if( $set_sql2 != ""){
            $comma2 = ",";
         }else{
            $comma2 = " ";
         }
         if ($column_value == ""){
            $set_sql2 .= $comma2.$column_name."=null ";
          }else{
            $set_sql2 .= $comma2.$column_name."='".mysqli_real_escape_string($dbcon,$column_value)."' ";
          }
         continue;

      }else{
         if( $set_sql != ""){
            $comma = ",";
         }else{
            $comma = " ";
         }
         if ($column_value == ""){
            $set_sql .= $comma.$column_name."=null ";
          }else{
            $set_sql .= $comma.$column_name."='".mysqli_real_escape_string($dbcon,$column_value)."' ";
          }
         continue;


      }
   }


   if($set_sql != ""){
      $up = mysqli_query($dbcon, "update ".$table_name." set ".$set_sql."
      where ".$key_name."='".$key_value."' ") or die(mysqli_error($dbcon));
   }

   /**  다른 테이블 동시 insert 하는 경우 시작 */
   if($set_sql2 != ""){
      $sel = mysqli_query($dbcon, "select * from out_order where ".$key_name."='".$key_value."' ") or die(mysqli_error($dbcon));
      $sel_num = mysqli_num_rows($sel);
      
      if ($sel_num > 0) {
         $data = mysqli_fetch_assoc($sel);
         $up = mysqli_query($dbcon, "update out_order set ".$set_sql2."
         where ".$key_name."='".$key_value."' ") or die(mysqli_error($dbcon));
      }else{
         
      }
   }
   /**  다른 테이블 동시 insert 하는 경우 끝 */


}



$up_num = mysqli_affected_rows($dbcon);
if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
   //
}else{ //쿼리실패
   error_json(0,"오류입니다.");
   exit;
}







$result = array();
$result['status'] = 1;
$result['data'] = 1;

echo json_encode($result);