<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


/** 필수 요소 누락 체크 시작 */
if($_POST['consulting_idx'] == ""){
    error_json(0,"consulting_idx 정보가 없습니다");
    exit;
}


/*
-- 쿼리 테스트 --
select 'flower' as product_part,a.consulting_idx,b.product_idx,b.product_name,a.client_product_idx,a.client_price_sum,a.client_price,a.client_price_tax from
(select * from flower.client_product where display='on') a
left join flower.product b 
on a.product_idx=b.product_idx 
where b.display='on'

union all

select 'sangjo' as product_part,consulting_idx,product_idx,product_name,'0' as client_product_idx,'0' as client_price_sum,'0' as client_price,'0' as client_price_tax from
sangjo.product 

where display='on';
*/



$sel = mysqli_query($dbcon, "select * from consulting.consulting where consulting_idx='".$_POST['consulting_idx']."'  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
   $data = mysqli_fetch_assoc($sel);
   if($data['office_type'] == "계열사"){
      $consulting_idx = $data['head_office_consulting_idx'];

   }else{
      $consulting_idx = $_POST['consulting_idx'];
   }
}else{
    $consulting_idx = $_POST['consulting_idx'];

}



$sel = mysqli_query($dbcon,"

SELECT * FROM (
    -- 꽃 상품 정보
    SELECT 
        'flower' AS product_part,
        display_order,
        b.consulting_idx,
        a.product_idx,
        a.product_name, 
        a.options,
        b.client_product_idx,
        COALESCE(b.client_price_sum, a.product_price) AS client_price_sum,
        COALESCE(b.client_price, a.product_price) AS client_price,
        IFNULL(b.client_price_tax, 0) AS client_price_tax
    FROM flower.product a
    LEFT JOIN (
        SELECT * 
        FROM flower.client_product 
        WHERE consulting_idx = '".$consulting_idx."'
    ) b ON a.product_idx = b.product_idx

    UNION ALL


    

    SELECT 
        'sangjo' AS product_part,
        display_order,
        b.consulting_idx,
        a.product_idx,
        a.product_name,
        '' AS options,
        b.client_product_idx,
        b.client_price_sum,
        b.client_price,
        b.client_price_tax
    FROM sangjo_new.client_product b
    INNER JOIN sangjo_new.product a ON a.product_idx = b.product_idx
    WHERE b.consulting_idx = '".$consulting_idx."'
    AND b.display = 'on'
    AND a.display = 'on'

) c

order by product_part desc, CAST(display_order AS UNSIGNED)



") or die(mysqli_error($dbcon));


$sel_num = mysqli_num_rows($sel);


$product_list = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_list,$data);
    }
}




$sender_list = array();
$sel1 = mysqli_query($dbcon, "select * from ".$db_flower.".client_flower_sender where consulting_idx='".$_POST['consulting_idx']."' order by sender_name ") or die(mysqli_error($dbcon));
$sel_num1 = mysqli_num_rows($sel1);

if ($sel_num1 > 0) {
    //$data1 = mysqli_fetch_assoc($sel1);
    while($data1 = mysqli_fetch_assoc($sel1)) {
        array_push($sender_list,$data1);
    }
}



$result = array();
$result['status'] = 1;
$result['data']['product_list']=$product_list;
$result['data']['sender_list']=$sender_list;


echo json_encode($result);


?>