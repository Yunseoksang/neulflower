<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">

    <title>(주)늘 주문발주 시스템</title>
    
        <link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/media_style_pw.css?ver=<?=time()?>">
        <script src='js/jquery.min.2.1.3.js'></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>

<style>

@font-face {
  font-family: 'Daehan';
  font-style: normal;
  font-weight: 400;
  src: url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff2') format('woff2'), url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff') format('woff');
}

input::placeholder {
  color: white;
  font-style: italic;
}

</style>


  </head>

  <body>


  <div style=" text-align: center;margin-top:50px;" id="neulflower">
       <img src="../../img/neulflower_logo.png" width="300px">
   </div>



    <div class="wrapper" style="margin-top: -170px;">




	<div class="container" style="padding-top:50px;" id="container1">
		<h2 class="main_title">비밀번호 재설정</h2>
		<br>
        <div class='exp_pw'>비밀번호를 분실하신경우 담당자에게 먼저 비밀번호 초기화를 요청해주세요.</div>
		<form class="form" onsubmit="return false;">

            <div style="display:flex; flex-direction: row; justify-content: center; align-items: center">
		        <label for="hp">휴대폰*</label>
			    <input id="hp" type="tel" class="input" placeholder="-제외한 숫자만">
			</div>

            <div style="display:flex; flex-direction: row; justify-content: center; align-items: center">
		        <label for="manager_id">아이디*</label>
			    <input id="manager_id" type="text" class="input"   placeholder="가입시 등록한 아이디">
			</div>

            <div  class="input_row">
		        <label for="pw">신규 패스워드*</label>
			    <input id="pw" type="password"  class="input"  placeholder="6자리이상">
			</div>

            <div  class="input_row">
		        <label for="pw2">패스워드 확인*</label>
			    <input id="pw2" type="password"  class="input"   placeholder="">
			</div>


			<button type="button" id="go-login-button1" class="button3" style="margin-right:20px;"> < 로그인 화면</button>
			<button type="button" id="next-button" style="width:150px;">비밀번호 저장</button>


		</form>


	</div>






	<div class="container" style="padding-top:80px; display:none;" id="container2">
		<h2 class="main_title">비밀번호 찾기</h2>
		<form class="form" onsubmit="return false;">
            <div style="display:flex; flex-direction: row; justify-content: center; align-items: center; margin-bottom: 50xp;">
                  본인확인을 위해 이메일을 발송하였습니다.<br> 이메일을 확인해 주세요.
				  <br><br><br>
			</div>

			<button type="button" id="go-login-button2" class="button3" style="margin-right:20px; width:300px"> 로그인 화면 바로가기</button>

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

<div class='toast' style='display:none'>Error</div>

<script src="js/index.js"></script>

<script>




	
function chkPwd(str){

	var pw = str;
	var num = pw.search(/[0-9]/g);
	var eng = pw.search(/[a-z]/ig);
	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

	if(pw.length < 6 || pw.length > 20){
	   toast("6자리 ~ 20자리 이내로 입력해주세요.");
	   return false;
	}

	if(pw.search(/₩s/) != -1){
		toast("비밀번호는 공백업이 입력해주세요.");
		return false;
	} 

	//  if(num < 0 || eng < 0 || spe < 0 ){
		//   toast("영문,숫자, 특수문자를 혼합하여 입력해주세요.");
		//   return false;
	//  }

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


				//var name = $("#name").val();
				var hp   = $("#hp").val();
				var manager_id = $.trim($("#manager_id").val());
				var pw    = $.trim($("#pw").val());
				var pw2   = $.trim($("#pw2").val());


				if(hp == undefined || hp == ""){
				   $("#hp").focus();
				   return false;
				}


				if(manager_id == undefined || manager_id == ""){
				   $("#manager_id").focus();
				   return false;
				}




				if(pw == undefined || pw == ""){
				   $("#pw").focus();
				   return false;
				}

				if($.trim($("#pw").val()) != $.trim($("#pw2").val())){
				   window.alert('새 비밀번호가 일치하지 않습니다');
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

                // $(this).addClass("btn_regist_wait").removeClass("next-button");
				// $(this).text("대기중");


                //var url = "./api/find_pw1.php";
				var url = "./api/reset_pw.php";

				var str = "manager_id="+manager_id+"&hp="+hp+"&pw="+pw;

				$.ajax( { 
						  type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
						  success: function(result){
							  if(result['code'] == "1")
							  {
								 window.alert('비밀번호가 변경되었습니다');
								 window.location.href='../';
									// $("#container1").hide();
									// $("#container2").show();

							  }else{
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
