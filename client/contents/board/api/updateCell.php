<?php



require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();

mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));




if($_POST['mode'] == "out_order_status"){


   
   $sel = mysqli_query($dbcon, "select * from out_order where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
   $sel_num = mysqli_num_rows($sel);
   if ($sel_num > 0) {
      $data = mysqli_fetch_assoc($sel);

      if($data['out_order_status'] == "배송완료" || $data['out_order_status'] == "주문취소" ){
         if($_POST['out_order_status'] == "배송요청" || $_POST['out_order_status'] == "본부접수" || $_POST['out_order_status'] == "주문접수" || $_POST['out_order_status'] == "배송중"){
            mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

            $result = array();
            $result['status'] = 0;
            $result['msg'] = "이전단계로 돌아갈수 없습니다.";
         
            echo json_encode($result);
            exit;
         }
      }


      if($_POST['out_order_status'] == "배송완료"){
         if($data['out_order_status'] == "배송요청"){
            mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

            $result = array();
            $result['status'] = 0;
            $result['msg'] = "배송요청 단계에서는 배송완료처리 할수 없습니다.";
         
            echo json_encode($result);
            exit;
         }
      }

   }







    $up = mysqli_query($dbcon, "update out_order set out_order_status='".$_POST['out_order_status']."' where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
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
       $result['msg'] = "실패하였습니다.";
    
       echo json_encode($result);
       exit;
    
    }
 
 }
 
 

?>



