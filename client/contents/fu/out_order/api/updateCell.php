<?php



require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_fullfillment.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();

mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));




if($_POST['mode'] == "io_status"){


   
   $sel = mysqli_query($dbcon, "select * from in_out where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
   $sel_num = mysqli_num_rows($sel);
   if ($sel_num > 0) {
      $data = mysqli_fetch_assoc($sel);

   }


   if($_POST['io_status'] == "출고완료"){


         $old_current_count = $data['current_count'];
         $out_count = $data['out_count'];
         $next_count = $old_current_count - $out_count;
         $next_count_sql = ",current_count='".$next_count."'";

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




    $up = mysqli_query($dbcon, "update in_out set io_status='".$_POST['io_status']."'".$next_count_sql." where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.

       if($sel_num > 0){
            //안전재고 테이블 합계 저장 업데이트
            $in = mysqli_query($dbcon, "insert into storage_safe (storage_idx,product_idx,current_count) values ('".$data['storage_idx']."','".$data['product_idx']."','".$next_count."')
            ON DUPLICATE KEY UPDATE current_count='".$next_count."'
            ") or die(mysqli_error($dbcon));
       }
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
       $result['msg'] = "실패하였습니다.";
    
       echo json_encode($result);
       exit;
    
    }
 
 }
 
 
mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));


?>



