<?php

/*
$additional_sql = "
    select sum(current_count) as sum_current_count from (
    select current_count from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out where product_idx='".$data['product_idx']."' group by storage_idx, product_idx)  
    ) x 
    "
;
*/


$additional_sql = "
    
    select sum(current_count) as sum_current_count from storage_safe where product_idx='".$data['product_idx']."' 
    
    "
;

$sel_addtional = mysqli_query($dbcon, $additional_sql) or die(mysqli_error($dbcon));
$sel_addtional_num = mysqli_num_rows($sel_addtional);

if ($sel_addtional_num > 0) {
    $data_addtional = mysqli_fetch_assoc($sel_addtional);
    $data['sum_current_count'] = $data_addtional['sum_current_count'];
}

?>