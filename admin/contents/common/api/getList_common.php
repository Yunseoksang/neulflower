<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);


$rData= $_REQUEST;

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속




require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


// sorting 기능을 이용하기 위해서 실제보여지는 데이터테이블의 인덱스 번호(0부터 시작)와 해당 인덱스에 해당하는 DB 테이블 칼럼 이름을 매칭해준다.
$column_list = $rData['column_list'];
$cl_ex = explode("/",$column_list);
$columns = [];
for ($i=0;$i<count($cl_ex);$i++ )
{
   $ex = explode(":",$cl_ex[$i]);
   $arr_num = $ex[0];
   $arr_col = $ex[1];
   $columns[$arr_num] = $arr_col;
}




//중심 테이블명,key column 파라미터 전송받은값 대입
$table_name = $rData['table_name'];
$key_column_name = $rData['key_column_name'];
$folder_name = $rData['folder_name'];

$cnt_column = " count(".$key_column_name.") as cnt ";
$select_column = " * ";
//$attr_key = $rData['key_column_name'];

$start_check_time = microtime(true);
//********************************************************************************************************************************************************************************** */
$time_check .= "show columns from ".$table_name.":".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";




//칼럼별 검색을 위해 칼럼 이름이 어느 테이블에 속해있는 것인지 배열에 담기
$sql_check = "show columns from ".$table_name;



$sel = mysqli_query($dbcon, $sql_check) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$column_list = [];
$origin_column_list = []; //칼럼명을 리네임하는 경우 필요
$origin_table_list = []; //칼럼명을 리네임하는 경우 필요

if ($sel_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data = mysqli_fetch_assoc($sel)) {
      $column_list[$data['Field']] = $table_name;
      
   }
}





/*********** 필터 적용 기본 세팅 시작******************************************************************* */

$sql_where_filter = "  ";
$sql_where_date = "  ";


//list.php 에서 정의한 기본적용 필터 쿼리
if($rData['main_filter_query'] != ""){ //단계별로 보기 선택버튼 활성화 $function_process_search == "on"
  $sql_where_filter .= " AND (".$rData['main_filter_query'].") ";
}



//가운데 선택 라디오 버튼 필터 적용되었으면
if($rData['main_process_query'] != ""){ //단계별로 보기 선택버튼 활성화 $function_process_search == "on"
  $sql_where_filter .= " AND (".$rData['main_process_query'].") ";
}


//중앙 체크박스 및 기타 조합형 필터 적용되었으면
if($rData['multi_search_query'] != ""){ //필터적용
  $sql_where_filter .= " AND (".$rData['multi_search_query'].") ";
}


//각 칼럼의 TH 에 select box 형 필터가 선택되었으면.
if($rData['select_filter'] != ""){
  $sf_ex = explode("|",$rData['select_filter']);
  for ($i=0;$i<count($sf_ex);$i++ )
  {
     $sf = explode(":",$sf_ex[$i]);
     $sql_where_filter .= " AND ".$sf[0]."='".$sf[1]."' ";
  }
}


//날짜 검색 적용시
if($rData['date_apply'] == "on"){ 
  if($rData['date_part'] != ""){
    $sql_where_date .= " AND ".$rData['date_part']." >='".$rData['start_date']." 00:00:00' AND ".$rData['date_part']." <= '".$rData['end_date']." 23:59:59' ";
  }
}

/*********** 필터 적용 기본 세팅 끝******************************************************************* */



$sql = "select  ".$select_column."  FROM ".$table_name." where 1=1 ".$rData['filter_query'];


$sql_cnt = "select  count(*) as cnt  FROM ".$table_name." where 1=1 ".$rData['filter_query'];


if(!empty($rData['sval'])){
  $rData['search']['value'] = $rData['sval'];
}

// if(!empty($rData['search']['value'])){
//   $rData['search_keyword'] = $rData['search']['value'];
// }


