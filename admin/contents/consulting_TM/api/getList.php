<?php

ini_set('display_errors', '0');


// error_reporting(E_ALL);
// ini_set("display_errors", 1);


$rData= $_REQUEST;



if($_REQUEST['db_part'] == ""){
  require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속

}else{
  require_once $_SERVER["DOCUMENT_ROOT"].'/lib/'.$_REQUEST['db_part']; //DB 접속

}





require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



/*
$additional_sql = "
select group_concat(manager_a separator '<br>')  as manager_group from (
select consulting_idx, concat(manager_name,' ',manager_position,' ',manager_department,' ',manager_tel,' ',manager_hp) as manager_a from manager 
where consulting_idx='".$data['consulting_idx']."' order by manager_idx ) abc
group by consulting_idx
";
*/


$sel = mysqli_query($dbcon, "select * from consulting where part='TM' limit 3 ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

$filtered_num = $sel_num;

$rows = array();
if ($sel_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data = mysqli_fetch_assoc($sel)) {
      array_push($rows,$data);
   }
}


$result = array();
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $filtered_num;// $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;
$result['multi_column_search_string'] = $rData['multi_column_search_string'];
$result['search_keyword'] = $rData['search_keyword'];
$result['keyword_column_selected_index'] = $rData['keyword_column_selected_index'];




echo json_encode($result);

