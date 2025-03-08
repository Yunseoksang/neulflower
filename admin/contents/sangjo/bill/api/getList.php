<?php

ini_set('display_errors', '0');


// error_reporting(E_ALL);
// ini_set("display_errors", 1);

///require_once $_SERVER["DOCUMENT_ROOT"].'/admin/contents/common/api/getList_common.php'; //DB 접속


$rData= $_REQUEST;


//print_r($rData);

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





$custom_filter = $rData['custom_filter'];

// if(!empty($rData['sval'])){
//   $rData['search']['value'] = $rData['sval'];
// }

// //검색을 했다면. sql 문을 통채로 전송받는다.
// if( !empty($rData['search_keyword']) ) {   // if there is a search parameter, $rData['search']['value'] contains search parameter
//   $sval = $rData['search_keyword'];

//   //통채로 받은 쿼리문에서 검색어 부분만 대체
//   //$sql = str_replace("'###keyword###'","'%".$sval."%' and 1=1 ",$rData['keyword_query']); //like 로 검색시
//   $sql = str_replace("'###keyword###'","'%".$sval."%' and 1=1 ",$rData['keyword_query']); //like 로 검색시 인덱스를 기본적으로 타게끔 하기 위에 앞쪽 % 는 안넣기로 함. 대신 와일드 검색을 위해서 검색창에 직접 "%검색어" 형태로 입력하면 가능. ==> 일반적인 검색시 %를 안넣기 때문에 그냥 기본 포함하기로 번복

//   $sql = str_replace("'#keyword#'","'".$sval."' and 1=1 ",$sql);  //= 로 검색시
//   $sql_search_copy = $sql;

// }


if($rData['order'][0]['column'] == 0 && $rData['order'][0]['dir'] == "desc"){ // 타이틀영역의 소팅 버튼을 안누른 상태이면
    $order_column = "company_name";
    $order_by_sort = "asc";

}else{ //각 컬럼별 소팅버튼을 누른 상태이면


  $order_column = $columns[$rData['order'][0]['column']];
  $order_by_sort = $rData['order'][0]['dir'];
}








$yyyymm = date("Ym",time());

$rows = [];

if($custom_filter != ""){
  parse_str($custom_filter, $parseArr); //key-value로 변환



    ////=======================================================================================//

  if(isset($parseArr['yyyymm']) && $parseArr['yyyymm'] > 0){  
    $yyyymm =  $parseArr['yyyymm'];
  }else{
    $yyyymm = date("Ym",time());
  }
  ////=======================================================================================//

  if(isset($parseArr['category1'])){  
    if($parseArr['category1'] == "view_all"){
      $category1_sql = "";
    }else{
      $category1_idx =  $parseArr['category1'];
      $category1_sql = " and a.category1_idx='".$category1_idx."' " ;
    }

  }else{
    $category1_sql = "";
  }
  ////=======================================================================================//

  if(isset($parseArr['payment_method'])){  
    if($parseArr['payment_method'] == "view_all" || $parseArr['payment_method'] == ""){  
       $payment_method_sql = "";
    }else if($parseArr['payment_method'] == "미지정"){
      $payment_method_sql = " and e.payment_method is null ";
    
    }else{
      $payment_method_sql = " and e.payment_method='".$parseArr['payment_method']."' ";
    }

  }else{
    $payment_method_sql = "";
  }

  ////=======================================================================================//

  if(isset($parseArr['bill_status'])){  
    if($parseArr['bill_status'] == "view_all"){  
       $bill_status_sql = "";
    }else{
      if($parseArr['bill_status'] == "미발행"){  
        $bill_status_sql = " and c.bill_status is null ";
      }else{
        $bill_status_sql = " and c.bill_status='".$parseArr['bill_status']."' ";

      }

    }

  }else{
    $bill_status_sql = "";
  }


  ////=======================================================================================//
  if(isset($parseArr['sdate'])){  
    if($parseArr['sdate'] == "전체" || $parseArr['sdate'] == ""){  
       $sdate_sql = "";
    }else if($parseArr['sdate'] == "미지정"){
      $sdate_sql = " and (e.sdate='미지정' or e.sdate is null) ";

    
    }else{

      $year = substr($yyyymm, 0, 4); // 연도를 추출
      $month = substr($yyyymm, 4, 2); // 월을 추출
      
      $last_day = date('t', strtotime("$year-$month-01")); // 달의 마지막 날짜를 구함

      if($parseArr['sdate'] == "말일"  ){  
        $sdate_sql = " and ( e.sdate='말일' or e.sdate='".$last_day."' ) ";
      }else{
        //다른 날짜인데 그날이 말일이면 "말일" 데이터도 뽑기
        if($parseArr['sdate'] == $last_day){
          $sdate_sql = " and ( e.sdate='말일' or e.sdate='".$last_day."' ) ";

        }else{
          $sdate_sql = " and e.sdate='".$parseArr['sdate']."' ";

        }

      }

    }

  }else{
    $sdate_sql = "";
  }

  ////=======================================================================================//





  ////=======================================================================================//




  // if(isset($parseArr['zero_sale'])){  //0원매출포함 여부
  //   if($parseArr['zero_sale'] == ""){  
  //      $zero_sale_sql = " having sum_total_client_price > 0 ";
  //   }else{
  //     $zero_sale_sql = " having sum_total_client_price = 0 ";
  //   }

  // }else{
  //   $zero_sale_sql = " having sum_total_client_price > 0 ";
  // }



}
  