//검색을 했다면. sql 문을 통채로 전송받는다.
if( !empty($rData['search_keyword']) ) {   // if there is a search parameter, $rData['search']['value'] contains search parameter
    $sval = $rData['search_keyword'];

    //통채로 받은 쿼리문에서 검색어 부분만 대체
    //$sql = str_replace("'###keyword###'","'%".$sval."%' and 1=1 ",$rData['keyword_query']); //like 로 검색시
    $sql = str_replace("'###keyword###'","'%".$sval."%' and 1=1 ",$rData['keyword_query']); //like 로 검색시 인덱스를 기본적으로 타게끔 하기 위에 앞쪽 % 는 안넣기로 함. 대신 와일드 검색을 위해서 검색창에 직접 "%검색어" 형태로 입력하면 가능. ==> 일반적인 검색시 %를 안넣기 때문에 그냥 기본 포함하기로 번복

    $sql = str_replace("'#keyword#'","'".$sval."' and 1=1 ",$sql);  //= 로 검색시
    $sql_search_copy = $sql;

}



$sel = mysqli_query($dbcon, "select  ".$cnt_column." from ".$table_name." where 1=1 ".$rData['filter_query']) or die(mysqli_error($dbcon));
//********************************************************************************************************************************************************************************** */
$time_check .= "select ".$cnt_column." from ".$table_name." where 1=1 ".$rData['filter_query'].":".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";



$sel_num = mysqli_num_rows($sel);
$data = mysqli_fetch_assoc($sel);
$total_cnt = $data['cnt'];
$totalFiltered =$total_cnt;  // when there is no search parameter then total number rows = total number filtered rows.



$sel2=mysqli_query($dbcon, $sql_cnt) or die(mysqli_error($dbcon));
//********************************************************************************************************************************************************************************** */
$time_check .= "첫번째 sql: ".$sql_cnt.":".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";

$sel2_num = mysqli_num_rows($sel2);
if($sel2_num > 0){
  $data2 = mysqli_fetch_assoc($sel2);

   $totalFiltered = $data2['cnt'];
   //$totalFiltered = $sel2_num;

} // 필터된 row 가 0개이면 total_count로 처리. 앞에서 처리했음.


if($rData['order'][0]['column'] == 0 && $rData['order'][0]['dir'] == "desc"){ // 타이틀영역의 소팅 버튼을 안누른 상태이면
  if($rData['order_by_column'] != ""){  //list.php 파일에서 직접 변수 $order_by_colnumn 을 입력한 경우
    $order_column = $rData['order_by_column'];
    $order_by_sort = $rData['order_by_sort'];

  }else{
    $order_column = $columns[$rData['order'][0]['column']];
    $order_by_sort = $rData['order'][0]['dir'];
  }

}else{ //각 컬럼별 소팅버튼을 누른 상태이면
  $order_column = $columns[$rData['order'][0]['column']];
  $order_by_sort = $rData['order'][0]['dir'];
}




