<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
//manager_check_ajax();


//header('Content-Type: text/html; charset=utf-8');
//session_start();



if($_REQUEST['uid'] == ""){
   echo "접근 권한이 없습니다1.";
   exit;
}

function decrypt($data, $key, $method, $iv) {
    return openssl_decrypt(base64_decode($data), $method, $key, OPENSSL_RAW_DATA, $iv);
}

$key = 'neulflower_key';  // 중요: 이 키는 외부에서 알려지면 안 되며, 보안을 위해 저장 또는 관리가 필요합니다.
$method = 'AES-256-CBC';  // 암호화 방식


// 별도의 salt로 사용할 initialization vector를 생성합니다.
//$ivLength = openssl_cipher_iv_length($method);
//$iv = openssl_random_pseudo_bytes($ivLength);


$hexIv = $_REQUEST['salt']; // URL 파라미터에서 IV 값을 받습니다.
// 16진수 문자열을 이진 데이터로 변환합니다.
$iv = hex2bin($hexIv);

//echo "/".$_REQUEST['uid'];
//echo "/".$iv;

//echo "<br>";

$decryptedData = decrypt($_REQUEST['uid'], $key, $method, $iv);

//echo "/".$decryptedData."/";

$sql = "select *,date(regist_datetime) as rdate from consulting.client_bill where uuid='".$decryptedData."'  ";
//$sql = "select *,date(regist_datetime) as rdate  from consulting.client_bill where unique_number='".$decryptedData."'  ";

//echo $sql;




$sel = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num == 0) {
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "유효한 데이터가 없습니다";
    // $result['uid'] = $_REQUEST['uid'];
    // $result['salt'] = $iv;
    // $result['uuid'] = $decryptedData;

    echo json_encode($result,JSON_UNESCAPED_UNICODE);
    exit;

}else{

    $bill_data = mysqli_fetch_assoc($sel);
    $consulting_idx = $bill_data['consulting_idx'];
    $bill_idx = $bill_data['bill_idx'];
    $bill_part = $bill_data['bill_part'];
    $unique_number = $bill_data['unique_number'];


}





//$manager_info['consulting_idx']


if($bill_part == "종합물류"){


    // $sql_aaa = "
    // select b.oocp_idx, date(a.output_datetime) as r_date, b.client_product_idx, b.product_name, sum(b.order_count) as sum_order_count,  
    // b.client_price as price ,
    // sum(b.client_price*b.order_count) as price_sum,
    // sum(b.client_price_tax*b.order_count) as price_tax

    // from
    // (select * from fullfillment.in_out where bill_idx='".$bill_idx."' ) a

    // left join fullfillment.out_order_client_product b 
    // on a.out_order_idx=b.out_order_idx group by r_date,b.client_product_idx order by r_date
    // ";


    $sql_aaa = 
    "select  a.oocp_idx, a.order_date, a.client_product_idx, a.product_name, sum(a.order_count) as sum_order_count,  
    a.client_price as price ,
    sum(a.total_client_price) as price_sum,
    sum(a.total_client_price_tax) as price_tax,
    a.bigo,
    b.io_status,
    d.to_name
    
    from
    (select * from fullfillment.out_order_client_product where bill_idx='".$bill_idx."') a
    
    
    left join fullfillment.in_out b 
    on a.oocp_idx=b.oocp_idx 
    
    left join consulting.consulting c 
    on a.consulting_idx=c.consulting_idx
    
    
    left join fullfillment.out_order d 
    on a.out_order_idx=d.out_order_idx
    
    
    order by a.order_date";

    

}



$sel = mysqli_query($dbcon, $sql_aaa) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

$rows = array();
if ($sel_num > 0) {
    //$data = mysqli_fetch_assoc($sel);
    while($data = mysqli_fetch_assoc($sel)) {
    
        $rows[] = $data;
    }
}




$sel_con = mysqli_query($dbcon, "select * from consulting.consulting where consulting_idx='".$consulting_idx."'  ") or die(mysqli_error($dbcon));
$sel_con_num = mysqli_num_rows($sel_con);

if ($sel_con_num > 0) {
   $data_con = mysqli_fetch_assoc($sel_con);
   
}




$filtered_num = $sel_num;

$result = array();
$result['status'] = 1;
$result['draw'] = $_REQUEST['draw'];
$result['recordsTotal'] = $filtered_num;// $total_cnt;
$result['recordsFiltered'] = $filtered_num;
$result['data'] = $rows;
$result['bill_data'] = $bill_data;
$result['consulting_info'] = $data_con;
$result['yyyymm'] = $yyyymm;



//$result['sql_aaa'] = $sql_aaa;

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
