<?
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

//ini_set('display_errors', '0');





header('Content-Type: text/html; charset=utf-8');
//session_start();


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_client.php'; //NEULFLOWER DB 접속
//dbcon = $db->connect();
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
require($_SERVER["DOCUMENT_ROOT"].'/lib/config.php');



if($manager_info['manager_id'] == ""){
   header('Location:./login/');
   exit;
}





?>




<!DOCTYPE html>
<html lang="ko">

<head>



  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="/favicon_32.ico">
  <link rel="icon" type="image/png" href="/favicon_32.ico">

  <title>(주)늘 주문발주 시스템</title>

  <!-- Bootstrap core v3.3.6 CSS -->
  <link href="./css/bootstrap.min.css" rel="stylesheet">

  <!-- input box autocomplete ajax 연동에 필요, 신규메뉴추가시 기존메뉴명 검색할때 이용 -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


  <!-- select2 -->
  <link href="./css/select/select2.min.css" rel="stylesheet">
  <!--스위치버튼 효과-->
  <link href="./css/switchery/switchery.min.css" rel="stylesheet">
  <!--스위치버튼 효과 끝-->


  
  <link href="./fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="./css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="./css/custom.css?time=?ver=<?=time()?>" rel="stylesheet">
  <link href="./css/icheck/flat/green.css" rel="stylesheet">

  <!--<link href="./js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />-->
  <link href="./js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />


  <link href="./js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="./js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="./js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="./js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" type="text/css" href="./js/datatables//fixedColumns.dataTables.min.css">

  <!--- (주)늘 CSS ---->
  <link href="./css/main.css?time=?ver=<?=time()?>" rel="stylesheet">
  <link href="./css/modal_add.css?time=?ver=<?=time()?>" rel="stylesheet">

  <!-- jquery confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">


  <!-- 화훼관리 월별 검색시 월 선택툴 https://kidsysco.github.io/jquery-ui-month-picker/ -->
  <link href="js/monthPicker/MonthPicker.css?time=<?=time()?>" rel="stylesheet" type="text/css" />



  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic|Nanum+Myeongjo&display=swap&subset=korean" rel="stylesheet">

  <style> p { font-family: 'Nanum Gothic', sans-serif; } </style>


  <style>
  
  @font-face {
    font-family: 'Daehan';
    font-style: normal;
    font-weight: 400;
    src: url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff2') format('woff2'), url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff') format('woff');
  }


  .profile{
    height:50px;
  }
  </style>


<script>
   var api_domain = '<?=$api_domain?>';
   //var img_resize_domain = '<?=$resize_domain?>/100x/';
   var img_resize_domain = '<?=$resize_domain?>';
   var admin_domain = "<?=$admin_domain?>";


</script>


<script>

// 데이터 테이블 Korean 세팅
var lang_kor = {
  "decimal" : "",
  "emptyTable" : "데이터가 없습니다.",
  "info" : "_START_ - _END_ (총 _TOTAL_ 건)",
  "infoEmpty" : "0건",
  "infoFiltered" : "(전체 _MAX_ 건 중 검색결과)",
  "infoPostFix" : "",
  "thousands" : ",",
  "lengthMenu" : "_MENU_ 개씩 보기",
  "loadingRecords" : "<div class='lds-hourglass loading-image'  ></div>",
  "processing" : "<div class='lds-hourglass loading-image'  ></div>",
  "search" :"",
  "searchPlaceholder": "",
  "zeroRecords" : "검색된 데이터가 없습니다.",
  "paginate" : {
      "first" : "첫 페이지",
      "last" : "마지막 페이지",
      "next" : "다음",
      "previous" : "이전"
  },
  "aria" : {
      "sortAscending" : " :  오름차순 정렬",
      "sortDescending" : " :  내림차순 정렬"
  }
};
</script>



  <!--<script src="./js/jquery.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
     //$("#partner_menu").slideDown();
  });


  </script>
<script src="./js/datatables/dataTables.ajax.button.js?ver=<?=time()?>"></script> <!-- 수정,저장,취소,삭제 공통기능 정의 --> 




  <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->



</head>


<body class="nav-md">

  <div class="container body">


    <div class="main_container">


      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title" style="border: 0;">
            <a href="./" class="site_title"><i class="glyphicon glyphicon-star-empty"></i> <span>(주)늘 발주시스템</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="./images/user.png" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info" id="profile_info" consulting_idx='<?=$manager_info['consulting_idx']?>' manager_idx="<?=$manager_info['manager_idx']?>">
              <span class="company_name"><?=$manager_info['company_name']?></span>
              <h2><?=$manager_info['manager_name']?> 님</h2>
            </div>
          </div>
          <!-- /menu prile quick info -->
          <? 
           if($this_agentt == "pc"){?>

          <br />
          <br />
          <br />
           <?}
          ?>

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">


                <li class="active"><a><i class="fa fa-download"></i> 물품발주 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="?page=fu/client_input/">발주하기</a>
                    </li>
                    <li><a href="?page=fu/out_order/">발주내역</a>
                    </li>
                    <li><a href="?page=bill/&part=fu">물품 거래명세서</a>
                    </li>

                  </ul>

                </li>


                <li><a><i class="fa fa-truck"></i> 화훼상조발주<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="?page=fl/client_input/">발주하기</a>
                    </li>
                    <li><a href="?page=fl/out_order/">발주내역</a>
                    </li>
                    <li><a href="?page=bill/&part=fl">화훼 거래명세서</a>
                    </li>
                    <li><a href="?page=bill/&part=sj">상조용품 거래명세서</a>
                    </li>

                  </ul>
                </li>



              </ul>

            </div>

          </div>




          <div class="sidebar-footer hidden-small">

             

            <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
          </div>
        </div>
      </div>



      <!-- top navigation -->
      <div class="top_nav">

        <div class="nav_menu">
          <nav class="" role="navigation">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-user emoticon_user"></i><?=$manager_info['manager_name']?>
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">

                  <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </li>
                </ul>
              </li>


              <li role="presentation" class="dropdown">
                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-envelope-o"></i>
                  <span class="badge bg-green">0</span>
                </a>
                <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">


                  <li>
                    <div class="text-center">
                      <a>
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                      </a>
                    </div>
                  </li>
                </ul>
              </li>

            </ul>
          </nav>
        </div>

      </div>
      <!-- /top navigation -->





<?



    if(isset($_GET['page']) && $_GET['page'] != ""){
      $tatle_bar_ratio = [2,8,2];
      $this_url = "contents/".$_GET['page'].".php";
      $this_url = str_replace("/.php","/index.php",$this_url);
      include($this_url);
    }else{
        include("contents/common/empty_html.php");
    }




?>


<?php

include('dashboard_common.php')

?>



<!-- 월선택 datepicker -->
<script src="js/monthPicker/MonthPicker.js?time=<?=time()?>"></script>


</body>

</html>