if( !empty($rData['search_keyword']) ) {   // if there is a search parameter, $rData['search']['value'] contains search parameter

  if($rData['search_column'] == "t_company_name"){
    $search_sql = " and b.company_name like '%".$rData['search_keyword']."%' ";
  }
}


/*
$year = substr($yyyymm, 0, 4);
$month = substr($yyyymm, 4, 2);

$start_date = $year . '-' . $month . '-01 00:00:00';
$last_day_of_month = date('t', mktime(0, 0, 0, $month, 1, $year));  // 해당 월의 마지막 날짜를 구함
$end_date = $year . '-' . $month . '-' . $last_day_of_month . ' 23:59:59';

*/


// 시작 날짜
$start_date = date("Y-m-d H:i:s", strtotime($yyyymm . "01 00:00:00"));

// 마지막 날짜
$last_day_of_month = date("t", strtotime($yyyymm . "01"));  // 해당 월의 마지막 날짜를 가져옴
$end_date = date("Y-m-$last_day_of_month 23:59:59");




//list.php 에서 정의한 기본적용 필터 쿼리
if($bill_status_sql == "" && $rData['main_filter_query'] != ""){ //단계별로 보기 선택버튼 활성화 $function_process_search == "on"
  $bill_status_sql = " AND ".$rData['main_filter_query']." ";
}




//날짜 검색 적용시
if($rData['date_apply'] == "on"){ 
  if($rData['date_part'] != ""){
    $sql_where_date = " where  ".$rData['date_part']." >='".$rData['start_date']."'  AND ".$rData['date_part']." <= '".$rData['end_date']."' ";
  }
}else{
  $sql_where_date = "where ((order_date >= '".$start_date."' and order_date <= '".$end_date."' and (bill_yyyymm='".$yyyymm."' or bill_yyyymm is null )) or (bill_yyyymm = '".$yyyymm."'))";

}






/*
//목록 추출 조건
where ((order_date >= '".$start_date."' and order_date <= '".$end_date."' and (bill_yyyymm='".$yyyymm."' or bill_yyyymm is null )) or (bill_yyyymm = '".$yyyymm."'))
// 검색기간내에 발주일이 포함되어야하고, 명세서발행월이 없거나 선택한 월과 같아야함. 또는 발주일이 선택한 월과 다를경우 발행월이 선택한월과 같아야함.

//and d.io_status <> '출고취소' : 출고취소 상태인 내역은 목록에서 아예 제외
*/

$sql1_1= "

