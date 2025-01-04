<?php

$ccq1 = "select a.*,b.current_count as sum_current_count,c.sum_out_count from product a
left join (

    select * from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out where storage_idx='".$admin_info['storage_sangjo']."' group by product_idx)
) b
on a.product_idx=b.product_idx 

left join (
    select *,sum(out_count) as sum_out_count from in_out where io_status='이동출고완료' and to_storage_idx='".$admin_info['storage_sangjo']."' group by product_idx
) c
on a.product_idx=c.product_idx


where a.category_idx=4 ";

$ccq3 = "  and b.current_count > 0 ";


$ccq2 = " order by (b.current_count+c.sum_out_count) desc,a.product_name ";


$base_input_product_query = $ccq1.$ccq2;

$base_output_product_query = $ccq1.$ccq3.$ccq2;



$ddq1 = "select a.*,b.sum_current_count,c.sum_out_count from product a
left join (
    select sum(current_count) as sum_current_count,product_idx from (
    select * from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out group by storage_idx, product_idx)) x group by product_idx
) b
on a.product_idx=b.product_idx 

left join (
  select sum(sum_out_c) as sum_out_count,product_idx from (
    select *,sum(out_count) as sum_out_c from in_out where io_status='이동출고완료' group by storage_idx, product_idx ) y group by product_idx
) c
on a.product_idx=c.product_idx
where a.category_idx=4 ";

$ddq3 =  " and b.sum_current_count > 0 ";

$ddq2 = " order by (b.sum_current_count+c.sum_out_count) desc,a.product_name ";



$total_input_product_query = $ddq1.$ddq2;
$total_output_product_query =  $ddq1.$ddq3.$ddq2;

if($arr['storage_idx'] == "0"){
    $base_input_product_query = $total_input_product_query;
    $base_output_product_query = $total_output_product_query;
    
}






?>