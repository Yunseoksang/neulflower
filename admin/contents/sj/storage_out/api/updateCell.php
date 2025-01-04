<?php



require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));




if($_POST['mode'] == "io_status"){


   
   $sel = mysqli_query($dbcon, "select * from in_out where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
   $sel_num = mysqli_num_rows($sel);
   if ($sel_num > 0) {
      $data = mysqli_fetch_assoc($sel);

   }


   if($_POST['io_status'] == "출고완료"){


         // $old_current_count = $data['current_count'];
         // $out_count = $data['out_count'];
         // $next_count = $old_current_count - $out_count;
         // $next_count_sql = ",current_count='".$next_count."'";
         

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

         $next_count_sql = ",current_count='".$next_count."'";

   }else if($_POST['io_status'] == "출고취소"){




      $sel1 = mysqli_query($dbcon, "select * from storage_safe where storage_idx='".$data['storage_idx']."' and product_idx='".$data['product_idx']."'  ") or die(mysqli_error($dbcon));
      $sel_num1 = mysqli_num_rows($sel1);
      
      if ($sel_num1 > 0) {
         $data1 = mysqli_fetch_assoc($sel1);
         $next_count = $data1['current_count'];
      }else{
         $next_count=0;
      }



      $up = mysqli_query($dbcon, "update in_out set io_status='".$_POST['io_status']."',current_count='".$next_count."',cancel_manager_idx='".$admin_info['admin_idx']."' where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
      $up_num = mysqli_affected_rows($dbcon);
      if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.

         
         mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));


         $result = array();
         $result['status'] = 1;
         $result['msg'] = "업데이트 되었습니다";
      
         echo json_encode($result);
         exit;
      }

   }else{

         if($data['io_status'] == "출고완료"){ //출고완료에서 이전으로 돌아갈수 없음  (혹은 재고량 마이너스 시키면서 백가능???)

            mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

            $result = array();
            $result['status'] = 0;
            $result['msg'] = "이전단계로 돌아갈수 없습니다.";
         
            echo json_encode($result);
            exit;
         
         }
   }


   if($_POST['qr_mode'] == "qr"){

      if($_POST['qr_code'] == ""){
         mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

         $result = array();
         $result['status'] = 0;
         $result['msg'] = "입력된 QR코드값이 없습니다.";
      
         echo json_encode($result);
         exit;
      }


      $product_idx = $data['product_idx'];
      $strlen = strlen($product_idx);
      $mlen = -1*$strlen;
      $qr_product_idx = substr($_POST['qr_code'],$mlen);
      if($qr_product_idx != $product_idx){
         mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

         $result = array();
         $result['status'] = 0;
         $result['msg'] = "QR코드값이 선택한 상품코드와 일치하지 않습니다.";
      
         echo json_encode($result);
         exit;
      }

      $qr_sql = ",qr_output_datetime=now()";
      $msg_text = $data['t_storage_name']."에서 ".$data['t_product_name']." 제품이 출고완료되었습니다.";


   }






   


    //출고완료가 아니면 모두 앞에서 exit 되므로 출고완료와 관련된 쿼리
    $up = mysqli_query($dbcon, "update in_out set io_status='".$_POST['io_status']."'".$next_count_sql.$qr_sql." ,
    
    out_date=date(now()),
    output_datetime=now(),
    out_manager_idx='".$admin_info['admin_idx']."',
    t_out_manager_name='".$admin_info['admin_name']."'
    
    
    where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.

       //if($sel_num > 0){
            //안전재고 테이블 합계 저장 업데이트
            // $in = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,current_count) values ('".$data['storage_idx']."','".$data['product_idx']."','".$next_count."')
            // ON DUPLICATE KEY UPDATE current_count='".$next_count."'
            // ") or die(mysqli_error($dbcon));
       //}



       if($_POST['receiver_name'] != "" && $_POST['io_status'] == "출고완료"){
         $up = mysqli_query($dbcon, "update out_order set receiver_name='".$_POST['receiver_name']."'  where out_order_idx='".$data['out_order_idx']."' ") or die(mysqli_error($dbcon));
      }
   

       mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));


       $result = array();
       $result['status'] = 1;
       $result['msg'] = "업데이트 되었습니다";
       $result['msg_text'] = $msg_text;
    
       echo json_encode($result);
       exit;
    
    }else{ //쿼리실패

      
      mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

       $result = array();
       $result['status'] = 0;
       $result['msg'] = "실패하였습니다.";
    
       echo json_encode($result);
       exit;
    
    }
 
 }
 
 
//mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));


?>