select count(*) as cnt, b.company_name,b.consulting_idx,a.bill_idx, a.category1_idx, a.t_category1_name, c.bill_month, date(c.regist_datetime) as bill_date,
c.manager_idx,c.manager_name, c.manager_email,
e.sdate, sum(IFNULL(a.total_client_price,0)) as sum_total_client_price,c.bill_status, e.payment_method,
g.manager_idx as origin_manager_idx, 




GROUP_CONCAT(DISTINCT CASE 
        WHEN g.manager_email IS NOT NULL AND g.manager_email != '' THEN g.manager_email 
        ELSE NULL 
    END ORDER BY g.manager_idx SEPARATOR ', ') AS origin_manager_email,



GROUP_CONCAT(DISTINCT CASE 
    WHEN g.manager_name IS NOT NULL AND g.manager_name != '' THEN g.manager_name 
    ELSE NULL 
END ORDER BY g.manager_idx SEPARATOR ', ') AS origin_manager_name,

GROUP_CONCAT(
        DISTINCT 
        CASE
            WHEN (g.manager_email IS NOT NULL AND g.manager_email != '') AND (g.manager_name IS NOT NULL AND g.manager_name != '') THEN CONCAT(g.manager_name, '/', g.manager_email)
            WHEN (g.manager_email IS NOT NULL AND g.manager_email != '') THEN CONCAT(g.manager_email, '/', g.manager_email)
            WHEN (g.manager_name IS NOT NULL AND g.manager_name != '') THEN CONCAT(g.manager_name, '/')
            ELSE NULL
        END
        ORDER BY g.manager_idx SEPARATOR ', '
    ) AS origin_manager_info



from 

(select * from sangjo_new.out_order_client_product 

".$sql_where_date."

) a

left join consulting.consulting b
on a.consulting_idx=b.consulting_idx 

left join consulting.client_bill c
on a.bill_idx=c.bill_idx and c.bill_part='종합물류' 

left join sangjo_new.in_out d
on a.oocp_idx=d.oocp_idx

left join sangjo_new.settlement_sdate e
on a.consulting_idx=e.consulting_idx and a.category1_idx=e.category1_idx

left join sangjo_new.settlement_manager f
on a.consulting_idx=f.consulting_idx and f.category1_idx=e.category1_idx


left join consulting.manager g
on f.manager_idx=g.manager_idx

where 1=1

and d.io_status <> '출고취소'

".$category1_sql;

//where a.oocp_status='출고지시' 



$sql1_2= " ".$search_sql.$payment_method_sql.$bill_status_sql." group by a.consulting_idx,a.category1_idx,a.bill_idx order by ".$order_column." ".$order_by_sort;

$sql1 = $sql1_1.$sdate_sql.$sql1_2;


//주문할때 out_order_idx 가 카테고리가 달라고 하나만생성됨. 이후 출고요청 처리할때 상품별로 발송창고 설정하면서 io_idx는 다르게 입력됨.
$sel = mysqli_query($dbcon, $sql1) or die(mysqli_error($dbcon));
$filtered_num = mysqli_num_rows($sel);


$num =1;
if ($filtered_num > 0) {
  //$data = mysqli_fetch_assoc($sel);
  while($data = mysqli_fetch_assoc($sel)) {
      $data['num'] = $num++;
      array_push($rows,$data);
  
  }
}


$sql2= "
select sdate, count(*) as cnt from (".$sql1_1.$sql1_2.") P
group by sdate
";

$sel2 = mysqli_query($dbcon, $sql2) or die(mysqli_error($dbcon));
$fil2_num = mysqli_num_rows($sel2);


$rows2 = [];
if ($fil2_num > 0) {
  //$data = mysqli_fetch_assoc($sel);
  while($data2 = mysqli_fetch_assoc($sel2)) {
      array_push($rows2,$data2);
  
  }
}








$result = array();
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $filtered_num;// $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;
$result['search_keyword'] = $rData['search_keyword'];

$result['sdate_spread'] = $rows2;
$result['sql1'] = $sql1;


echo json_encode($result);

?>
