<?php
//save_product_output.php 애서 include.
//그리고 화훼관리에서 주문내역화면에서 발송요청 처리할때도 include => flower/out_order/api/save_product_output.php 에서


for ($i=0;$i<count($product_list);$i++ )
{

    //$sel = mysqli_query($dbcon, "select * from in_out where storage_idx='".$arr['storage_idx']."' and product_idx='".$product_list[$i]['product_idx']."' order by io_idx desc limit 1") or die(mysqli_error($dbcon));
    $sel = mysqli_query($dbcon, "select * from ".$db_sangjo.".storage_safe where storage_idx='".$arr['storage_idx']."' and product_idx='".$product_list[$i]['product_idx']."' ") or die(mysqli_error($dbcon));

    
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        $current_count = $data['current_count'];
        
    }else{
        $current_count = 0;

        // mysqli_rollback($dbcon);

        // $result = array();
        // $result['status'] = 0;
        // $result['msg']="최초입고후 출고요청 가능합니다.";

        // echo json_encode($result);
        // exit;

    }


    


    if($arr['mode'] == "output"){
        $next_count = $current_count;
        $io_status = "미출고";

    }else if($arr['mode'] == "out_complete"){
        $next_count = $current_count - $product_list[$i]['cnt'];


        if($next_count < 0){

            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="재고량이 0개 미만일수 없습니다.";

            echo json_encode($result);
            exit;
            
        }

        $io_status = "출고완료";

    }

    //화훼쪽에서 접수받을때
    $oocp_idx_sql = "";
    if(isset($product_list[$i]['oocp_idx']) && $product_list[$i]['oocp_idx'] != ""){
       $oocp_idx_sql = "oocp_idx='".$product_list[$i]['oocp_idx']."',";
    }
    //


    $sel_oocp = mysqli_query($dbcon, "select * from ".$db_sangjo.".out_order where oocp_idx='".$product_list[$i]['oocp_idx']."'  ") or die(mysqli_error($dbcon));
    $sel_oocp_num = mysqli_num_rows($sel_oocp);
    
    if ($sel_sel_oocp_num > 0) {

        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="중복접수된 값이 이미 있습니다.";

        echo json_encode($result);
        exit;
    }



    $in = mysqli_query($dbcon, "insert into ".$db_sangjo.".out_order
    set 
    ".$oocp_idx_sql."
    storage_idx='".$arr['storage_idx']."',
    to_address='".$arr['address']."',
    to_name='".$arr['to_name']."',
    to_hp='".$arr['hp']."'
    
    
    ") or die(mysqli_error($dbcon));

    $out_order_idx = mysqli_insert_id($dbcon);
    if($out_order_idx){//쿼리성공


        //        to_place_name='".$arr['to_place_name']."',

        $additional_sql = "";
        if(isset($arr['admin_notice']) && $arr['admin_notice'] != ""){
            $additional_sql = "admin_notice='".$arr['admin_notice']."',";
        }
        if(isset($arr['head_officer']) && $arr['head_officer'] != ""){
            $additional_sql .= "head_officer='".$arr['head_officer']."',";
        }
        if(isset($arr['agency_order_price']) && $arr['agency_order_price'] != ""){
            $additional_sql .= "agency_order_price='".$arr['agency_order_price']."',";
        }





        $in = mysqli_query($dbcon, "insert into ".$db_sangjo.".in_out 
        set
        storage_idx='".$arr['storage_idx']."',
        out_order_idx='".$out_order_idx."',
        product_idx='".$product_list[$i]['product_idx']."',
        current_count='".$next_count."',
        out_count='".$product_list[$i]['cnt']."',
        part='출고',
        io_status='".$io_status."',
        admin_memo = '".$arr['admin_memo']."',
        move_check = '".$arr['move_check']."',
        ".$additional_sql."
        write_admin_idx='".$admin_info['admin_idx']."',
        t_write_admin_name='".$admin_info['admin_name']."',
        update_admin_idx='".$admin_info['admin_idx']."',
        t_update_admin_name='".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));
    
        $io_idx = mysqli_insert_id($dbcon);
        if($io_idx){//쿼리성공


            //안전재고 테이블 합계 저장 업데이트
            $in = mysqli_query($dbcon, "insert into ".$db_sangjo.".storage_safe (storage_idx,product_idx,current_count) values ('".$arr['storage_idx']."','".$product_list[$i]['product_idx']."','".$next_count."')
            ON DUPLICATE KEY UPDATE current_count='".$next_count."'
            
            ") or die(mysqli_error($dbcon));



         
        }else{//쿼리실패
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



    if($arr['move_check'] == 1){ //이동지시서 작성
        //이동지시서 자동 작성을 위해서는 제품 최초 등록시 지정창고가 있어야 함.


        $sel = mysqli_query($dbcon, "select * from ".$db_sangjo.".product where product_idx='".$product_list[$i]['product_idx']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $data = mysqli_fetch_assoc($sel);
            $base_storage_idx= $data['base_storage_idx'];
         
        }else{
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="자동이동지시 작성 중: 상품등록 정보 읽기 실패";
    
            echo json_encode($result);
            exit;
        }

        //기본출고 창고의 재고량 파악
        //$sel = mysqli_query($dbcon, "select * from in_out where storage_idx='".$base_storage_idx."' and product_idx='".$product_list[$i]['product_idx']."' order by io_idx desc limit 1 ") or die(mysqli_error($dbcon));
        $sel = mysqli_query($dbcon, "select * from ".$db_sangjo.".storage_safe where storage_idx='".$base_storage_idx."' and product_idx='".$product_list[$i]['product_idx']."'  ") or die(mysqli_error($dbcon));

        
        $sel_num = mysqli_num_rows($sel);
        
        if ($sel_num > 0) {
            $data = mysqli_fetch_assoc($sel);
            $base_current_count = $data['current_count'];
            
        }else{
            //$current_count = 0;

            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="자동이동지시 작성 중: 기본출고지 상품정보 없음";

            echo json_encode($result);
            exit;


        }

        //$base_next_count = $base_current_count - $product_list[$i]['cnt'];

        $in = mysqli_query($dbcon, "insert into ".$db_sangjo.".in_out 
        set
        storage_idx='".$base_storage_idx."',
        product_idx='".$product_list[$i]['product_idx']."',
        part='이동출고',
        io_status='미출고',
        out_count='".$product_list[$i]['cnt']."',
        to_storage_idx='".$arr['storage_idx']."',

        current_count='".$base_current_count."',


        admin_memo = '".$arr['admin_memo ']."',
        move_check = '".$arr['move_check']."',
        write_admin_idx='".$admin_info['admin_idx']."',
        t_write_admin_name='".$admin_info['admin_name']."',
        update_admin_idx='".$admin_info['admin_idx']."',
        t_update_admin_name='".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));


    }
    

    
}


?>