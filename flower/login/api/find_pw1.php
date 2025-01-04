<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');



if($_POST['email'] == "" || $_POST['hp'] == ""){
    	$msg = array('code' => '10', 'msg' => "이메일 또는 전화번호 정보가 누락되었습니다.");
		echo json_encode($msg); 
		exit;
}


$hp = str_replace("-","",$_POST['hp']);


$sel = mysqli_query($dbcon, "select * from ".$db_admin.".admin_list where admin_email='".$_POST['email']."' and admin_hp='".$hp."'   ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
   $data = mysqli_fetch_assoc($sel);

   $sel1 = mysqli_query($dbcon, "select * from admin_pw where admin_uuid='".$data['admin_uuid']."'  and status='0wait' and reg_datetime > NOW() - INTERVAL 5 MINUTE  ") or die(mysqli_error($dbcon));
   $sel_num1 = mysqli_num_rows($sel1);
   
   if ($sel_num1 == 0) {
      $data1 = mysqli_fetch_assoc($sel1);
	  $del = mysqli_query($dbcon, "delete from admin_pw where admin_uuid='".$data['admin_uuid']."' ") or die(mysqli_error($dbcon));
	  $del_num = mysqli_affected_rows($dbcon);

	   $verify_code = guidv4(random_bytes(16)); //php7 부터 가능
	   $verify_code = str_replace("-","",$verify_code);


	  if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
	     $in = mysqli_query($dbcon, "insert into admin_pw (admin_uuid,verify_code) values ('".$data['admin_uuid']."','".$verify_code."' )  ") or die(mysqli_error($dbcon));
	     $in_id = mysqli_insert_id($dbcon);
	     if($in_id){
	        //쿼리성공


			$user_email = $_POST['email'];
			$user_name = $data['admin_name'];
			$user_uuid = $data['admin_uuid'];


            //include('../mail/sendMail_pw.php'); //메일발송.




	        $msg = array('code' => '1', 'msg' => "성공");
			echo json_encode($msg); 
			exit;
	     }else{
	       //쿼리실패
			$msg = array('code' => '14', 'msg' => "쿼리 실행 중 중 오류입니다. Error 14");
			echo json_encode($msg); 
			exit;
	     }
	     
	     
	     
	     
	  }else{ //쿼리실패
        $msg = array('code' => '13', 'msg' => "쿼리 실행 중 중 오류입니다. Error 13");
		echo json_encode($msg); 
		exit;
	  }
	  
	  
	  
	  
	  
      
   }else{
        $msg = array('code' => '12', 'msg' => "5분이내에 패스워드 변경요청을 다시 하실 수 없습니다.");
		echo json_encode($msg); 
		exit;

   }
   
   
   

}else{

       	$msg = array('code' => '11', 'msg' => "일치하는 회원정보가 없습니다.");
		echo json_encode($msg); 
		exit;
}







?>