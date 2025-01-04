<?
//require_once '../lib/DB_Connect.php'; //DB 접속

//require_once('../lib/lib_api.php');



//unset($_COOKIE['admin_info']); //not working
setcookie("admin_info", "", time()-3600);
header('Location:login/index.php');
exit;

