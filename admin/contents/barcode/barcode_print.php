<?php


header('Content-Type: text/html; charset=utf-8');
//session_start();

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //NEULFLOWER DB 접속
//$dbcon = $db->connect();
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
require($_SERVER["DOCUMENT_ROOT"].'/lib/config.php');


if($_REQUEST['part'] == "sffm"){
  $dbcon = $db->fullfillment_connect();

}


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




$sel = mysqli_query($dbcon, "select * from product where product_idx='".$_REQUEST['product_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);

if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $product_name = $data['product_name'];
 
}




if($_REQUEST['date'] != ""){
  $t_date = $_REQUEST['date'];
}else{
  $t_date = date("Y-m-d",time());
}




//barcode 규칙 : 2211230100001
$thisymd = date("ymd",strtotime($t_date));
$part_code = "01"; // 01: 상조, 02: 종합물류, 03: 화훼
$prd_idx_len = strlen($_REQUEST['product_idx']);
$zero_len = 5 - $prd_idx_len;
$zero_char = "";
for ($i=0;$i<$zero_len;$i++ )
{
   $zero_char .= "0";
}

$barcode = $thisymd.$part_code.$zero_char.$_REQUEST['product_idx'];


// 압축을 해제한 phpqrcode 폴더의 qrlib.php 파일을 include한다.
include_once "./phpqrcode/qrlib.php";
$qrContents = $barcode;
$filePath = sha1($qrContents).".png";

if(!file_exists($filePath)) {
    $ecc = 'H'; // L, M, Q, H, This parameter specifies the error correction capability of QR.
    $pixel_Size = 10; // This specifies the pixel size of QR.
    $frame_Size = 10; // level 1-10
    QRcode::png($qrContents, $filePath, $ecc, $pixel_Size, $frame_Size);
    //echo "파일이 정상적으로 생성되었습니다.";
    //echo "<hr/>";
} else {
    //echo "파일이 이미 생성되어 있습니다.\n파일을 지우고 다시 실행해 보세요.";
    //echo "<hr/>";
}

//echo "저장된 파일명 : ".$filePath;
//echo "<hr/>";
//echo "<center><img src='".$filePath."'/></center>";









?>



<?
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

//ini_set('display_errors', '0');



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

  <title>QR코드 출력</title>

  <!-- Bootstrap core v3.3.6 CSS -->
  <link hfef="../../css/bootstrap.min.css" rel="stylesheet">

  <!-- input box autocomplete ajax 연동에 필요, 신규메뉴추가시 기존메뉴명 검색할때 이용 -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic|Nanum+Myeongjo&display=swap&subset=korean" rel="stylesheet">


  <!--<script src="../../js/jquery.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
     function print_this(){
      window.print();
     }



  </script>


</head>
<body>

<style>
  .qr_table{
    margin-top: -20px;
    margin-left: 20px;
  }
table tr td{
  padding:10px 20px;
}

.qr_table img{
  width:300px;
}

table tr td.odd{
  padding: 10px 0px;
    padding-top: 50px;
    min-width: 250px;
    font-size: 18pt;
    font-weight: 600;
    text-align: right;
}

.btn_label{
  margin-left:10px;
}


.qr_img{
  margin-bottom:-80px;
}

td.img{

}
</style>



<!-- 제조일: <input type="date" name="product_date" class="form-control" id="product_date" value="<?=date("Y-m-d",time())?>">

출력수량: <input type="text" name="print_amount" class="form-control" id="print_amount" value="10">
<button type="button" class="btn btn-dark  btn_label" id="btn_label">라벨출력</button> -->

<table class='qr_table'>
<tr>
<td class='odd'>
  <span class='mtitle'></span><?=$product_name?>
  <br>
  <span class='mtitle'>제조일:</span><span class='pdate'><?=$t_date?></span>
  <br>
  <?=$barcode?>

</td>
<td class="td_img"><img class="qr_img" src='<?=$filePath?>'/></td>
</tr>


</table>
<script src="../../js/bootstrap.min.js"></script>




</body>
</html>
