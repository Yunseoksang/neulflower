

        
          <?

        if($admin_info['pm_super'] == "종합관리자"){?>


          <div class="menu_section">
            <h3></h3>
            <ul class="nav side-menu">

              <!-- <li><a href="dashboard_sffm.php" target="_blank"><i class="fa fa-laptop"></i> 종합물류관리 <span class="label label-success pull-right">Link</span></a>
              </li> -->
              <li><a href="dashboard_sffm.php" target="_blank"><i class="fa fa-laptop"></i> 종합물류관리<span class="label label-success pull-right">Link</span></a>
              </li>

              <li><a href="dashboard_flower.php" target="_blank"><i class="fa fa-laptop"></i> 화훼관리 <span class="label label-success pull-right">Link</span></a>
              </li>

              <li><a href="dashboard_sangjo.php" target="_blank"><i class="fa fa-laptop"></i> 상조물류관리(신버전) <span class="label label-success pull-right">Link</span></a>
              </li>





              <li><a href="dashboard_cnst.php" target="_blank"><i class="fa fa-laptop"></i> 계약업체관리 <span class="label label-success pull-right">Link</span></a>
              </li>


              <li><a href="dashboard_hrm.php" target="_blank"><i class="fa fa-laptop"></i> 인사총무관리 <span class="label label-success pull-right">Link</span></a>
              </li>


              <!--
              <li><a href="dashboard_TM.php" target="_blank"><i class="fa fa-laptop"></i> TM영업관리 <span class="label label-success pull-right">Link</span></a>
              </li>-->
              <li><a  href="<?=$PHP_SELF?>?page=admin_list/list"><i class="fa fa-user"></i> 관리자 목록 </a>
              </li>

              <li><a href="dashboard_sj.php" target="_blank"><i class="fa fa-laptop"></i> 상조물류관리(구버전) <span class="label label-success pull-right">Link</span></a>
              </li>


<!-- 
              <ul class="nav child_menu">

                <li><a  href="<?=$PHP_SELF?>?page=admin_list/admin_login_history/list"><i class="fa fa-user"></i> 관리자 로그인 이력 </a>
                </li>
              </ul> -->






              <?php
            if($admin_info['admin_id'] == "triplen123@naver.com"){?>
              <li><a  href="<?=$PHP_SELF?>?page=framework/list"><i class="fa fa-table"></i>관리자페이지 설정</a>
              </li>
            <?}
              ?>
           

              
            </ul>
          </div>

          <?
          }else{?>
          <div class="menu_section">
            <h3></h3>
            <ul class="nav side-menu">

            <? if($admin_info['pm_fullfillment'] != null){?>
              <li><a href="dashboard_sffm.php" target="_blank"><i class="fa fa-laptop"></i> 종합물류관리 <span class="label label-success pull-right">Link</span></a>
              </li>
            <?}?>

            <? if($admin_info['pm_flower'] != null){?>
              <li><a href="dashboard_flower.php" target="_blank"><i class="fa fa-laptop"></i> 화훼관리 <span class="label label-success pull-right">Link</span></a>
              </li>
              <?}?>

              <? if($admin_info['pm_sangjo'] != null){?>
              <li><a href="dashboard_sangjo.php" target="_blank"><i class="fa fa-laptop"></i> 상조물류관리(신버전) <span class="label label-success pull-right">Link</span></a>
              </li>
              <?}?>


            <? if($admin_info['pm_sangjo'] != null){?>
              <li><a href="dashboard_sj.php" target="_blank"><i class="fa fa-laptop"></i> 상조물류관리(구버전) <span class="label label-success pull-right">Link</span></a>
              </li>
              <?}?>




            <? if($admin_info['pm_consulting'] != null){?>
              <li><a href="dashboard_cnst.php" target="_blank"><i class="fa fa-laptop"></i> 계약업체관리 <span class="label label-success pull-right">Link</span></a>
              </li>
              <?}?>



              <?if($admin_info['pm_hrm'] == "총무관리자" || $admin_info['pm_hrm'] == "인사관리자" || $admin_info['pm_hrm'] == "인사총무관리자" ){?>

              <li><a href="dashboard_hrm.php" target="_blank"><i class="fa fa-laptop"></i> 인사총무관리 <span class="label label-success pull-right">Link</span></a>
              </li>


              <?}?>




            </ul>
          </div>


          <?}
          ?>
          




