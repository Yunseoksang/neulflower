<?
require_once '../../lib/DB_Connect.php'; //DB 접속
require('../../lib/lib.php');

if($_GET['uuid'] == "" || $_GET['vcode'] == ""){
   daum_exit('index.php');
   exit;
}

$sel = mysqli_query($dbcon, "select * from  partner_pw  where partner_uuid='".$_GET['uuid']."' and verify_code='".$_GET['vcode']."' and (status='0wait' or status='1confirm') and reg_datetime > NOW() - INTERVAL 15 MINUTE   ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if($sel_num == 0) {
   alert_daum_exit('기간이 만료된 접속 정보입니다.비밀번호 변경요청을 다시 신청하시기 바랍니다.','index.php');
   exit;
}else{
   $data = mysqli_fetch_assoc($sel);
   
   $up = mysqli_query($dbcon, "update partner_pw set status='1confirm',reg_datetime=now()  where pw_idx=".$data['pw_idx']." ") or die(mysqli_error($dbcon));

}






?>



<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">

    <title>주식회사 늘 파트너</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

    <title>주식회사 늘 파트너</title>
    
        <link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/media_style_pw.css?ver=<?=time()?>">
        <script src='js/jquery.min.2.1.3.js'></script>

<style>

@font-face {
  font-family: 'Daehan';
  font-style: normal;
  font-weight: 400;
  src: url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff2') format('woff2'), url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff') format('woff');
}
</style>


  </head>

  <body>


  <div style=" text-align: center;margin-top:50px;" id="neulflower">
       <img src="../../img/neulflower_logo.png" width="300px">
   </div>


    <div class="wrapper" style="margin-top: -170px;">




	<div class="container" style="padding-top:80px;" id="container1">
		<h2 class="main_title">새로운 비밀번호를 입력해주세요.</h2>

		<form class="form" onsubmit="return false;">

            <div style="display:flex; flex-direction: row; justify-content: center; align-items: center">
		        <label for="pw">신규 패스워드*:</label>
			    <input id="pw" type="password"  class="input"  placeholder="8자리이상 영문+숫자+특수문자">
			</div>

            <div style="display:flex; flex-direction: row; justify-content: center; align-items: center">
		        <label for="pw2">패스워드확인*:</label>
			    <input id="pw2" type="password"  class="input"   placeholder="">
			</div>

			<button type="button" id="go-login-button1" class="button3" style="margin-right:20px;"> < 로그인 화면</button>
			<button type="button" id="next-button" style="width:150px;">저장하기</button>


		</form>


	</div>






	<div class="container" style="padding-top:80px; display:none;" id="container2">
		<h2 class="main_title">비밀번호 변경 완료</h2>
		<form class="form" onsubmit="return false;">
            <div style="display:flex; flex-direction: row; justify-content: center; align-items: center; margin-bottom: 50xp;">
                  비밀번호가 변경 완료되었습니다. <br> 로그인해 주세요.
				  <br><br><br>
			</div>

			<button type="button" id="go-login-button2" class="button2" style="margin-right:20px; width:300px"> 로그인 화면 바로가기</button>

		</form>

	</div>


<div class='toast' style='display:none'>Error</div>
	
	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>

    <script src="js/index.js"></script>

    <script>



		function chkPwd(str){

		 var pw = str;

		 var num = pw.search(/[0-9]/g);

		 var eng = pw.search(/[a-z]/ig);

		 var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

		 

		 if(pw.length < 8 || pw.length > 20){

		  toast("8자리 ~ 20자리 이내로 입력해주세요.");

		  return false;

		 }

		 if(pw.search(/₩s/) != -1){

		  toast("비밀번호는 공백업이 입력해주세요.");

		  return false;

		 } if(num < 0 || eng < 0 || spe < 0 ){

		  toast("영문,숫자, 특수문자를 혼합하여 입력해주세요.");

		  return false;

		 }

		 

		 return true;

		}




$(document).ready(function(){
 




         $("#go-login-button1").click(function(){
             window.location.href='./index.php';
         });

         $("#go-login-button2").click(function(){
             window.location.href='./index.php';
         });


		 $("#next-button").click(function(event){



				var pw    = $.trim($("#pw").val());
				var pw2   = $.trim($("#pw2").val());




				if($.trim($("#pw").val()) != $.trim($("#pw2").val())){
				   toast('새 비밀번호가 일치하지 않습니다');
				   $("#pw").focus();
				   $("#pw").val("");
				   $("#pw2").val("");
                   return false;
				}




				if(!chkPwd(pw)){
				   $("#pw").focus();
				   
				   return false;
				}





               // $("#container1").hide();
			    //$("#container2").show();

                $(this).addClass("btn_regist_wait").removeClass("next-button");
				$(this).text("대기중");


                var url = "./api/find_pw2.php";
				var str = "pw="+pw+"&uuid=<?=$_GET['uuid']?>&vcode=<?=$_GET['vcode']?>";

				$.ajax( { 
						  type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
						  success: function(result){
							  if(result['code'] == "1")
							  {
								 //window.alert('비밀번호가 변경되었습니다');
								 //window.location.href='/partner/login/';
									$("#container1").hide();
									$("#container2").show();


							  }else{

                                 if(result['code'] == "11"){
                                    window.alert(result['msg']); //접속정보가 유효하지 않습니다.
									window.location.href="index.php";
								 }

								 window.alert(result['msg']);
							  }
						  }, //success
						  error : function( jqXHR, textStatus, errorThrown ) {
							  alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
						  }
				}); //ajax







		});



  
});



	</script>


    
    
  </body>
</html>
