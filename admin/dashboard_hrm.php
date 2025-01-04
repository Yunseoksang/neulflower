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







//$_GET['link']


// require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //NEULFLOWER DB 접속
// $dbcon = $db->connect_mk_fmoney();



require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //NEULFLOWER DB 접속
//$dbcon = $db->connect();
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
require($_SERVER["DOCUMENT_ROOT"].'/lib/config.php');



if($admin_info['admin_uuid'] == ""){
  header('Location:./login/'.$admin_info['admin_login_page']);
  exit;
}


if(strpos($_SERVER['QUERY_STRING'],"hrm") || strpos($_SERVER['QUERY_STRING'],"admin_list/")){
  if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_hrm'] != null){
      //
  }else{
    header('Location:./login/'.$admin_info['admin_login_page']);
    exit;  
  }
}






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

          <div class="navbar nav_title" style="border: 0;">
            <a href="dashboard_cnst.php" class="site_title"><i class="glyphicon glyphicon-star-empty"></i> <span>(주)늘</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="images/user.png" alt="..." class="img-circle profile_img">
              
            </div>
            <div class="profile_info" storage_idx='<?=$admin_info['storage_consulting']?>'>
              <span><?=$admin_info['t_storage_consulting']?></span>
              <h2><?=$admin_info['admin_name']?> 님</h2>
            </div>
          </div>
          <!-- /menu prile quick info -->

          <? 
           if($this_agent == "pc"){?>

          <br />
          <br />
          <br />
           <?}
          ?>



          <!-- sidebar menu -->
          <div  id="sidebar-menu" class="main_menu_side hidden-print main_menu">




            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">


              <?php

              if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_hrm'] == "인사관리자" || $admin_info['pm_hrm'] == "인사총무관리자"){?>

                <li><a><i class="fa fa-users"></i> 인사관리 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="?page=hrm/list&status=1">계약중</a>
                    </li>  

                    <li><a href="?page=hrm/list&status=2">계약종료</a>
                    </li>  

                    <? 
                    if($admin_info['pm_super'] == "종합관리자" || $admin_info['storage_hrm'] == "1"){?> 

                    <li><a href="?page=hrm/input"><i class="fa fa-plus"></i> 등록하기 </a>
                    <!-- <li><a href="?page=hrm/statistics"><i class="fa fa-bar-chart"></i> 통계 </a> -->

                    <? } ?>

                    


                  </ul>
                </li>

                </li>
                <li><a ><i class="fa fa-sitemap"></i> 소속관리 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">

                    <li><a href="?page=hrm/jisa/list">지사 목록</a>
                    </li>  
                    <li><a href="?page=hrm/organization/list">기관 목록</a>
                    </li>  
                    
                    <li><a href="?page=hrm/office/list">근무지 목록</a>
                    </li>  

                  </ul>
                </li>

                <?}?>

                <?php

                if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_hrm'] == "총무관리자" || $admin_info['pm_hrm'] == "인사총무관리자"){?>

                <li><a><i class="fa fa-key"></i> 총무관리 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="?page=cmu/list&status=1">관리중</a>
                    </li>  
                    <li><a href="?page=cmu/list&status=2">관리종료</a>
                    </li>  


                    <?
                    if($admin_info['pm_super'] == "종합관리자" || $admin_info['storage_hrm'] == "1"){?> 

                      <li><a href="?page=cmu/input"><i class="fa fa-plus"></i> 등록하기 </a>
                      <!-- <li><a href="?page=hrm/statistics"><i class="fa fa-bar-chart"></i> 통계 </a> -->

                    <? } ?>


                  </ul>
                </li>

                <?}?>

              </ul>
          </div>













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
              <a href="javascript:" id="menu_toggle"><i class="fa fa-bars"></i></a>
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

      if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_hrm'] == "인사관리자"  || $admin_info['pm_hrm'] == "인사총무관리자" ){

        include("contents/hrm/list.php");

      }else if($admin_info['pm_hrm'] == "총무관리자" ){
        include("contents/cmu/list.php");

      }
    }







?>

<?php

include('dashboard_common.php')

?>

</body>

</html>
