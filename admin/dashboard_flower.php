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

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //NEULFLOWER DB 접속
//$dbcon = $db->connect();
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
require($_SERVER["DOCUMENT_ROOT"].'/lib/config.php');



if($admin_info['admin_uuid'] == ""){
   header('Location:./login/'.$admin_info['admin_login_page']);
   exit;
}



if(strpos($_SERVER['QUERY_STRING'],"flower/") || strpos($_SERVER['QUERY_STRING'],"admin_list/")){
  if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_flower'] != null){
    //
  }else{
    header('Location:./login/'.$admin_info['admin_login_page']);
    exit;  
  }
}




//$_GET['link']


// require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //NEULFLOWER DB 접속
// $dbcon = $db->connect_mk_fmoney();





$sel = mysqli_query($dbcon, "select * from ".$db_admin.".admin_list where admin_uuid='".$admin_info['admin_uuid']."'  ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num == 0) {

   header('Location:./login/'.$admin_info['admin_login_page']);
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

  <title>(주)늘 물류생산관리 시스템</title>
  <? 
     require('./dashboard_common_head_html.php');
  ?>


</head>


<body class="nav-md">


  <div class="container body">


    <div class="main_container">


      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title navbar_flower" style="border: 0;">
            <a href="dashboard_flower.php" class="site_title"><i class="fa fa-pagelines"></i> <span>화훼관리</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="images/user.png" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info" storage_idx='<?=$admin_info['storage_flower']?>'>
              <span><?=$admin_info['t_storage_flower']?></span>
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

          if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_flower'] == "화훼관리자" ){?>

            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">
                <li><a  href="<?=$PHP_SELF?>?page=flower/board/list"><i class="fa fa-download"></i> 배송상황판 <span class="fa fa-chevron-down"></span></a>
                </li>


                <li><a><i class="fa fa-truck"></i> 주문/배송 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=flower/client_input/order">주문서 등록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=flower/out_order/list&mode=order">주문내역</a>
                    </li>

                    <!-- <li><a href="<?=$PHP_SELF?>?page=flower/storage_input/out">출고지시서 등록</a>
                    </li> -->
<!-- 
                    <li><a href="<?=$PHP_SELF?>?page=flower/storage_out/view_delivery">배송요청목록</a>
                    </li> -->

                    <li><a href="<?=$PHP_SELF?>?page=flower/out_order/list&mode=req">배송요청목록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=flower/out_order/list&mode=complete">배송완료목록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=flower/out_order/list&mode=cancel">취소내역</a>
                    </li>
                    <!-- <li><a href="/admin/dashboard_sj.php?page=sj/storage_input/out" target="_blank">상조용품출고지시</a>
                    </li> -->


                  </ul>
                </li>



                <li><a><i class="fa fa-cubes"></i> 관리 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">
                    <li><a href="<?=$PHP_SELF?>?page=flower/category1/list">대분류</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=flower/category2/list">중분류</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=flower/storage_product/list">제품관리</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=flower/storage_list/list">지점관리</a>
                    </li>

                  <?php

                  if($admin_info['super_flower_permission'] == 1 ){?>

                    <li><a href="<?=$PHP_SELF?>?page=flower/storage_list/list_2">협력사관리</a>
                    </li>
                  <?}
                  ?>

                    <li><a href="<?=$PHP_SELF?>?page=flower/client/list">고객사관리</a>
                    </li>

                  </ul>
                </li>

                <li><a><i class="fa fa-cubes"></i> 정산 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" style="display:none;">

                      <li><a href="<?=$PHP_SELF?>?page=statistics/flower_consulting_monthly/list">거래처별 매출현황</a>
                      </li>

                      <li><a href="<?=$PHP_SELF?>?page=statistics/flower_consulting_m/list">거래처별 매출현황(월별)</a>
                      </li>


                      <li><a href="<?=$PHP_SELF?>?page=statistics/flower_storage_monthly/list">협력사별 매출현황</a>
                      </li>
                      <li><a href="<?=$PHP_SELF?>?page=statistics/flower_storage_m/list">협력사별 매출현황(월별)</a>
                      </li>
<!-- 
                      <li><a href="<?=$PHP_SELF?>?page=statistics/flower_yearly_p/list">지역별 매출현황</a>
                      </li> -->
                    </ul>

                </li>


                <!-- <li><a><i class="fa fa-bar-chart-o"></i> 통계 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none;">


                  </ul>
                </li> -->


                


              </ul>

          </div>


          <?}else if($admin_info['pm_flower'] == "화훼지점관리자"  && $admin_info['t_storage_flower'] != ""){?>

            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">


                <li><a  ><i class="fa fa-truck"></i> 주문/배송 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?=$PHP_SELF?>?page=flower/out_order/list&mode=req">배송요청목록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=flower/out_order/list&mode=complete">배송완료목록</a>
                    </li>
                    <li><a href="<?=$PHP_SELF?>?page=statistics/flower_storage_m/list">월별매출조회</a>
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





          <div class="sidebar-footer hidden-small navbar_flower">

             

            <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
          </div>
        </div>
      </div>



      <!-- top navigation -->
      <div class="top_nav">

        <div class="nav_menu nav_menu_ffm">
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

				    <!---
                  <li><a href="javascript:;">  Profile</a>
                  </li>
                  <li>
                    <a href="javascript:;">
                      <span class="badge bg-red pull-right">50%</span>
                      <span>Settings</span>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:;">Help</a>
                  </li>
			      -->
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

      if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_flower'] == "화훼관리자" ){
        $_GET['mode'] = "order";

        include("contents/flower/out_order/list.php");

      }else{
        if($admin_info['pm_flower'] == "화훼지점관리자"){


          if($admin_info['t_storage_flower'] != ""){
            $_GET['mode'] = "req";
            include("contents/flower/out_order/list.php");
          }else{ //창고미지정 상태

            include("contents/common/empty_html.php");
          }
        }else if($admin_info['pm_flower'] == "화훼뷰어"){
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
