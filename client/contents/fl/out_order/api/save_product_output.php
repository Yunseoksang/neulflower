<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();




if($_POST['mode'] == "배송요청"){

    /* Start transaction */
    mysqli_begin_transaction($dbcon);

    $sel_oo = mysqli_query($dbcon, "select * from ".$db_flower.".out_order where out_order_idx='".$_POST['out_order_idx']."'
    
    ") or die(mysqli_error($dbcon));
    $sel_oo_num = mysqli_num_rows($sel_oo);
    
    if ($sel_oo_num > 0) {
        $data_oo = mysqli_fetch_assoc($sel_oo);
        if($data_oo['out_order_status'] != '접수대기'){
                mysqli_rollback($dbcon);

                $result = array();
                $result['status'] = 0;
                $result['msg']="처리실패:접수대기 상태 일때만 배송요청 가능합니다.";
        
                echo json_encode($result);
                exit;
        }
    }else{
        
        $result = array();
        $result['status'] = 0;
        $result['msg']="오류입니다 : ES001";

        echo json_encode($result);
        exit;
    }




    if($_POST['out_order_part'] == "상조"){


        $sel_output = mysqli_query($dbcon, "
            select product_idx,order_count as cnt,oocp_idx from ".$db_sj.".out_order_client_product where flower_out_order_idx='".$_POST['out_order_idx']."'


        ") or die(mysqli_error($dbcon));

        $sel_output_num = mysqli_num_rows($sel_output);

        $product_list = array();
        if ($sel_output_num > 0) {
            while($data_output = mysqli_fetch_assoc($sel_output)) {

                array_push($product_list,$data_output);
            
            }
        }

        $arr['storage_idx'] = $_POST['storage_idx'];
        $arr['mode'] = "output";
        $arr['address'] = $data_oo['address1']." ".$data_oo['address2'];
        $arr['to_name'] = $data_oo['r_name'];
        $arr['hp'] = $data_oo['r_tel'];
        $arr['admin_memo'] = $_POST['admin_memo'];
        $arr['move_check'] = $_POST['move_check'];

        $arr['admin_notice'] = $_POST['admin_notice'];
        $arr['head_officer'] = $_POST['head_officer'];
        $arr['agency_order_price'] = $_POST['agency_order_price'];




        include($_SERVER["DOCUMENT_ROOT"].'/admin/contents/sj/storage_input/api/save_product_output_common.php');


    
    
    }else if($_POST['out_order_part'] == "화훼"){


        $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set out_order_status='배송요청',
        storage_idx='".$_POST['storage_idx']."',
        admin_memo='".$_POST['admin_memo']."',
        admin_notice='".$_POST['admin_notice']."',
        head_officer='".$_POST['head_officer']."',
        agency_order_price='".$_POST['agency_order_price']."'

        
        where out_order_idx='".$_POST['out_order_idx']."' and out_order_status='접수대기' ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num > 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        //
        }else{ //쿼리실패
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="처리실패:접수대기상태 일때만 배송요청이 가능합니다.";

            echo json_encode($result);
            exit;
        }
    }


    mysqli_commit($dbcon);

    $result = array();
    $result['status'] = 1;
    $result['msg']="저장되었습니다";

    echo json_encode($result);
    exit;


}else if($_POST['mode'] == "주문취소"){


    $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set out_order_status='주문취소' where out_order_idx='".$_POST['out_order_idx']."' and out_order_status='접수대기' ") or die(mysqli_error($dbcon));
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
        $result['msg']="처리실패:접수대기 상태일때만 주문취소 가능합니다.";

        echo json_encode($result);
        exit;

    }


}

?>