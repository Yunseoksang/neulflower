<?
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

//ini_set('display_errors', '0');


// register_shutdown_function('shutdown');
// function shutdown()
// {
//     $lastError = error_get_last();
//     if($lastError !== null)
//     {
//         $log = array();
//         $log['error_message'] = $lastError;
//         $log['debug'] = debug_backtrace();

//         print_r($log);
//     }
// }

header('Content-Type: text/html; charset=utf-8');
//session_start();


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //THE HUE DB 접속
//dbcon = $db->connect();
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
require($_SERVER["DOCUMENT_ROOT"].'/lib/config.php');



if($admin_info['admin_uuid'] == ""){
   header('Location:./login1/'.$admin_info['admin_login_page']);
   exit;
}


if(strpos($_SERVER['QUERY_STRING'],"sangjo/") || strpos($_SERVER['QUERY_STRING'],"admin_list/")){
  if($admin_info['pm_sangjo'] == "종합관리자" || $admin_info['pm_sangjo'] != null){
    //
  }else{
    header('Location:./login1/'.$admin_info['admin_login_page']);
    exit;  
  }
}





//$_GET['link']


// require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //THE HUE DB 접속
// $dbcon = $db->connect_mk_fmoney();





// $sel = mysqli_query($dbcon, "select * from ".$db_admin.".admin_list where admin_uuid='".$admin_info['admin_uuid']."'  ") or die(mysqli_error($dbcon));
// $sel_num = mysqli_num_rows($sel);

