<?


header('Content-Type: text/html; charset=utf-8');
//session_start();

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //NEULFLOWER DB 접속
//$dbcon = $db->connect();
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
require($_SERVER["DOCUMENT_ROOT"].'/lib/config.php');





if($admin_info['admin_uuid'] == ""){
   header('Location:./login/'.$admin_info['admin_login_page']);
   exit;
}





if(strpos($_SERVER['QUERY_STRING'],"sfullfillment/") || strpos($_SERVER['QUERY_STRING'],"admin_list/")){
  if($admin_info['pm_super'] == "종합관리자" || $admin_info['pm_fullfillment'] != null){
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

  <title>거래명세서 출력</title>
  <? 
     require('./dashboard_common_head_html.php');
  ?>


</head>


<body >





      <!--우측 상세내역 영역2   --->
      <div class="col-md-6  col-sm-6  col-xs-12 hide" id="detail_section_bill">


        <div class="x_panel" id="printableArea">
            

            <div class="x_content" id="print_detail_section_bill">
                <div class="row">
                    <div class="col-sm-12">
                      <?
                        require($_SERVER["DOCUMENT_ROOT"]."/common/html/detail_html_bill.php");
                      
                      ?>
                    </div>
                </div>
            </div>
        </div>
        
      </div>
      <!-- /detail_section_bill -->







</body>

</html>
