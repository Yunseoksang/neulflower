<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_fullfillment.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();




if($_POST['mode'] == "출고지시"){

    /* Start transaction */
    mysqli_begin_transaction($dbcon);

    $sel_oocp = mysqli_query($dbcon, "select b.product_idx,a.* from (select * from out_order_client_product where oocp_idx='".$_POST['oocp_idx']."') a 
    left join client_product b
    on a.client_product_idx=b.client_product_idx
    
    ") or die(mysqli_error($dbcon));
    $sel_oocp_num = mysqli_num_rows($sel_oocp);
    
    if ($sel_oocp_num > 0) {
        $data_oocp = mysqli_fetch_assoc($sel_oocp);
        if($data_oocp['oocp_status'] != '주문접수'){
                mysqli_rollback($dbcon);

                $result = array();
                $result['status'] = 0;
                $result['msg']="처리실패:주문접수상태 일때만 출고처리 가능합니다.";
        
                echo json_encode($result);
                exit;
        }
    }




    $sel = mysqli_query($dbcon, "select * from storage_safe where storage_idx='".$_POST['storage_idx']."' and product_idx='".$data_oocp['product_idx']."' limit 1") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        $current_count = $data['current_count'];
        $next_count = $current_count;

        /* 23.01.14 이종철님 요청으로 주문내역->출고지시 단계에서는 재고수량 사전체크 하지 않음.
        if($data_oocp['order_count'] > $current_count ){

            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="출고 수량 부족";
    
            echo json_encode($result);
            exit;
        }

        */

        
    }else{

        $current_count = 0;
        $next_count = $current_count;

        /*

        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="출고 수량 부족";

        echo json_encode($result);
        exit;
        */


    }





    $in = mysqli_query($dbcon, "insert into in_out 
    set
    storage_idx='".$_POST['storage_idx']."',
    out_order_idx='".$data_oocp['out_order_idx']."',
    oocp_idx='".$data_oocp['oocp_idx']."',
    client_product_idx='".$data_oocp['client_product_idx']."',
    product_idx='".$data_oocp['product_idx']."',
    client_price_sum='".$data_oocp['client_price_sum']."',
    total_client_price_sum='".$data_oocp['total_client_price_sum']."',
    current_count='".$next_count."',
    out_count='".$data_oocp['order_count']."',
    part='출고',
    io_status='미출고',
    memo='".$_POST['admin_memo']."',
    write_admin_idx='".$admin_info['admin_idx']."',
    t_write_admin_name='".$admin_info['admin_name']."',
    update_admin_idx='".$admin_info['admin_idx']."',
    t_update_admin_name='".$admin_info['admin_name']."'
    
    ") or die(mysqli_error($dbcon));

    $io_idx = mysqli_insert_id($dbcon);
    if($io_idx){//쿼리성공



        $up_num = mysqli_affected_rows($dbcon);
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
             $up = mysqli_query($dbcon, "update out_order_client_product set oocp_status='출고지시' where oocp_idx='".$_POST['oocp_idx']."' and oocp_status='주문접수' ") or die(mysqli_error($dbcon));
             $up_num = mysqli_affected_rows($dbcon);
             if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                //
             }else{ //쿼리실패
                mysqli_rollback($dbcon);

                $result = array();
                $result['status'] = 0;
                $result['msg']="처리실패:주문접수상태 일때만 출고처리 가능합니다.";
        
                echo json_encode($result);
                exit;
             }

        }else{ //쿼리실패
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="저장 실패 E21";
    
            echo json_encode($result);
            exit;
        }

        
    
    }else{//쿼리실패

        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="저장 실패 E22";

        echo json_encode($result);
        exit;
    }

    mysqli_commit($dbcon);

    $result = array();
    $result['status'] = 1;
    $result['msg']="저장되었습니다";

    echo json_encode($result);
    exit;


}else if($_POST['mode'] == "주문취소"){


    $up = mysqli_query($dbcon, "update out_order_client_product set oocp_status='주문취소' where oocp_idx='".$_POST['oocp_idx']."' and oocp_status='주문접수' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     

        $result = array();
        $result['status'] = 1;
        $result['msg']="저장되었습니다";

        echo json_encode($result);
        exit;


    }else{ //쿼리실패
        $result = array();
        $result['status'] = 0;
        $result['msg']="처리실패:주문접수 상태일때만 주문취소 가능합니다.";

        echo json_encode($result);
        exit;

    }


}

?>