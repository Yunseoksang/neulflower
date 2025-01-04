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


if(strpos($_SERVER['QUERY_STRING'],"consulting/") || strpos($_SERVER['QUERY_STRING'],"admin_list/")){
  if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_consulting'] != null){
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
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">


          <?php

          if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_consulting'] == "계약업체관리자"){?>

            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">
                <li><a><i class="fa fa-download"></i> TM 영업관리 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="?page=consulting_TM/list_TM">목록보기</a>
                    </li>  

                    <!-- <li><a href="?page=consulting/list">전체</a>
                    </li> -->

                  </ul>
                </li>
                <li><a href="?page=consulting/input&mode=TM"><i class="fa fa-plus"></i> 업체 등록 </a>
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

      if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_consulting'] == "계약업체관리자" ){

        include("contents/consulting_TM/list_TM.php");

      }else{
        if($admin_info['pm_consulting'] == "계약업체뷰어"){

            include("contents/common/viewer_html.php");

        }
      }
    }







?>

<?php

include('dashboard_common.php')

?>

</body>

</html>
