<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));




if($_POST['mode'] == "io_status"){


    $sel = mysqli_query($dbcon, "select * from in_out where io_idx='".$_POST['io_idx']."'  ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        $old_io_status = $data['io_status'];

        if($_POST['io_status'] == "이동출고완료"){
            if($old_io_status != "미출고" ){
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

                $result = array();
                $result['status'] = 0;
                $result['msg'] = "미출고 상태에서만 이동출고완료 처리가 가능합니다.";
             
                echo json_encode($result);
                exit;
            }




            $sel1 = mysqli_query($dbcon, "select * from storage_safe where storage_idx='".$data['storage_idx']."' and product_idx='".$data['product_idx']."'  ") or die(mysqli_error($dbcon));
            $sel_num1 = mysqli_num_rows($sel1);
            
            if ($sel_num1 > 0) {
               $data1 = mysqli_fetch_assoc($sel1);
               $next_count = $data1['current_count'] - $data['out_count'];
               if($next_count < 0){
                  mysqli_rollback($dbcon);
   
                  $result = array();
                  $result['status'] = 0;
                  $result['msg']="출고할수 있는 재고수량이 부족합니다. 현재재고수량:".$data1['current_count']."개";
            
                  echo json_encode($result);
                  exit;
               }
   
               $up = mysqli_query($dbcon, "update storage_safe set current_count='".$next_count."' where st_safe_idx='".$data1['st_safe_idx']."' ") or die(mysqli_error($dbcon));
               $up_num = mysqli_affected_rows($dbcon);
               if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
               //
               }else{ //쿼리실패
                  mysqli_rollback($dbcon);
   
                  $result = array();
                  $result['status'] = 0;
                  $result['msg']="쿼리실행 오류: ERROR 211";
            
                  echo json_encode($result);
                  exit;
               }
   
            }else{
               mysqli_rollback($dbcon);
   
               $result = array();
               $result['status'] = 0;
               $result['msg']="출고지 창고에 최초입고내역이 없습니다";
      
               echo json_encode($result);
               exit;
            }
   



            //$next_count = $data['current_count'] - $data['out_count'];

            /*
            if($next_count < 0){

                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

                $result = array();
                $result['status'] = 0;
                $result['msg'] = "보내는 창고의 최종재고량이 0개 미만이 되어 오류입니다.";
             
                echo json_encode($result);
                exit;
            }

            */


            if($_POST['out_date'] != "" && $_POST['out_date'] != "undefined"){
               $out_date_sql = "out_date='".$_POST['out_date']."',";
            }

            if($_POST['delivery_type'] != "" && $_POST['delivery_type'] != "undefined"){
                $delivery_type_sql = "delivery_type='".$_POST['delivery_type']."',";
            }
            if($_POST['memo'] != "" && $_POST['memo'] != "undefined"){
                $memo_sql = "memo='".$_POST['memo']."',";
            }
                    
            $up = mysqli_query($dbcon, "update in_out set io_status='".$_POST['io_status']."' ,"
            .$out_date_sql.$delivery_type_sql.$memo_sql.
            "current_count='".$next_count."',
            out_date=now(),

            out_manager_idx='".$admin_info['admin_idx']."',
            update_admin_idx='".$admin_info['admin_idx']."'

            where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
            $up_num = mysqli_affected_rows($dbcon);
            if($up_num < 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

                $result = array();
                $result['status'] = 0;
                $result['msg'] = "실패하였습니다.";
                
                echo json_encode($result);
                exit;
                
            }else{
                //안전재고 테이블 합계 저장 업데이트
                // $in = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,current_count) values ('".$data['storage_idx']."','".$data['product_idx']."','".$next_count."')
                // ON DUPLICATE KEY UPDATE current_count='".$next_count."'
                
                // ") or die(mysqli_error($dbcon));




                $sel3 = mysqli_query($dbcon, "select * from storage_safe where storage_idx='".$data['to_storage_idx']."' and product_idx='".$data['product_idx']."'  ") or die(mysqli_error($dbcon));
                $sel_num3 = mysqli_num_rows($sel3);
                
                if ($sel_num3 > 0) {
                    $data3 = mysqli_fetch_assoc($sel3);
                    $next_count = $data3['current_count'];
                    
                }else{
                    $next_count = 0;
                }
        




                $in = mysqli_query($dbcon, "insert into in_out 
                set
                storage_idx='".$data['to_storage_idx']."',
                product_idx='".$data['product_idx']."',

                part='이동입고',
                io_status='미입고',

                in_count='".$data['out_count']."',
                from_storage_idx='".$data['storage_idx']."',

                current_count='".$next_count."',
                out_date=now(),

                write_admin_idx='".$admin_info['admin_idx']."',
                update_admin_idx='".$admin_info['admin_idx']."'

                
                ") or die(mysqli_error($dbcon));
    
    
    
                $in_id = mysqli_insert_id($dbcon);

                
            }



        }else if($_POST['io_status'] == "이동입고완료"){

            if($old_io_status != "미입고"){
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

                $result = array();
                $result['status'] = 0;
                $result['msg'] = "미입고 단계에서만 이동입고완료 처리가 가능합니다.";
             
                echo json_encode($result);
                exit;
            }



            $sel1 = mysqli_query($dbcon, "select * from storage_safe where storage_idx='".$data['storage_idx']."' and product_idx='".$data['product_idx']."'  ") or die(mysqli_error($dbcon));
            $sel_num1 = mysqli_num_rows($sel1);
            
            if ($sel_num1 > 0) {
               $data1 = mysqli_fetch_assoc($sel1);
               $next_count = $data1['current_count'] + $data['in_count'];
               
            }else{
                $next_count = $data['in_count'];

            }
   




                //$next_count = $data['current_count'] + $data['in_count'];
                 



                
                $up = mysqli_query($dbcon, "update in_out set io_status='".$_POST['io_status']."',

                receive_date=now(),
                current_count='".$next_count."',
                receive_date=now(),
                
                in_manager_idx='".$admin_info['admin_idx']."',
                update_admin_idx='".$admin_info['admin_idx']."'

                where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
                $up_num = mysqli_affected_rows($dbcon);
                if($up_num < 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                    mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));
    
                    $result = array();
                    $result['status'] = 0;
                    $result['msg'] = "실패하였습니다.";
                    
                    echo json_encode($result);
                    exit;
                    
                }else{
                    //안전재고 테이블 합계 저장 업데이트
                    $in = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,current_count) values ('".$data['storage_idx']."','".$data['product_idx']."','".$next_count."')
                    ON DUPLICATE KEY UPDATE current_count='".$next_count."'
                    ") or die(mysqli_error($dbcon));

                }



         


        }
        /*
        
        else{ //단순히 단계만 업데이트
    
            $up = mysqli_query($dbcon, "update in_out set io_status='".$_POST['io_status']."' where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
            $up_num = mysqli_affected_rows($dbcon);
            if($up_num < 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

                $result = array();
                $result['status'] = 0;
                $result['msg'] = "실패하였습니다.";
                
                echo json_encode($result);
                exit;
                
            }


        }

        */


     
    }


    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));

    $result = array();
    $result['status'] = 1;
    $result['msg'] = "처리 되었습니다";

    echo json_encode($result);
    exit;





 
 }else if($_POST['mode'] == "delivery_type"){



   $up = mysqli_query($dbcon, "update in_out set delivery_type='".$_POST['delivery_type']."' where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
   $up_num = mysqli_affected_rows($dbcon);
   if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
    
        mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));

        $result = array();
        $result['status'] = 1;
        $result['msg'] = "업데이트 되었습니다";
    
        echo json_encode($result);
        exit;
    
   }else{ //쿼리실패
    
        mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

        $result = array();
        $result['status'] = 0;
        $result['msg'] = "실패하였습니다";
    
        echo json_encode($result);
        exit;
   
   }



   
   

 }
 
 



?>