// if ($sel_num > 0) {
//    $data = mysqli_fetch_assoc($sel);
//    $admin_name = $data['admin_name'];
// }else{
//    header('Location:./login1/'.$admin_info['admin_login_page']);
//    exit;
// }



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

  <title>(주)늘 물류생산관리 시스템</title>

  <!-- Bootstrap core v3.3.6 CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- input box autocomplete ajax 연동에 필요, 신규메뉴추가시 기존메뉴명 검색할때 이용 -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


  <!-- select2 -->
  <link href="css/select/select2.min.css" rel="stylesheet">
  <!--스위치버튼 효과-->
  <link href="css/switchery/switchery.min.css" rel="stylesheet">
  <!--스위치버튼 효과 끝-->


  
  <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="css/custom.css?time=?ver=<?=time()?>" rel="stylesheet">
  <link href="css/icheck/flat/green.css" rel="stylesheet">

  <!--<link href="js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />-->
  <link href="js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />


  <link href="js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" type="text/css" href="js/datatables//fixedColumns.dataTables.min.css">

  <!--- (주)늘 CSS ---->
  <link href="css/main.css?time=?ver=<?=time()?>" rel="stylesheet">
  <link href="css/modal_add.css?time=?ver=<?=time()?>" rel="stylesheet">

  <!-- jquery confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">





  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic|Nanum+Myeongjo&display=swap&subset=korean" rel="stylesheet">

  <style> p { font-family: 'Nanum Gothic', sans-serif; } </style>


  <style>
  
  @font-face {
    font-family: 'Daehan';
    font-style: normal;
    font-weight: 400;
    src: url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff2') format('woff2'), url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff') format('woff');
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



  <!--<script src="js/jquery.min.js"></script>-->
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
            <a href="dashboard_sangjo.php" class="site_title"><i class="glyphicon glyphicon-star-empty"></i> <span>상조물류관리</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="images/user.png" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info" storage_idx='<?=$admin_info['storage_sangjo']?>'>
              <span><?=$admin_info['t_storage_sangjo']?></span>
              <h2><?=$admin_info['admin_name']?> 님</h2>
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




          <?php

          if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_sangjo'] == "상조물류관리자"){?>

            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">
                
              
              
              <li><a ><i class="fa fa-download"></i> 생산 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_input/input">생산/입고</a>
                    </li>  
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_input/input&mode=QR">생산입력(QR코드)</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_in/list">입고내역</a>
                    </li>

                    <!-- <li><a href="<?=$PHP_SELF?>?page=statistics/daily/list">일별생산통계</a>
                    </li> -->

                  </ul>
                </li>


                <li><a><i class="fa fa-truck"></i> 주문/출고 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/client_input/order">주문서 등록</a>
                    </li>
                    <!-- <li><a href="<?=$PHP_SELF?>?page=sangjo/out_order/list&mode=order">주문내역</a>
                    </li> -->

                    <!-- <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_input/out">출고지시서 등록</a>
                    </li> -->
                    <!-- <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_out/view">출고지시서 보기</a>
                    </li> -->
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_out/view_delivery">출고지시서 목록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_out/list">출고내역</a>
                    </li>
                    <!-- <li><a href="<?=$PHP_SELF?>?page=sangjo/out_order/list&mode=cancel">취소내역</a>
                    </li> -->
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_out/cancel_list">취소내역</a>
                    </li>
                  </ul>
                </li>

                <li><a><i class="fa fa-random"></i> 이동 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_input/move">이동지시서 작성</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_move/view_in">이동입고현황</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_move/view_out">이동출고현황</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_move/list">이동내역</a>
                    </li>
                  </ul>
                </li>

                <li><a><i class="fa fa-cogs"></i> 조정 <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_input/adjust">조정</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_adjust/list">조정내역</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_adjust/history">상품별 히스토리</a>
                    </li>
                  </ul>
                </li>


                <li><a><i class="fa fa-cubes"></i> 관리 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/category1/list">대분류</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/category2/list">중분류</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_product/list">제품관리</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_list/list">창고관리</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/client/list">고객사관리</a>
                    </li>

                  </ul>
                </li>

                

                <li><a><i class="fa fa-bar-chart-o"></i> 재고분석 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage/list">전체재고</a>
                    </li>

                    <?php
            if($admin_info['admin_id'] == "triplen123@naver.com"){?>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage/list_test">전체재고(테스트)</a>
                    </li>
            <?}
              ?>

                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage/list_branch">더존재고</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage/list_branch2">대성재고</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage/perStorage">창고별재고</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_input/safe">안전재고설정</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_alarm/list">안전재고알림</a>
                    </li>
                    <!--
                    <li><a href="<?=$PHP_SELF?>?page=sangjo/storage_statistics/list">입출고분석</a>
                    </li>
                     -->
                  </ul>
                </li>


                <li><a><i class="fa fa-cubes"></i> 거래명세서 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display:none;">

                      <li><a href="<?=$PHP_SELF?>?page=sangjo/bill/list&st=">전체</a>
                      </li>
                      <li><a href="<?=$PHP_SELF?>?page=sangjo/bill/list&st=미발행">작성(미발행)</a>
                      </li>
                      <li><a href="<?=$PHP_SELF?>?page=sangjo/bill/list&st=미발송">저장/미발송</a>
                      </li>

                      <li><a href="<?=$PHP_SELF?>?page=sangjo/bill/list&st=발송완료">발송완료/수락대기</a>
                      </li>
                      <li><a href="<?=$PHP_SELF?>?page=sangjo/bill/list&st=수정요청">수정요청</a>
                      </li>
                      <li><a href="<?=$PHP_SELF?>?page=sangjo/bill/list&st=승인완료">승인완료</a>
                      </li>

                    </ul>

                </li>


              <? if($admin_info['admin_idx'] == "1"){?>
                
                <li><a><i class="fa fa-cubes"></i> 정산(OLD) <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display:none;">

                      <li><a href="<?=$PHP_SELF?>?page=statistics/fu_cnst_monthly/list">거래처별 매출현황</a>
                      </li>

                      <li><a href="<?=$PHP_SELF?>?page=statistics/fu_cnst_m/list">거래처별 매출현황(월별)</a>
                      </li>

                      <li><a href="<?=$PHP_SELF?>?page=sangjo/bill/list">거래명세서 발행</a>
                      </li>

                    </ul>

                </li>
                

                <? } ?>



              </ul>

            </div>


          <?}else if($admin_info['pm_sangjo'] == "상조물류창고관리자" && $admin_info['t_storage_sangjo'] != ""){?>

            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">



              <li><a ><i class="fa fa-download"></i> 생산 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" >
                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/storage_input/input">생산/입고</a>
                    </li>  

                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/storage_in/list">입고내역</a>
                    </li>

                  </ul>
                </li>


                <li><a ><i class="fa fa-truck"></i> 출고 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/client_input/order">주문서 등록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/storage_out/view_delivery">주문서 목록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/storage_out/list_local">출고내역</a>
                    </li>
                  </ul>
                </li>

                <li><a ><i class="fa fa-random"></i> 이동 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/storage_move/view_out_local">이동출고</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/storage_move/view_in_local">이동입고</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=sangjo_local/storage_move/list_local">이동내역</a>
                    </li>
                  </ul>
                </li>




              </ul>

          </div>


          <?}?>


          <?php

          require('dashboard_left_common.php')
          ?>




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
                <i class="fa fa-user emoticon_user"></i><?=$admin_info['admin_name']?>
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
      include("contents/".$_GET['page'].".php");
    }else{

      if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_sangjo'] == "상조물류관리자" ){

        include("contents/sangjo/storage_in/list.php");

      }else{
        if($admin_info['pm_sangjo'] == "상조물류창고관리자"){


          if($admin_info['t_storage_sangjo'] != ""){
            include("contents/sangjo_local/storage_out/view_local.php");
          }else{ //창고미지정 상태

            include("contents/common/empty_html.php");
          }
        }else if($admin_info['pm_sangjo'] == "상조물류뷰어"){
          include("contents/common/viewer_html.php");

        }else{ //
          //xxxxx 경우의수 없음
          include("contents/common/empty_html.php");
        }
      }
    }









?>


<?php

include('dashboard_common.php')

?>


</body>

</html>
