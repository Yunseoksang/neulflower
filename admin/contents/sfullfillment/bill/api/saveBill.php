<?php
// ini_set('display_errors', '0');

// $rData= $_REQUEST;

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();





if($_POST['yyyymm'] == ""){
    $_POST['yyyymm'] = date("Ym",time());
}



$yyyymm = str_replace("년 ","",$_POST['yyyymm']);
$yyyymm = str_replace("월","",$yyyymm);

// 시작 날짜
$start_date = date("Y-m-d H:i:s", strtotime($yyyymm . "01 00:00:00"));

// 마지막 날짜
$last_day_of_month = date("t", strtotime($yyyymm . "01"));  // 해당 월의 마지막 날짜를 가져옴
$end_date = date("Y-m-$last_day_of_month H:i:s", strtotime($yyyymm . "01 23:59:59"));



$oocp_idx_list = explode(",",$_POST['oocp_idx_list']);

if(count($oocp_idx_list) == 0){

    $result = array();
    $result['status'] = 0;
    $result['msg'] =  "거래명세서 발행대상 거래내역이 선택되지 않았습니다.";

    echo json_encode($result);
    exit;

}

$sel = mysqli_query($dbcon, "select * from fullfillment.category1 where category1_idx='".$_POST['category1_idx']."'  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
   $data = mysqli_fetch_assoc($sel);
   $category1_name = $data['category1_name'];
   
}else{
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "카테고리 정보 오류입니다.";

    echo json_encode($result,JSON_UNESCAPED_UNICODE);

    exit;

}




$in = mysqli_query($dbcon, "insert into consulting.client_bill

set
bill_month='".$yyyymm."',
uuid=UUID(),
bill_part='종합물류',
category1_idx='".$_POST['category1_idx']."',
t_category1_name='".$category1_name."',
consulting_idx='".$_POST['consulting_idx']."',

bill_status='저장/미발송',
regist_admin_idx='".$admin_info['admin_idx']."',
update_admin_idx='".$admin_info['admin_idx']."'

") or die(mysqli_error($dbcon));
$bill_idx = mysqli_insert_id($dbcon);

$bill_success_num = 0;
if($bill_idx){//쿼리성공
   
    for ($i=0;$i<count($oocp_idx_list);$i++ )
    {
        $oocp_idx = intval($oocp_idx_list[$i]);


        $sql_up = "update fullfillment.out_order_client_product set bill_idx=".$bill_idx.",bill_yyyymm='".$yyyymm."'

        where oocp_idx=".$oocp_idx." ";
    
        $up = mysqli_query($dbcon, $sql_up) or die(mysqli_error($dbcon));
    
    
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
           $bill_success_num++;
    
        }

    }


    if($bill_success_num > 0){
        $result = array();
        $result['status'] = 1;
        $result['data']['bill_idx'] =  $bill_idx;
        $result['msg'] =  "저장되었습니다.";
    
        echo json_encode($result);
        exit;

    }else{

        $sel_check = mysqli_query($dbcon, "select * from fullfillment.out_order_client_product where bill_idx='".$bill_idx."'  ") or die(mysqli_error($dbcon));
        $sel_check_num = mysqli_num_rows($sel_check);
        
        if ($sel_check_num == 0) {

            $del = mysqli_query($dbcon, "delete from consulting.client_bill where bill_idx='".$bill_idx."' ") or die(mysqli_error($dbcon));
            $result = array();
            $result['status'] = 0;
            $result['msg'] =  "선택목록중 거래명세서 발행가능한 거래내역이 없습니다.";
        
            echo json_encode($result);
            exit;
         
        }else{
            // out_order_client_product 의 업데이트 내역은 없는데, bill_status='수정요청' 에서 '저장/미발송' 으로 업데이트 된 경우.
        }


    }


}else{//쿼리실패
    $result = array();
    $result['status'] = 0;
    $result['msg'] =  "거래명세서 발행에 실패하였습니다.ERROR_CODE:3001 ";

    echo json_encode($result);
    exit;
}






/*

//발송하기
$sel_m = mysqli_query($dbcon, "

select b.* from (
select * from fullfillment.settlement_sdate where consulting_idx='".$_POST['consulting_idx']."'
and category1_idx='".$_POST['category1_idx']."' ) a 

left join consulting.manager b 
on a.manager_idx=b.manager_idx 



") or die(mysqli_error($dbcon));
$selm_num = mysqli_num_rows($sel_m);

if ($selm_num > 0) {
   $manager_info = mysqli_fetch_assoc($sel_m);
   $mname = $manager_info['manager_name']." ".$manager_info['manager_position'];
}else{
    $manager_info = array();
    $mname = "";
}

manager_idx='".$manager_info['manager_idx']."',
manager_name='".$mname."',
manager_email='".$manager_info['manager_email']."',

*/



