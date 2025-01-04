<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//api_member_check();


$sel = mysqli_query($dbcon, "select * from storage order by storage_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);




?>

<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="/img/favicon.ico">
    <title>주식회사 늘</title>
      <!-- Bootstrap core v3.3.6 CSS -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">


        <link rel="stylesheet" href="css/style.css?ver=<?=time()?>">
        <link rel="stylesheet" href="css/media_style_regist.css?ver=<?=time()?>">
        <script src='js/jquery.min.2.1.3.js'></script>

        <link rel="stylesheet" href="css/rule.css?ver=<?=time()?>">

        <link rel="stylesheet" href="css/privacy.css?ver=<?=time()?>">


        <script src="/js/jquery.preloaders.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>


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



    <div class="wrapper" style="  height: 600px; margin-top: -240px;" >




	<div class="container" style="padding-top:30px;">

		
		<form class="form" onsubmit="return false;">

		<h1 id="title" class="main_title">(주)늘 관리자 등록</h1>
		    <br>



            <div class="input_row">
		        <label for="id">아이디*</label>
			    <input id="id" type="text" class="input"   placeholder="">
			</div>

            <div  class="input_row">
		        <label for="pw">패스워드*</label>
			    <input id="pw" type="password"  class="input"  placeholder="6자리이상">
			</div>

            <div  class="input_row">
		        <label for="pw2">패스워드확인*</label>
			    <input id="pw2" type="password"  class="input"   placeholder="">
			</div>

            <div  class="input_row">
		        <label for="name">이 름*</label>
			    <input id="name" type="text"  class="input" placeholder="">
			</div>

            <div  class="input_row">
		        <label for="hp">휴대폰*</label>
			    <input id="hp" type="tel" class="input"   placeholder="-제외한 숫자만">
			</div>

			<?
			if ($sel_num > 0) {?>
			<div class="input_row" style="padding-left:30px;">
		        <label for="storage_idx">소속그룹*</label>
				    <div class="col-md-6 col-sm-6 col-xs-12" style="    PADDING: 0PX 31PX 25PX 4PX;">

						<select id="storage_idx" class="form-control" style="background-color: rgba(255, 255, 255, 0.2);">
							<?
							while($data = mysqli_fetch_assoc($sel)) 
							{?>
								<option value='<?=$data['storage_idx']?>' <? if($data['storage_idx'] == "1"){echo "selected";} ?> ><?=$data['storage_name']?></option>
					
							<?} 

							?>
						</select>
					</div>

			</div>		
			<?}
			?>

<!-- 			 -->
<!--             <div style="display:flex; flex-direction: row; justify-content: center; align-items: center"> -->
<!-- 		        <label for="login-button"></label> -->
<!-- 			    <button type="button" id="next-button" >다음 > </button> -->
<!-- 			</div> -->



			<!----<button type="button" id="login-button" class="button3" style="margin-right:20px;"> < 로그인 화면</button><button type="button" id="next-button" style="width:150px;">다음 > </button> -->

						<button type="button" id="login-button" class="button3" style="margin-right:20px;"> < 로그인 화면</button><button type="button" id="regist-button" class="button2">등록신청</button>



		</form>



        

        <form class="form5" onsubmit="return false;" style="display:none;">
		    <h1>(주)늘 관리자 신청완료</h1>
			<br><br>
            <div style="height: 100px; ">
			   <h2></h2>
			   <h2>담당자의 확인 후 로그인이 가능합니다.</h2>

			</div>
			<button type="button" class="login-button" style="margin-top:30px;">로그인 페이지</button>
		</form>



	</div>



	
	<ul class="bg-bubblesss">
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
 


        // 정규식 - 이메일 유효성 검사
        var regEmail = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        // 정규식 -전화번호 유효성 검사
        var regPhone = /^((01[1|6|7|8|9])[1-9]+[0-9]{6,7})|(010[1-9][0-9]{7})$/;


/*
		$(document).on("keypress","#pw",function(e){
			$("#pw2").val("");
		});

		$(document).on("focus","#pw",function(){
		   $("#pw2").show();
		   //$("#login-button").hide();
		});

*/




		$(document).on("keyup","#pw2",function(e){


			if($.trim($("#pw").val()) == $.trim($("#pw2").val())){

                //$("#pw2").hide();
				//$("#login-button").show();
			}else{
                if(e.keyCode == 13){ //엔터키면
                   //window.alert('패스워드가 일치하지 않습니다');
				   
                   toast("패스워드가 일치하지 않습니다.");

				   //$("#pw").val("");
				   //$("#pw2").val("");
				   $("#pw").focus();
				   return false;
				}
			}
		});


          
      


		 $("#pre-button").click(function(event){
			  $(".form2").hide();
			  $(".form").show();

		 });



		 $(".alink1").click(function(event){
			  $(".form2").hide();
			  $("#title").hide();
			  $(".form3").show();
		 });


		 $(".alink2").click(function(event){
			  $(".form2").hide();
			  $("#title").hide();
			  $(".form4").show();
		 });



		 $(".button-close").click(function(event){
				
             $(".form3").hide();
             $(".form4").hide();
			 $(".form2").show();
			 $("#title").show();
		 });





         $("#login-button").click(function(){
             window.location.href='./index.php';
         });

         $(".login-button").click(function(){
             window.location.href='./index.php';
         });

		 $(".welcome-button").click(function(event){
			   window.location.href='../index.php';
		 });



		 $("#regist-button").click(function(event){





			    var name  = $.trim($("#name").val());
				var hp    = $.trim($("#hp").val());
				var id = $.trim($("#id").val());
				var pw    = $.trim($("#pw").val());
				var pw2   = $.trim($("#pw2").val());
				var storage_idx   = $.trim($("#storage_idx").val());

/*

				if(email == undefined || email == ""){
				   $("#email").focus();
				   toast('이메일을 입력해주세요');
				   return false;
				}


				 if(!regEmail.test(email)) {
				   $("#email").focus();
				   toast('올바른 이메일이 아닙니다.');
				   return false;
				}

*/

				if(pw == undefined || pw == ""){
				   $("#pw").focus();
				   toast('패스워드를 입력해주세요.');
                   //$("#pw2").val("").show();
				   //$("#login-button").hide();
				   return false;
				}

				if(pw != pw2){
				   //window.alert('비밀번호가 일치하지 않습니다');
                   toast("패스워드가 일치하지 않습니다.");

				   $("#pw").focus();
                   //$("#pw2").val("").show();
				   //$("#login-button").hide();
                   return false;
				}



				if(!chkPwd(pw)){
				   $("#pw").focus();
				   
				   return false;
				}





				if(name == undefined || name == ""){
				   $("#name").focus();
				   toast('이름을 입력해주세요');
				   return false;
				}


/*

				if(hp == undefined || hp == ""){
				   $("#hp").focus();
				   toast('휴대폰을 입력해주세요');
				   return false;
				}

				 if(!regPhone.test(hp)) {
				   $("#hp").focus();
				   toast('올바른 휴대폰번호가 아닙니다.');
				   return false;
				}

*/


				 $(".form").hide();
				 $(".form2").show();






                var url = "./api/admin_regist.php";

				var str = "id="+id+"&pw="+pw+"&name="+name+"&hp="+hp+"&storage_idx="+storage_idx; 


				console.log(str);


				$.ajax( { 
						  type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {
						  
						       $.preloader.start();
						  },context: this,
						  success: function(result){
						       $.preloader.stop();

							  if(result['code'] == "1")
							  {

                                    //window.location.href='../index.php';


									$(".form").hide();
									$("#title").hide();

									$(".form5").show();


								   toast('등록되었습니다.');


							  }else{
								  toast(result['msg']);

								  if(result['code'] == "10"){ //이메일 중복
                                       $(".form2").hide();
									   $(".form").show();

								  }


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
