<?php


        //비번 변경후 곧바로 로그인 처리
		$sel1 = mysqli_query($dbcon, "select company_name from consulting.consulting where consulting_idx='".$data['consulting_idx']."'  ") or die(mysqli_error($dbcon));
		$sel_num1 = mysqli_num_rows($sel1);
		
		if ($sel_num1 > 0) {
		   $data1 = mysqli_fetch_assoc($sel1);
		   $data['company_name'] = $data1['company_name'];
		}

		setcookie('manager_info', json_encode($data), time()+360*24); //manager 정보 전체  => 사용할때는 $data = json_decode($_COOKIE['manager_info'], true);

        

		try{

			$up = mysqli_query($dbcon, "update consulting.manager set manager_last_login_date=now(), manager_login_count=manager_login_count+1  where manager_id='".$data['manager_id']."' ") or die(mysqli_error($dbcon));
			$in = mysqli_query($dbcon, "insert into consulting.manager_login_history  (manager_idx) values ('".$data['manager_idx']."')  ") or die(mysqli_error($dbcon));
			
		}catch(Exception $e) {
			//echo 'Message: ' .$e->getMessage();
		}


?>