if($rData['other_table_column'] != ""){



  $other_ex = explode("/",$rData['other_table_column']);

  $add_query1 = "";
  $add_query2 = "";
  for ($i=0;$i<count($other_ex);$i++ )
  {

      $oex = explode(":",$other_ex[$i]);


      if(count($oex) == 2){//키테이블에서 함수 사용
          $in_sub_query = str_replace("+",",",$oex[1]); //키테이블의 다른 서브쿼리 문에 그대로 가져다 쓰기 "a:cache_url(cu_img_url) as cache_img_url


          if(empty($rData['search_keyword'])){ //검색이 아니라면
            $sql = str_replace("FROM"," ,".$in_sub_query." FROM ",$sql);  //서브 쿼리에 함수이용한 칼럼 추가
          }else{ //검색이라면

            if(strpos($sql,".* from (select") !== false){ //포함되어 있으면 => 검색 쿼리가 join 형태
                //keyword_query:select coupon_upload.* from (select user_idx,user_name from user where user_name like '###keyword###' ) p  left join coupon_upload on p.user_idx=coupon_upload.user_idx   where coupon_upload.user_idx is not null 
                $sql = str_replace(".* from (select"," .*,".$in_sub_query." from (select",$sql);  //서브 쿼리에 함수이용한 칼럼 추가

            }else{ //포함안되어 있으면
                //keyword_query: select * from coupon where barcode_number like '###keyword###' 
                $sql = str_replace(" * from"," *, ".$in_sub_query." from ",$sql);
            }

          }

          //함수로 추가된 칼럼이름을 칼럼별 검색 가능하게 목록에 추가
          $zex = explode("+",$oex[1]);
          for ($k=0;$k<count($zex);$k++)
          {
             $yex = explode(" as ",$zex[$k]);

             if(count($yex) == 1){
                  $column_list[$yex[0]] = $table_name;
             }else{
                  $origin_table_list[$yex[1]] = $table_name;
                  $origin_column_list[$yex[1]] = $yex[0];
             }
          }

      }else{

          $table_ex = explode(" as ",$oex[2]); //조인할 테이블명에 별칭 사용
          if(count($table_ex) == 1){
            $table_origin = $oex[2];
            $table_nick = "";
            $table_rel_name = $table_origin;

          }else{//==2, 테이블 닉네임 있음
              $table_origin = $table_ex[0];
              $table_nick = $table_ex[1];
              $table_rel_name = $table_nick;
          }


          if(count($oex) == 4){//조인테이블A: key_idx :조인테일블B: 추출칼럼명
            $tex = explode("+",$oex[3]);
            $join_key = $oex[1];

          }else if(count($oex) == 5){//조인테이블A: A key_idx :조인테일블B: B key_idx : 추출칼럼명
            $tex = explode("+",$oex[4]);
            $join_key = $oex[3];
          }

          if(count($oex) == 4 || count($oex) == 5){
            for ($j=0;$j<count($tex);$j++ ) //추출 칼럼 개수별로 순환
            {
              $nex = explode(" as ",$tex[$j]);

              if(count($nex) == 1){ //칼럼 닉네임 없으면

                if($tex[$j] == $order_column){
                  $sql_order_after = " ORDER BY ".$order_column."   ".$order_by_sort;
                }

                //쿼리 자체에서 함수 사용여부 체크
                if(strpos($tex[$j],"(") !== false){ //sentence에 "abc" 문자열이 포함되어 있으면
                    $sp = explode("(",$tex[$j]);
                    $function_name = $sp[0];
                    $sp_after = explode(")",$sp[1]);
                    $in_column = $sp_after[0];

                    $add_query1 .= ",".$function_name."(".$table_rel_name.".".$in_column.")";

                }else{ //함수괄호문자열 포함되어 있지 않으면
                    $add_query1 .= ",".$table_rel_name.".".$tex[$j];
                    
                    $column_list[$tex[$j]] = $table_rel_name; //칼럼별 검색에 사용
                }

              }else{ //2 칼럼 닉네임 있으면
                $column_origin = $nex[0];
                $column_nick = $nex[1];

                if($column_nick == $order_column){
                  $sql_order_after = " ORDER BY ".$order_column."   ".$order_by_sort;
                }
                
                
                //쿼리 자체 함수 사용여부 체크
                if(strpos($column_origin,"(") !== false){ //sentence에 "abc" 문자열이 포함되어 있으면
                  $sp = explode("(",$column_origin);
                  $function_name = $sp[0];
                  $sp_after = explode(")",$sp[1]);
                  $in_column = $sp_after[0];
                  
                  $add_query1 .= ",".$function_name."(".$table_rel_name.".".$in_column.") ".$column_nick." ";

                }else{ //문자열 포함되어 있지 않으면
                  $add_query1 .= ",".$table_rel_name.".".$column_origin." ".$column_nick." ";

                  $origin_table_list[$column_nick] = $table_rel_name; //칼럼별 검색에 사용
                  $origin_column_list[$column_nick] = $column_origin; //칼럼별 검색에 사용

                }
              }
            }

            $add_query2 .= " left join ".$table_origin." ".$table_nick." on ".$oex[0].".".$oex[1]."=".$table_rel_name.".".$join_key." ";

          }
      }


  }
}

$sql = str_replace(";;","/",$sql);

$sql = str_replace(";",":",$sql);

$add_query1 = str_replace(";;","/",$add_query1);
$add_query1 = str_replace(";",":",$add_query1);

