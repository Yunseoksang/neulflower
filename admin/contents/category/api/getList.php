<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

$rData= $_REQUEST;

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

$cnt_column = " count(".$key_column_name.") as cnt ";
$select_column = " * ";
//$attr_key = $rData['key_column_name'];


$sql_where_filter = "  ";
$sql_where_date = "  ";


/*

if($rData['main_process_column'] != ""){ //단계별로 보기 선택버튼 활성화 $function_process_search == "on"

  $process_column = $rData['main_process_column'];
  $mm = $rData['main_process'];
  switch ($mm)
  { 


      case "":
      case "전체":
          break;
      case "신상품":
          $sql_where_filter .= " AND t_new_menu_kinds_count > 0 ";
          break;

      case "재고없음":
          $sql_where_filter .= " AND ".$process_column." = 0  ";
          break;
      case "재고있음":
          $sql_where_filter .= " AND ".$process_column." > 0  ";
          break;

      case "처리완료":
          $sql_where_filter .= " AND (".$process_column."='환불완료' or ".$process_column."='재발행' or ".$process_column."='대체상품발송' or ".$process_column."='신고접수반려' or ".$process_column."='신고취소' ) ";
          break;
      default:
          $sql_where_filter .= " AND ".$process_column."='".$mm."' ";
  }
}
*/


if($rData['multi_search_query'] != ""){ //필터적용
  $sql_where_filter .= " AND ".$rData['multi_search_query'];
}


if($rData['select_filter'] != ""){
  $sf_ex = explode("|",$rData['select_filter']);
  for ($i=0;$i<count($sf_ex);$i++ )
  {
     $sf = explode(":",$sf_ex[$i]);
     $sql_where_filter .= " AND ".$sf[0]."='".$sf[1]."' ";
  }
}


/*
if($rData['request_action'] != ""){
  $sql_where_filter .= " AND request_action='".$rData['request_action']."' ";
}


if($rData['report_part'] != ""){
  $sql_where_filter .= " AND report_part='".$rData['report_part']."' ";

}
*/


if($rData['date_apply'] == "on"){ //날짜 검색 적용시
  if($rData['date_part'] != ""){
    $sql_where_date .= " AND ".$rData['date_part']." >='".$rData['start_date']." 00:00:00' AND ".$rData['date_part']." <= '".$rData['end_date']." 23:59:59' ";
  }
}



$sql = "select ".$select_column."  FROM ".$table_name." where 1=1 ";



//검색을 했다면.
if( !empty($rData['search']['value']) ) {   // if there is a search parameter, $rData['search']['value'] contains search parameter
    $sval = $rData['search']['value'];

    //$sql = "select * from user where user_name like '%".$sval."%' ";

    $mm = $rData['search_column'];
    switch ($mm)
    {
      case "brand_idx":
      case "brand_name":
      case "brand_keyword":
      case "cf_admin_name":
        $sql = " select * from brand where ".$mm." like '%".$sval."%'  ";
        break;

      case "t_menu_count":
      case "t_brand_coupon_count":
      case "t_max_discount_percent":

        $sql = " select * from brand where ".$mm." > '".$sval."' ";
        break;

      default: 
        $sql = " select * from brand where ".$mm." like '%".$sval."%' ";
        break;
    }
}



/****** 이 아래쪽은 끝까지 별도 수정 필요 없음. */

$sel = mysqli_query($dbcon, "select ".$cnt_column." from ".$table_name) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);
$data = mysqli_fetch_assoc($sel);
$total_cnt = $data['cnt'];
$totalFiltered =$total_cnt;  // when there is no search parameter then total number rows = total number filtered rows.



$sel2=mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
$sel2_num = mysqli_num_rows($sel2);
$data2 = mysqli_fetch_assoc($sel2);
if($sel2_num > 0){
   $totalFiltered = $sel2_num;
} // 필터된 row 가 0개이면 total_count로 처리. 앞에서 처리했음.


$mm = $columns[$rData['order'][0]['column']];
switch ($mm)
{
   case "brand_name": // 다른 테이블 조인해야 하는 칼럼인경우 order after로
      $sql_order_after = " ORDER BY ". $columns[$rData['order'][0]['column']]."   ".$rData['order'][0]['dir'];
      break;

   default:
      $sql_order = " ORDER BY ". $columns[$rData['order'][0]['column']]."   ".$rData['order'][0]['dir'];

}



$sql_limit = "  LIMIT ".$rData['start']." ,".$rData['length']."   ";
/* $rData['order'][0]['column'] contains colmun index, $rData['order'][0]['dir'] contains order such as asc/desc  */	


$sql_all          = $sql.$sql_where_filter.$sql_where_sub.$sql_where_date.$sql_order;
$sql_all_no_limit = $sql.$sql_where_filter.$sql_where_sub.$sql_where_date;
if($sql_where_after == "" && $sql_order_after == ""){
  $sql_all .= $sql_limit;
}else{
  $sql_limit_after = $sql_limit;
}


//조인이 필요할때
//search와 상관없이 datatable에 보여질 다른 칼럼을 수집해와야 하는 경우 1차 쿼리(sql_all)에 추가 조인을 해줌.
  $sql_join = " select a.*,b.brand_name from (".$sql_all.") a ";

  $sql_join_plus = "";
  $sql_join_plus = " 
  left join brand b
  on a.brand_idx=b.brand_idx
  where 1=1 
  ".$sql_where_after;
  

  $sql_join .= $sql_join_plus.$sql_order_after.$sql_limit_after;



$query_result=mysqli_query($dbcon, $sql_join) or die(mysqli_error($dbcon));
$result_num = mysqli_num_rows($query_result);

$rows = array();
//$bank_transfer_idxs = array();
if ($result_num > 0) {
   while($data = mysqli_fetch_assoc($query_result)) {

      //null 값은 "" empty 값으로 출력.
      foreach($data as $key=>$val)
      {
        if($val == null){
          $data[$key] = "";
        }
      }


      //row tr 별로 index 값 출력
      $data['DT_RowAttr'][$key_column_name] = $data[$key_column_name];//
      //$data['DT_RowAttr']['category_idx'] = $data["category_idx"];//


      $rows[] = $data;

   }
}


//필터 검색시 count
$sql_join_no_limit = " select count(*) as cnt from (".$sql_all_no_limit.") a ".$sql_join_plus;
$no_limit_result=mysqli_query($dbcon, $sql_join_no_limit) or die(mysqli_error($dbcon));
$data_filter = mysqli_fetch_assoc($no_limit_result);
$filtered_num = $data_filter['cnt'];


$result = array();
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;

// $result['sql_join'] = $sql_join;
// $result['sql_all_no_limit'] = $sql_join_no_limit;


echo json_encode($result);

