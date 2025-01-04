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
  <link href="../../css/bootstrap.min.css" rel="stylesheet">

  <!-- input box autocomplete ajax 연동에 필요, 신규메뉴추가시 기존메뉴명 검색할때 이용 -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic|Nanum+Myeongjo&display=swap&subset=korean" rel="stylesheet">


  <!--<script src="../../js/jquery.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
     //$("#partner_menu").slideDown();
     $(document).on("change","#product_date",function(){
        var t_date = $(this).val();
        var url = 'barcode_print.php?part=sffm&product_idx=<?=$_REQUEST['product_idx']?>&date='+t_date;

        console.log(url);
        $('#barcode_frame').attr("src",url);

     });

     $(document).on("click","#btn_label",function(){

        var frm = document.getElementById("barcode_frame");
        frm.contentWindow.print_this();

     });

     
  });


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

#barcode_frame{
    border:2px dotted #3ca5ff;
    width:650px;;
    height:350px;
}


body {
    padding-top:50px;
    text-align:center;
}

#product_date{
    display: inline;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

</style>

<form class="form-horizontal form-label-left">

    <div class="form-group" >
        제조일 <span class="required">: </span>


        <input type="date" name="product_date" class="" id="product_date" value="<?=date("Y-m-d",time())?>" style="display:inline-block;">
        <button type="button" class="btn btn-success  btn_label" id="btn_label" style="display:inline-block;">라벨출력</button>

    </div>

    
    <div class="form-group">
        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"></label>
        <div class="col-md-3 col-sm-3 col-xs-12">
        </div>
    </div>

    </form>



<iframe id="barcode_frame" src="./barcode_print.php?part=sffm&product_idx=<?=$_REQUEST['product_idx']?>&date=<?=$t_date?>" >

</iframe>





<script src="../../js/bootstrap.min.js"></script>




</body>
</html>