$add_query2 = str_replace(";;","/",$add_query2);
$add_query2 = str_replace(";",":",$add_query2);



//********************************************************************************************************************************************************************************** */
$time_check .= "중간점검:".(microtime(true) - $start_check_time);
$time_check .= "\n";



// //칼럼별 멀티 동시검색 설정이 있으면 
// for ($i=0;$i<count($rData['columns']);$i++ )
// {
//   if(!empty($ex_cols[1])){
//     $this_col_name = $rData['columns'][$i]['data'];

//     if(!empty($column_list[$this_col_name])){
//       $this_table_name = $column_list[$this_col_name];
//       if($this_table_name == $table_name){
//         $column_search_query = " and ".$this_col_name." like '%".$ex_cols[1]."%' ";
//         $sql = str_replace("1=1","1=1 ".$column_search_query,$sql);
//       }else{

//           $sql_where_after .= " and ".$this_table_name.".".$this_col_name." like '%".$ex_cols[1]."%' ";

//       }

//     }else{
//       $this_table_name = $origin_table_list[$this_col_name];
//       if($this_table_name == $table_name){
//         $column_search_query = " and ".$origin_column_list[$this_col_name]." like '%".$ex_cols[1]."%' ";
//         $sql = str_replace("1=1","1=1 ".$column_search_query,$sql);

//       }else{
//             $sql_where_after .= " and ".$this_table_name.".".$origin_column_list[$this_col_name]." like '%".$ex_cols[1]."%' ";

//       }

//     }


//   }
// }




//칼럼별 멀티 동시검색 설정이 있으면 
$multi_column_search_string = explode("/",$rData['multi_column_search_string']);

if(count($multi_column_search_string) > 0){
  for ($i=0;$i<count($multi_column_search_string);$i++ )
  {

      $ex_cols = explode("=",$multi_column_search_string[$i]);
      $this_col_name = $ex_cols[0];

      if(!empty($ex_cols[1])){

        if(!empty($ex_cols[2])){ //equal
          $search_type_add = " = '".$ex_cols[1]."' ";
        }else{
          $search_type_add = " like '%".$ex_cols[1]."%' ";

        }

        if(!empty($column_list[$this_col_name])){
          $this_table_name = $column_list[$this_col_name];
          if($this_table_name == $table_name){
            $column_search_query = " and ".$this_col_name.$search_type_add;
            $sql = str_replace("1=1","1=1 ".$column_search_query,$sql);
          }else{
              $sql_where_after .= " and ".$this_table_name.".".$this_col_name.$search_type_add;
          }

        }else{
          $this_table_name = $origin_table_list[$this_col_name];
          if($this_table_name == $table_name){
            $column_search_query = " and ".$origin_column_list[$this_col_name].$search_type_add;
            $sql = str_replace("1=1","1=1 ".$column_search_query,$sql);
          }else{
                $sql_where_after .= " and ".$this_table_name.".".$origin_column_list[$this_col_name].$search_type_add;
          }
        }

      }

  }
}




if($sql_order_after == "" && $order_column != ""){
    $sql_order       = " ORDER BY ".$order_column."   ".$order_by_sort;
    $sql_order_after = " ORDER BY ".$order_column."   ".$order_by_sort;

    //$sql_order_after = " ORDER BY ". $columns[$rData['order'][0]['column']]."   ".$order_by_sort;
}


//$mm = $columns[$rData['order'][0]['column']];
// switch ($mm)
// {
//    case "brand_name": // 다른 테이블 조인해야 하는 칼럼인경우 AFTER: order after로
//       $sql_order_after = " ORDER BY ". $columns[$rData['order'][0]['column']]."   ".$order_by_sort;

//       break;

//    default:
//       $sql_order       = " ORDER BY ". $columns[$rData['order'][0]['column']]."   ".$order_by_sort;

// }

/* $rData['order'][0]['column'] contains colmun index, $order_by_sort contains order such as asc/desc  */	

if($rData['length'] == -1){
  $sql_limit = "  ";

}else{
  $sql_limit = "  LIMIT ".$rData['start']." ,".$rData['length']."   ";

}


