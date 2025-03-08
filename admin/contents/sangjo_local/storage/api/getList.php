<?php

// ini_set('display_errors', '0');


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();




$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage order by storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);


if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}





$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name from product order by product_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_arr,$data);
    }
}



$ps_list = array();

for ($i=0;$i<count($product_arr);$i++ )
{
    $arr = array();
    $arr['product_idx'] = $product_arr[$i]['product_idx'];
    $arr['product_name'] = $product_arr[$i]['product_name'];

    for ($k=0;$k<count($storage_arr);$k++)
    {
        $sel_io = mysqli_query($dbcon, "select current_count from in_out where storage_idx='".$storage_arr[$k]['storage_idx']."' and product_idx='".$product_arr[$k]['product_idx']."' order by io_idx desc limit 1 ") or die(mysqli_error($dbcon));
        $sel_io_num = mysqli_num_rows($sel_io);
        $sn = "col_".$storage_arr[$k]['storage_idx'];

        if ($sel_io_num > 0) {
            $data_io = mysqli_fetch_assoc($sel_io);

            $arr[$sn] = $data_io['current_count'];
        }else{
            $arr[$sn] = 0;
        }
    }

    array_push($ps_list,$arr);
 
}




$total_cnt = count($ps_list);
$filtered_num = count($ps_list);


//********************************************************************************************************************************************************************************** */




$result = array();
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $ps_list;
$result['multi_column_search_string'] = "";
$result['search_keyword'] = "";
$result['keyword_column_selected_index'] = "";


// $result['sql'] = $sql_join_xls;
// $result['sql_join'] = $sql_join;
// $result['keyword_query'] = $sql_search_copy;
// $result['sql_where_after'] = $sql_where_after;
// $result['time_check'] = $time_check;


echo json_encode($result,JSON_UNESCAPED_UNICODE);

