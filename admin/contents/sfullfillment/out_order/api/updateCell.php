<?php



require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_fullfillment.php'; //DB 접속
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


         $old_current_count = $data['current_count'];
         $out_count = $data['out_count'];
         $next_count = $old_current_count - $out_count;
         $next_count_sql = ",current_count='".$next_count."'";


         $sel1 = mysqli_query($dbcon, "select * from settlement_sdate where consulting_idx='".$data['consulting_idx']."' and category1_idx='".$data['category1_idx']."'  ") or die(mysqli_error($dbcon));
         $sel_num1 = mysqli_num_rows($sel1);
         
         if ($sel_num1 > 0) {
            $data1 = mysqli_fetch_assoc($sel1);
            $sdate = $data1['sdate'];
         }else{
            $sdate = "";
         }
      
         if($sdate > 0){
            $this_date = intval(date("d",time()));
            if($this_date > $sdate){ //다음달 정산대상임
               $sdate_sql = ",bill_yyyymm=DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 MONTH), '%Y%m') ";
            }else{ //이번달 정산대상임
               $sdate_sql = ",bill_yyyymm=date_format(now(),'%Y%m') ";
            }
         }else{ //출고완료일이속하는 달을 정산대상월로 기본 설정
            //sdate == "건별" || $sdate == "미지정" || $sdate == "상시" || $sdate == "" || $sdate == null  || $sdate == "말일" 
            $sdate_sql = ",bill_yyyymm=date_format(now(),'%Y%m') ";
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






    $up = mysqli_query($dbcon, "update in_out set io_status='".$_POST['io_status']."'".$sdate_sql.",out_date=now(),output_datetime=now() ".$next_count_sql.",out_manager_idx='".$admin_info['admin_idx']."',t_out_manager_name='".$admin_info['admin_name']."' where io_idx='".$_POST['io_idx']."' ") or die(mysqli_error($dbcon));
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