$sql_all          = $sql.$sql_where_filter.$sql_where_sub.$sql_where_date.$sql_order;




$sql_all_no_limit = $sql_cnt.$sql_where_filter.$sql_where_sub.$sql_where_date.$sql_order;
if($sql_where_after == "" && $sql_order_after == ""){
  $sql_all .= $sql_limit;
}else{
  $sql_limit_after = $sql_limit;
}



//********************************************************************************************************************************************************************************** */
$time_check .= "sql_join 이전:".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";



//조인이 필요할때
//search와 상관없이 datatable에 보여질 다른 칼럼을 수집해와야 하는 경우 1차 쿼리(sql_all)에 추가 조인을 해줌.

$sql_join_plus = $add_query2." where 1=1 ".$sql_where_after;
//$sql_join_xls = " select SQL_NO_CACHE a.* ".$add_query1." from (".$sql_all.") a ";

$sql_join_xls = " select  a.* ".$add_query1." from (".$sql_all.") a ";


$sql_join_xls .= $sql_join_plus.$sql_order_after; //엑셀 다운받을 쿼리 => limit 제한 없음.


$sql_join = $sql_join_xls.$sql_limit_after;

//$check_sql1=  " select  a.* ".$add_query1." from (".$sql_all.") a "."/////".$sql_join_plus."/////".$sql_order_after ;


$sql_join = str_replace("where 1=1      ORDER BY","ORDER BY",$sql_join);
$sql_join = str_replace("where 1=1  and ","where ",$sql_join);
$sql_join = str_replace("    "," ",$sql_join);
$sql_join = str_replace("   "," ",$sql_join);
$sql_join = str_replace("  "," ",$sql_join);





$query_result=mysqli_query($dbcon, $sql_join) or die(mysqli_error($dbcon));

//********************************************************************************************************************************************************************************** */
$time_check .= "sql_join 이후: $sql_join:".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";



$result_num = mysqli_num_rows($query_result);
$rows = array();
//$bank_transfer_idxs = array();
if ($result_num > 0) {
   while($data = mysqli_fetch_assoc($query_result)) {

      if($folder_name != ""){
          include('../../'.$folder_name.'/api/add_data.php');
      }
 
      //null 값은 "" empty 값으로 출력.
      foreach($data as $key=>$val)
      {
        if($val == null){
          $data[$key] = "";
        }
      }

      //row tr 별로 index 값 출력
      $data['DT_RowAttr'][$key_column_name] = $data[$key_column_name];//
      $rows[] = $data;

   }
}
 


$time_check .= "필터 count 전:".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";

//필터 검색시 count
//$sql_join_no_limit = " select count(*) as cnt from (".$sql_all_no_limit.") a ".$sql_join_plus;
$sql_join_no_limit = $sql_all_no_limit;

$no_limit_result=mysqli_query($dbcon, $sql_join_no_limit) or die(mysqli_error($dbcon));
//********************************************************************************************************************************************************************************** */
$time_check .= "필터 count 후:".$sql_join_no_limit.":".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";



$data_filter = mysqli_fetch_assoc($no_limit_result);
$filtered_num = $data_filter['cnt'];





//********************************************************************************************************************************************************************************** */
$time_check .= "마지막:".microtime(true).":".(microtime(true) - $start_check_time);
$time_check .= "\n";





$result = array();
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $filtered_num;// $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;
$result['multi_column_search_string'] = $rData['multi_column_search_string'];
$result['search_keyword'] = $rData['search_keyword'];
$result['keyword_column_selected_index'] = $rData['keyword_column_selected_index'];


//$result['other_table_column'] = $rData['other_table_column'];
 $result['sql_all_no_limit'] = $sql_join_no_limit;

//$result['sql'] = $sql_join_xls;

//$result['process'] = $rData['main_process_query'];
//$result['keyword_query'] = $add_query1."-----".$add_query2;




$result['sql_join'] = $sql_join;
//$result['check_sql1'] = $check_sql1;

// $result['keyword_query'] = $sql_search_copy;

// $result['sql_where_after'] = $sql_where_after;

$result['time_check'] = $time_check;

?>