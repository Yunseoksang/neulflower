<?
error_reporting(E_ALL&~E_WARNING);

//unset($_COOKIE['admin_info']); //not working
setcookie("flower_info", "", time()-3600);


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//api_member_check();


//require('../../lib/lib.php');

//session_start();
// if(isset($admin_info['admin_uuid'])){
// 	if($admin_info['admin_uuid'] != ""){
// 	header('Location:../dashboard_sangjo.php');
// 	exit;
// 	}
// }






?>




<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
     <meta name="viewport" content="user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />
	<!--<link rel="shortcut icon" href="/img/favicon.ico">-->
    <title>(주)늘 화훼 협력사 로그인</title>
    

        <link rel="stylesheet" href="css/style.css?ver=<?=time()?>">
        <link rel="stylesheet" href="css/media_style.css?ver=<?=time()?>">
        <script src='js/jquery.min.2.1.3.js'></script>
        <script src="/js/jquery.preloaders.min.js?ver=<?=time()?>"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script src='js/index.js'></script>

<style>
 
@font-face {
  font-family: 'Daehan';
  font-style: normal;
  font-weight: 400;
  src: url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff2') format('woff2'), url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff') format('woff');
}


a:link, a:visited {
    /*background-color: #B16702;*/
    color: white;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

a:hover, a:active {
    background-color: red;
}


</style>

<script>
<!--
	// 쿠키 생성
	function setCookie(cName, cValue, cDay){
		var expire = new Date();
		expire.setDate(expire.getDate() + cDay);
		cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
		if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
		document.cookie = cookies;
	}
 
	// 쿠키 가져오기
	function getCookie(cName) {
		cName = cName + '=';
		var cookieData = document.cookie;
		var start = cookieData.indexOf(cName);
		var cValue = '';
		if(start != -1){
			start += cName.length;
			var end = cookieData.indexOf(';', start);
			if(end == -1)end = cookieData.length;
			cValue = cookieData.substring(start, end);
		}
		return unescape(cValue);
	}





//-->



</script>


  </head>

  <body>


   <div style=" text-align: center;margin-top:50px;" id="neulflower">
       <img src="../../img/neulflower_logo.png" width="500px">
   </div>



    <div class="wrapper" >

		<div class="container">
			<h1 class='main_title'>(주)늘 화훼협력사 로그인</h1>
			
			<form class="form" onsubmit="return false;">


				<div class="input_row">
					<label for="admin_id">아이디:</label>
					<input id="admin_id" type="text" class="input"   placeholder="user id"   onkeyup='input_keyup(this);'>
				</div>



				<div class="input_row">
					<label for="pw">패스워드:</label>
					<input id="pw" type="password"  class="input"  placeholder="password"    onkeyup='input_keyup(this);'>
				</div>



				
				<div class="input_row">
					<label for="login-button"></label>
					<button type="button" id="login-button" >로그인</button>
				</div>

				<div class="input_row" style=" margin-top: 25px;" >
				    <span  style="margin-right:30px; color:white; cursor:pointer;"><input type=checkbox id="checkId" name="checkId" style=" width:30px !important; ">아이디기억</span>
					<a href="#" id="regist_link" style="margin-right:25px;">아이디등록</a> <a href="#" id="pw_link">비밀번호 분실</a> 
				</div>


			</form>


		</div>
		
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




<script>


$(document).on("keypress","#pw",function(e){
   //return false; //  기능 정지.
  if (e.which == 13) {/* 13 == enter key@ascii */
      $("#login-button").trigger("click");
  }

});
 
$("#regist_link").click(function(){
      window.location.href='regist.php';
});
 
$("#pw_link").click(function(){
      window.location.href='find_pw.php';
});
 
 $("#login-button").click(function(event){

		event.stopImmediatePropagation();


		var url = "../login_check.php";
		var admin_id = $.trim($("#admin_id").val());
		var pw = $.trim($("#pw").val());



		//window.alert('모든 파트너 작업이 종료되었습니다. 감사합니다');
		//return false;


        if(admin_id == undefined || admin_id == ""){
           $("#admin_id").focus();
		   return false;
		}

        if(pw == undefined || pw == ""){
           $("#pw").focus();
		   return false;
		}

		var str = "admin_id="+admin_id+"&pw="+pw; ; 

		$.ajax( { 
				  type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {
				  
				  
				      $.preloader.start();

				  
				  },context: this,
				  success: function(result){

					  $.preloader.stop();

					  console.log(result);

					  if(result['code'] == "1")
					  {
						 //window.alert(result['msg']);

		             	 //setCookie('login', '1', 15);

						console.log(result.admin_info);




						if(result.start_page == "상조물류관리"){
							window.location.href='../dashboard_sangjo.php';

						}else if(result.start_page == "계약업체관리"){
							window.location.href='../dashboard_cnst.php';

						}else if(result.start_page == "화훼관리"){
							window.location.href='../dashboard_flower.php';

						}else if(result.start_page == "통계관리"){
							window.location.href='../dashboard_stat.php';

						}else if(result.start_page == "인사총무관리"){
							window.location.href='../dashboard_hrm.php';

						}else{ //종합물류관리
							window.location.href='../dashboard_sffm.php';

						}



					  }else{
						 window.alert(result['msg']);
					  }
				  }, //success
				  error : function( jqXHR, textStatus, errorThrown ) {
					  window.alert('로그인 오류입니다');
					  console.log( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
				  }
		}); //ajax


});




 function input_keyup(ethis){   //<input type=text onkeyup='input_keyup(this);'> inline에벤트에서 외부의 jquery function을 콜할때 자신객체를 pass하기 위해 this이용. 
					 //jquery function에서는 function functionname(param){$(param).value();} 처럼 하면 this 객체 전달가능.
	  var ethis_id = $(ethis).attr("id");
	 
	   if(event.keyCode == 13){ //엔터키면


			 if(ethis_id == "id"){
				  $("#pw").focus();
			 }else if(ethis_id == "pw"){
				  $("#login-button").trigger("click");
			 }

	   }


 }





</script>


    
    
    
  </body>
</html>
