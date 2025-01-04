<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



// mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));



// mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));

if($_POST['mode'] == "insert"){ //insert mode는 삭제되었음. 실제 사용되지 않음. edit 모드에서 insert 될수 있도록 수정됨

    if($_POST['product_idx'] == ""){
        error_json(0,"product_idx 값은 필수입니다.");
        exit;
    }


    $sel0 = mysqli_query($dbcon, "select * from ".$db_flower.".client_product where consulting_idx='".$_POST['consulting_idx']."' and product_idx='".$_POST['product_idx']."' ") or die(mysqli_error($dbcon));
    $sel0_num = mysqli_num_rows($sel0);
    
    if ($sel0_num > 0) {
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "중복된 상품입니다.";

        echo json_encode($result);
        exit;
      
    }
    

    $in = mysqli_query($dbcon, "insert into ".$db_flower.".client_product
    set
    consulting_idx='".$_POST['consulting_idx']."',
    product_idx='".$_POST['product_idx']."',
    client_price='".$_POST['client_price']."',
    client_price_tax='".$_POST['client_price_tax']."',
    client_price_sum='".$_POST['client_price_sum']."',
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'

    ") or die(mysqli_error($dbcon));
    $client_product_idx = mysqli_insert_id($dbcon);
    if($client_product_idx){//쿼리성공

        $sel = mysqli_query($dbcon, "select a.*,b.product_name from (select * from ".$db_flower.".client_product where client_product_idx='".$client_product_idx."') a 
        left join ".$db_flower.".product b
        on a.product_idx=b.product_idx 
        
        ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $product_info = mysqli_fetch_assoc($sel);
        }

        $result = array();
        $result['status'] = 1;
        $result['data']['product_info'] = $product_info;

        $result['msg'] = "저장되었습니다.";

        echo json_encode($result);
        exit;
    }else{//쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "실패하였습니다.";

        echo json_encode($result);
        exit;
    }
    


}else if($_POST['mode'] == "edit"){
    
    if($_POST['product_idx'] == "" || $_POST['product_idx'] == "undefined" || $_POST['product_idx'] == null){
        error_json(0,"idx 값은 필수입니다.");
        exit;
    }


    $sel = mysqli_query($dbcon, "select * from ".$db_flower.".client_product where consulting_idx='".$_POST['consulting_idx']."' and product_idx='".$_POST['product_idx']."' ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num == 0) { //insert
    

        $in = mysqli_query($dbcon, "insert into ".$db_flower.".client_product
        set
        consulting_idx='".$_POST['consulting_idx']."',
        product_idx='".$_POST['product_idx']."',
        client_price='".$_POST['client_price']."',
        client_price_tax='".$_POST['client_price_tax']."',
        client_price_sum='".$_POST['client_price_sum']."',
        admin_idx='".$admin_info['admin_idx']."',
        admin_name='".$admin_info['admin_name']."'
    
        ") or die(mysqli_error($dbcon));
        $client_product_idx = mysqli_insert_id($dbcon);

    }else{ //edit
        $data = mysqli_fetch_assoc($sel);
        $client_product_idx = $data['client_product_idx'];

        $up = mysqli_query($dbcon, "update ".$db_flower.".client_product set 
    
        client_price='".$_POST['client_price']."',
        client_price_tax='".$_POST['client_price_tax']."',
        client_price_sum='".$_POST['client_price_sum']."',
        admin_idx='".$admin_info['admin_idx']."',
        admin_name='".$admin_info['admin_name']."'
        
        where client_product_idx='".$data['client_product_idx']."'  ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
    }
    
    if($up_num >= 0 || $client_product_idx > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     
        $sel = mysqli_query($dbcon, "select * from ".$db_flower.".client_product where client_product_idx='".$client_product_idx."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $product_info = mysqli_fetch_assoc($sel);
        }

        $result = array();
        $result['status'] = 1;
        $result['data']['product_info'] = $product_info;

        $result['msg'] = "저장되었습니다.";

        echo json_encode($result);
        exit;
    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "실패하였습니다.";

        echo json_encode($result);
        exit;
    }


}else if($_POST['mode'] == "del"){

    if($_POST['client_product_idx'] == ""){
        error_json(0,"idx 값은 필수입니다.");
        exit;
    }
    

     $del = mysqli_query($dbcon, "delete from ".$db_flower.".client_product where client_product_idx='".$_POST['client_product_idx']."'  ") or die(mysqli_error($dbcon));
     $del_num = mysqli_affected_rows($dbcon);
     if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "삭제하였습니다.";

        echo json_encode($result);
        exit;
     }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "삭제실패";

        echo json_encode($result);
        exit;
     }


}
?>