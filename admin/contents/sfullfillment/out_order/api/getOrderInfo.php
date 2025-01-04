<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


$sel = mysqli_query($dbcon, "

select a.*,b.t_company_name,b.to_place_name,b.to_address,b.to_name,b.to_hp,delivery_memo,b.total_client_price,b.total_client_price_tax,b.total_client_price_sum,d.base_storage_idx,d.t_base_storage_name,e.manager_name,b.admin_name from (
select * from out_order_client_product where oocp_idx='".$_POST['oocp_idx']."') a
left join out_order b 
on a.out_order_idx=b.out_order_idx

left join client_product c 
on a.client_product_idx=c.client_product_idx 

left join product d 
on c.product_idx=d.product_idx


left join consulting.manager e
on b.manager_idx=e.manager_idx


") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$order_info;
if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $order_info = $data;

}




$sel2 = mysqli_query($dbcon, "select attachment_idx,filename from attachment where out_order_idx='".$data['out_order_idx']."' order by attachment_idx") or die(mysqli_error($dbcon));
$sel2_num = mysqli_num_rows($sel2);

$attachment_list = array();
if($sel2_num > 0) {
    while($data2 = mysqli_fetch_assoc($sel2)){
        array_push($attachment_list,$data2);

    }
    
}





$result = array();
$result['status'] = 1;
$result['data']['order_info']=$order_info;
$result['data']['attachment_list']=$attachment_list;


echo json_encode($result);


?>